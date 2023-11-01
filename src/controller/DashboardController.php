<?php

namespace app\src\controller;

use app\src\core\exception\ForbiddenException;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\repository\CandidatureRepository;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\MailRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\StaffRepository;
use app\src\model\repository\TuteurEntrepriseRepository;
use app\src\model\repository\TuteurRepository;
use app\src\model\repository\UtilisateurRepository;
use app\src\model\Request;

class DashboardController extends AbstractController
{
    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function utilisateurs(Request $request): string
    {
        $id = $request->getRouteParams()['id'] ?? null;
        if (!is_null($id) && !Auth::has_role(Roles::Manager, Roles::Staff)) throw new ForbiddenException();
        $utilisateur = null;
        if ($id != null) {
            if ((new EntrepriseRepository([]))->getByIdFull($id) != null) {
                $utilisateur = (new EntrepriseRepository([]))->getByIdFull($id);
                return $this->render('utilisateurs/detailEntreprise', ['utilisateur' => $utilisateur]);
            } elseif ((new EtudiantRepository([]))->getByIdFull($id) != null && $id != null) {
                $utilisateur = (new EtudiantRepository([]))->getByIdFull($id);
                return $this->render('utilisateurs/detailEtudiant', ['utilisateur' => $utilisateur]);
            } elseif ((new TuteurRepository([]))->getByIdFull($id) != null && $id != null) {
                $utilisateur = (new TuteurRepository([]))->getByIdFull($id);
                return $this->render('utilisateurs/detailTuteur', ['utilisateur' => $utilisateur]);
            } elseif ((new StaffRepository([]))->getByIdFull($id) != null && $id != null) {
                $utilisateur = (new StaffRepository([]))->getByIdFull($id);
                return $this->render('utilisateurs/detailStaff', ['utilisateur' => $utilisateur]);
            }
        }
        $utilisateur = (new UtilisateurRepository([]))->getAll();
        return $this->render('utilisateurs/utilisateurs', ['utilisateurs' => $utilisateur]);
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function ListeTuteurPro(Request $request): string
    {
        if (Auth::has_role(Roles::Manager, Roles::Staff)) $tuteurs = (new TuteurEntrepriseRepository([]))->getAll();
        else if (Auth::has_role(Roles::Enterprise)) $tuteurs = (new TuteurEntrepriseRepository([]))->getAllTuteursByIdEntreprise(Application::getUser()->id());
        else throw new ForbiddenException();
        return $this->render('tuteurPro/listeTuteurPro', ['tuteurs' => $tuteurs]);
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */


    public function candidatures(Request $request): string
    {

        $userid = Application::getUser()->id();

        $id = $request->getRouteParams()['id'] ?? null;
        $candidatures = (new CandidatureRepository())->getById($id);
        if (Auth::has_role(Roles::Tutor)) $entrepriseid = (new TuteurEntrepriseRepository())->getById($userid)->getIdentreprise();
        else if (Auth::has_role(Roles::Enterprise)) $entrepriseid = $userid;
        if ($candidatures != null && $id != null) {
            $offre = (new OffresRepository())->getById($candidatures->getIdoffre());
            if (Auth::has_role(Roles::Staff, Roles::Manager, Roles::Teacher) || $candidatures->getIdutilisateur() == $userid || $offre->getIdutilisateur() == $entrepriseid) {
                return $this->render('candidature/detailCandidature', ['candidatures' => $candidatures]);
            } else throw new ForbiddenException();
        }
        if ($request->getMethod() === 'post') {
            $id = $request->getBody()['idcandidature'] ?? null;
            $candidature = (new CandidatureRepository())->getById($id);
            if ($request->getBody()['action'] === 'Accepter') {
                $candidature->setEtatcandidature("accepted");
            } else {
                $candidature->setEtatcandidature("declined");
            }
        }
        if (Auth::has_role(Roles::Enterprise, Roles::Tutor)) {
            $array = ['candidaturesAttente' => (new CandidatureRepository())->getByIdEntreprise($entrepriseid, 'on hold'),
                'candidaturesAutres' => array_merge((new CandidatureRepository())->getByIdEntreprise($entrepriseid, 'accepted'), (new CandidatureRepository())->getByIdEntreprise($entrepriseid, 'declined'))
            ];
        } else if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::Teacher)) {

            $array = ['candidaturesAttente' => (new CandidatureRepository())->getByStatement('on hold'),
                'candidaturesAutres' => array_merge((new CandidatureRepository())->getByStatement('accepted'), (new CandidatureRepository())->getByStatement('declined'))
            ];
        } else if (Auth::has_role(Roles::Student)) {
            $array = ['candidaturesAttente' => (new CandidatureRepository())->getByIdEtudiant($userid, 'on hold'),
                'candidaturesAutres' => array_merge((new CandidatureRepository())->getByIdEtudiant($userid, 'accepted'), (new CandidatureRepository())->getByIdEtudiant($userid, 'declined'))
            ];
        } else throw new ForbiddenException();
        return $this->render(
            'candidature/listCandidatures', $array);
    }

    /**
     * @throws ServerErrorException
     */
    public function archiver(Request $req): string
    {
        $user = (new UtilisateurRepository([]))->getUserById($req->getRouteParams()["id"]);
        if ((new UtilisateurRepository([]))->isArchived($user)) {
            (new UtilisateurRepository([]))->setUserToArchived($user, false);
            (new MailRepository())->send_mail([$user->getEmailutilisateur()], "Désarchivage de votre compte", "Votre compte a été désarchivé");
        } else {
            (new UtilisateurRepository([]))->setUserToArchived($user, true);
            (new MailRepository())->send_mail([$user->getEmailutilisateur()], "Archivage de votre compte", "Votre compte a été archivé");
        }
        Application::$app->response->redirect('/utilisateurs/' . $req->getRouteParams()["id"]);
        return '';
    }
}