<?php

namespace app\src\controller;

use app\src\controller\AbstractController;
use app\src\core\exception\ForbiddenException;
use app\src\core\exception\NotFoundException;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\MailRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\PostulerRepository;
use app\src\model\repository\TuteurEntrepriseRepository;
use app\src\model\repository\TuteurRepository;
use app\src\model\Request;

class CandidatureController extends AbstractController
{

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function candidatures(Request $request): string
    {
        $userid = Application::getUser()->id();
        $idOffre = $request->getRouteParams()['idOffre'] ?? null;
        $idUtilisateur = $request->getRouteParams()['idUtilisateur'] ?? null;
        $candidatures = (new PostulerRepository())->getById($idOffre, $idUtilisateur);
//        if (Auth::has_role(Roles::Tutor)) {
//            $entrepriseid = (new TuteurRepository([]))->getIdOffreByIdEtuAndIdOffre($userid,$idOffre);
//        }
        if (Auth::has_role(Roles::Enterprise)) $entrepriseid = $userid;
        if ($candidatures != null && $idOffre != null && $idUtilisateur != null) {
            $offre = (new OffresRepository())->getById($candidatures->getIdoffre());
            if (Auth::has_role(Roles::Staff, Roles::Manager, Roles::Teacher,Roles::TutorTeacher,Roles::Tutor) || $candidatures->getIdutilisateur() == $userid || $offre->getIdutilisateur() == $entrepriseid) {
                return $this->render('candidature/detailCandidature', ['candidatures' => $candidatures]);
            } else throw new ForbiddenException();
        }
        if ($request->getMethod() === 'post') {
            $id = explode('_', $_POST['idcandidature']);
            $idOffre = $id[0] ?? null;
            $idUtilisateur = $id[1] ?? null;
            $candidature = (new PostulerRepository())->getById($idOffre, $idUtilisateur);
            if ($request->getBody()['action'] === 'Accepter') {
                $candidature->setStatutPostuler("valider");
            } else {
                $candidature->setStatutPostuler("refuser");
            }
        }
        if (Auth::has_role(Roles::Enterprise)) {
            $array = ['candidaturesAttente' => (new PostulerRepository())->getCandidaturesAttenteEntreprise($entrepriseid),
                'candidaturesAutres' => (new PostulerRepository())->getByIdEntreprise($entrepriseid)
            ];
        } else if (Auth::has_role(Roles::Manager, Roles::Staff)) {
            $array = ['candidaturesAttente' => (new PostulerRepository())->getByStatementAttente(),
                'candidaturesAutres' => (new PostulerRepository())->getByStatementValideeOrRefusee()
            ];
        } else if (Auth::has_role(Roles::Teacher,Roles::Tutor, Roles::TutorTeacher)) {
            $array = ['candidaturesAttente' => (new PostulerRepository())->getByStatementAttenteTuteur()];
            $array['candidaturesAutres'] = array_merge((new PostulerRepository())->getByStatementTuteur(Auth::get_user()->id(), 'validee'), (new PostulerRepository())->getByStatementTuteur(Auth::get_user()->id(), 'refusee'));
        } else if (Auth::has_role(Roles::Student)) {
            $array = ['candidaturesAttente' => (new PostulerRepository())->getCandidaturesAttenteEtudiant($userid),
                'candidaturesAutres' => (new PostulerRepository())->getByIdEtudiant($userid)
            ];
        } else throw new ForbiddenException();
        return $this->render(
            'candidature/listCandidatures', $array);
    }

    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws ServerErrorException
     */
    public function contacterEntreprise(Request $req): string
    {
        if (!Auth::has_role(Roles::Student)) throw new ForbiddenException();
        $form = new FormModel([
            'message' => FormModel::string("Message")->required()->asterisk()->forget(),
        ]);
        if ($req->getMethod() === 'post') {
            if ($form->validate($req->getBody())) {
                $idoffre = $req->getRouteParams()["id"];
                $offre = (new OffresRepository())->getById($idoffre);
                if ($offre == null) throw new NotFoundException();
                $idEntreprise = $offre->getIdutilisateur();
                $emailEntreprise = (new EntrepriseRepository([]))->getByIdFull($idEntreprise)->getEmailutilisateur();
                $mail = new MailRepository();
                $mail->send_mail([$emailEntreprise], Application::getUser()->full_name() . " vous a envoyer un message concernant l'offre " . $offre->getSujet(), "Message:\n" . $form->getParsedBody()['message']);
                Application::redirectFromParam('/candidatures');
            }
        }
        return $this->render('candidature/contacter', [
            'form' => $form
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public function validerAsEntreprise(Request $request)
    {
        if (Auth::has_role(Roles::Enterprise)) {
            $idEtudiant = $request->getRouteParams()["idEtudiant"];
            $idOffre = $request->getRouteParams()["idOffre"];
            (new PostulerRepository())->validerCandidatureEntreprise($idEtudiant, $idOffre);
            Application::redirectFromParam("/candidatures");
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function validerAsEtudiant(Request $request)
    {
        if (Auth::has_role(Roles::Student)) {
            $idEtudiant = $request->getRouteParams()["idEtudiant"];
            $idOffre = $request->getRouteParams()["idOffre"];
            (new PostulerRepository())->validerCandidatureEtudiant($idEtudiant, $idOffre);
            Application::redirectFromParam("/candidatures");
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function refuser(Request $request)
    {
        if (Auth::has_role(Roles::Student, Roles::Enterprise)) {
            $idEtudiant = $request->getRouteParams()["idEtudiant"];
            $idOffre = $request->getRouteParams()["idOffre"];
            (new PostulerRepository())->refuserCandidature($idEtudiant, $idOffre);
            Application::redirectFromParam("/candidatures");
        }
    }
}