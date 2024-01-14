<?php

namespace app\src\controller;

use app\src\core\exception\ForbiddenException;
use app\src\core\exception\NotFoundException;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\MailRepository;
use app\src\model\repository\NotificationRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\PostulerRepository;
use app\src\model\Request;

class CandidatureController extends AbstractController
{

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     * @throws NotFoundException
     */
    public function candidatures(Request $request): string
    {
        $userid = Application::getUser()->id();
        $idOffre = $request->getRouteParams()['idOffre'] ?? null;
        $idUtilisateur = $request->getRouteParams()['idUtilisateur'] ?? null;
        $entrepriseid = null;
        if (Auth::has_role(Roles::Enterprise)) $entrepriseid = $userid;

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

        if ($idOffre != null && $idUtilisateur != null) {
            $candidatures = (new PostulerRepository())->getById($idOffre, $idUtilisateur);
            if ($candidatures == null) throw new NotFoundException();
            $offre = (new OffresRepository())->getById($candidatures->getIdoffre());
            if (!$offre) throw new NotFoundException();
            if (Auth::has_role(Roles::Staff, Roles::Manager, Roles::Teacher, Roles::TutorTeacher, Roles::Tutor) || $candidatures->getIdutilisateur() == $userid || $offre->getIdutilisateur() == $entrepriseid) {
                return $this->render('candidature/detailCandidature', ['candidatures' => $candidatures]);
            } else throw new ForbiddenException();
        }

        $candidatures = [];

        if (Auth::has_role(Roles::Student)) {
            $candidatures = PostulerRepository::getByIdEtudiant($userid);
        } elseif (Auth::has_role(Roles::Enterprise)) {
            $candidatures = PostulerRepository::getByIdEntreprise($userid);
        } else if (Auth::has_role(Roles::Manager, Roles::Staff)) {
            $candidatures = PostulerRepository::getAllCandidatures();
        } else if (Auth::has_role(Roles::Teacher, Roles::Tutor, Roles::TutorTeacher)) {
            $postulerRepository = new PostulerRepository();
            if ($postulerRepository::getByStatementTuteur(Auth::get_user()->id(), 'validee') != null)
                $candidatures = array_merge($postulerRepository::getByStatementTuteur(Auth::get_user()->id(), 'validee'), $postulerRepository::getByStatementTuteur(Auth::get_user()->id(), 'refusee'), $postulerRepository::getByStatementAttenteTuteur());
        }

        $res = [
            "candidaturesAttente" => array_values(array_filter($candidatures, fn($candidature) => str_starts_with($candidature["statut"], "en attente"))),
            "candidaturesAutres" => array_values(array_filter($candidatures, fn($candidature) => !str_starts_with($candidature["statut"], "en attente")))
        ];
        return $this->render(
            'candidature/listCandidatures', $res);
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
                $emailEntreprise = EntrepriseRepository::getEmailById($idEntreprise);
                $mail = new MailRepository();
                $mail->send_mail([$emailEntreprise], Application::getUser()->full_name() . " vous a envoyer un message concernant l'offre " . $offre->getSujet(), "Message:\n" . $form->getParsedBody()['message']);
                NotificationRepository::createNotification($idEntreprise, "Vous avez reçu un message de " . Application::getUser()->full_name(), "");
                NotificationRepository::createNotification(Application::getUser()->id(), "Vous avez envoyé un message à " . EntrepriseRepository::getNomEntrepriseById($idEntreprise), "");
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
    public function validerAsEntreprise(Request $request): void
    {
        if (Auth::has_role(Roles::Enterprise)) {
            $idEtudiant = $request->getRouteParams()["idEtudiant"];
            $idOffre = $request->getRouteParams()["idOffre"];
            PostulerRepository::validerCandidatureEntreprise($idEtudiant, $idOffre);
            NotificationRepository::createNotification($idEtudiant, "Votre candidature a été acceptée", "/candidatures/" . $idOffre . "/" . $idEtudiant);
            NotificationRepository::createNotification(Auth::get_user()->id(), "Vous avez accepté la candidature de " . EtudiantRepository::getFullNameByID($idEtudiant), "/candidatures/" . $idOffre . "/" . $idEtudiant);
            Application::redirectFromParam("/candidatures");
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function validerAsEtudiant(Request $request): void
    {
        if (Auth::has_role(Roles::Student)) {
            $idEtudiant = $request->getRouteParams()["idEtudiant"];
            $idOffre = $request->getRouteParams()["idOffre"];
            PostulerRepository::validerCandidatureEtudiant($idEtudiant, $idOffre);
            NotificationRepository::createNotification(EntrepriseRepository::getIdEntrepriseByIdOffre($idOffre), "Votre candidature a été acceptée par l'etudiant " . EtudiantRepository::getFullNameByID($idEtudiant), "/candidatures/" . $idOffre . "/" . $idEtudiant);
            NotificationRepository::createNotification($idEtudiant, "Vous avez accepté la candidature de " . EtudiantRepository::getFullNameByID($idEtudiant), "/candidatures/" . $idOffre . "/" . $idEtudiant);
            Application::redirectFromParam("/candidatures");
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function refuser(Request $request): void
    {
        if (Auth::has_role(Roles::Student, Roles::Enterprise)) {
            $idEtudiant = $request->getRouteParams()["idEtudiant"];
            $idOffre = $request->getRouteParams()["idOffre"];
            PostulerRepository::refuserCandidature($idEtudiant, $idOffre);
            if (Auth::has_role(Roles::Enterprise)) {
                NotificationRepository::createNotification($idEtudiant, "Votre candidature a été refusée", "/candidatures/" . $idOffre . "/" . $idEtudiant);
                NotificationRepository::createNotification(Auth::get_user()->id(), "Vous avez refusé la candidature de " . EtudiantRepository::getFullNameByID($idEtudiant), "/candidatures/" . $idOffre . "/" . $idEtudiant);
            } else {
                NotificationRepository::createNotification(EntrepriseRepository::getIdEntrepriseByIdOffre($idOffre), "Votre candidature a été refusée par l'etudiant " . EtudiantRepository::getFullNameByID($idEtudiant), "/candidatures/" . $idOffre . "/" . $idEtudiant);
                NotificationRepository::createNotification($idEtudiant, "Vous avez refusé la candidature de " . EtudiantRepository::getFullNameByID($idEtudiant), "/candidatures/" . $idOffre . "/" . $idEtudiant);
            }
            Application::redirectFromParam("/candidatures");
        }
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function harceler(): string
    {
        if (Auth::has_role(Roles::ChefDepartment, Roles::Manager, Roles::ManagerStage, Roles::ManagerAlternance, Roles::Staff)) {
            if (isset($_GET["idUtilisateur"])) {
                $idUtilisateur = $_GET["idUtilisateur"];
                $etudiant = (new EtudiantRepository([]))->getByIdFull($idUtilisateur);
                $mail = new MailRepository();
                $mail->send_mail([$etudiant->getEmail()], "Vous n'avez pas encore de convention", "Bonjour,\nVous n'avez pas encore de convention de stage, il serait temps de s'en occuper !");
                NotificationRepository::createNotification($idUtilisateur, "Vous n'avez pas encore de convention", "/conventions");
                NotificationRepository::createNotification(Auth::get_user()->getId(), "Vous avez harcelé " . $etudiant->getNom() . " " . $etudiant->getPrenom(), "");
                Application::redirectFromParam("/harceler");
            }
            if (isset($_GET["isAdmin"])) {
                $etudiants = EtudiantRepository::getAllEleveNoConvention();
                foreach ($etudiants as $etudiant) {
                    $mail = new MailRepository();
                    $mail->send_mail([$etudiant['email']], "Vous n'avez pas encore de convention", "Bonjour,\nVous n'avez pas encore de convention de stage, il serait temps de s'en occuper !");
                    NotificationRepository::createNotification($etudiant['idutilisateur'], "Vous n'avez pas encore de convention", "/conventions");
                    NotificationRepository::createNotification(Auth::get_user()->getId(), "Vous avez harcelé " . $etudiant['nom'] . " " . $etudiant['prenom'], "");
                }
                Application::redirectFromParam("/harceler");
            }
            $etudiants = (new EtudiantRepository([]))->getEtuSansConv();
            return $this->render('candidature/harceler', ['etudiants' => $etudiants]);
        } else throw new ForbiddenException();
    }

}
