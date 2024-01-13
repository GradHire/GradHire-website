<?php

namespace app\src\controller;

use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\NotificationRepository;
use app\src\model\repository\PostulerRepository;
use app\src\model\repository\StaffRepository;
use app\src\model\repository\TuteurRepository;
use app\src\model\Request;

class PostulerController extends AbstractController
{

    /**
     * @throws ServerErrorException
     */
    public function se_proposerTuteur(Request $request): void
    {
        if (Auth::has_role(Roles::Teacher, Roles::TutorTeacher)) {
            $idOffre = $request->getRouteParams()["idoffre"] ?? null;
            $idEtudiant = $request->getRouteParams()["idetudiant"] ?? null;
            $idUtilisateur = Application::getUser()->id();
            $countPostulation = StaffRepository::getCountPostulationTuteur($idUtilisateur);
            if ($countPostulation < 10) {
                TuteurRepository::seProposerProf($idUtilisateur, $idOffre, $idEtudiant);
                NotificationRepository::createNotification(Auth::get_user()->id(),"Vous avez postulé pour être tuteur d'un étudiant","/candidatures");
                NotificationRepository::createNotification($idEtudiant,"Un professeur a postulé pour être votre tuteur","/candidatures");
            }
            Application::redirectFromParam("/candidatures");
        }
    }


    /**
     * @throws ServerErrorException
     */
    public function se_deproposerProf(Request $request): void {
        if (Auth::has_role(Roles::Teacher, Roles::TutorTeacher)) {
            $idOffre = $request->getRouteParams()["id"] ?? null;
            $idEtudiant = (new PostulerRepository())->getByIdOffre($idOffre)[0]->getIdUtilisateur();
            $idUtilisateur = Application::getUser()->id();
            $countPostulation = StaffRepository::getCountPostulationTuteur($idUtilisateur);
            if($countPostulation > 0) {
               TuteurRepository::seDeProposerProf($idUtilisateur,$idOffre,$idEtudiant);
               NotificationRepository::createNotification(Auth::get_user()->id(),"Vous vous êtes dépostulé pour être tuteur d'un étudiant","/candidatures");
                NotificationRepository::createNotification($idEtudiant,"Un professeur s'est dépostulé pour être votre tuteur","/candidatures");
            }
            Application::redirectFromParam("/candidatures");
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function assignerTuteurEntreprise(Request $request): void {
        if (Auth::has_role(Roles::Enterprise)) {
            $idOffre = $request->getRouteParams()["idOffre"] ?? null;
            $idEtudiant = $request->getRouteParams()["idEtu"] ?? null;
            $idUtilisateur = $request->getRouteParams()["idUser"] ?? null;
            $idTuteurEntreprise = $request->getBody()["idtuteur"] ?? null;
            TuteurRepository::assigneCommeTuteurEntreprise($idUtilisateur,$idOffre,$idEtudiant,$idTuteurEntreprise);
            NotificationRepository::createNotification(Auth::get_user()->id(),"Vous avez assigné un tuteur à un étudiant","/candidatures");
            NotificationRepository::createNotification($idEtudiant,"Un tuteur vous a été assigné","/candidatures");
            NotificationRepository::createNotification($idTuteurEntreprise,"Vous avez été assigné comme tuteur d'un étudiant","/candidatures");
            Application::redirectFromParam("/candidatures");
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function listeTuteurPostuler(Request $request): string
    {
        $idOffre = $request->getRouteParams()["idOffre"] ?? null;
        $postulations = (new PostulerRepository())->getTuteurByIdOffre($idOffre);
        $postulationValider = null;
        foreach ($postulations as $postulation) {
            //si le professeur est déjà tuteur de l'étudiant, on le garde
            if ((new TuteurRepository([]))->getIfTuteurAlreadyExist($postulation["idutilisateur"], $idOffre, $postulation["idetudiant"])) {
                $postulationValider[] = $postulation;
                break;
            }
        }
        if ($postulationValider != null) {
            return $this->render('candidature/listeTuteurPostuler', ['postulations' => $postulationValider, 'idOffre' => $idOffre]);
        }
        return $this->render('candidature/listeTuteurPostuler', ['postulations' => $postulations, 'idOffre' => $idOffre]);
    }

    /**
     * @throws ServerErrorException
     */
    public function accepterAsTuteur(Request $request): void{
        if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::ManagerAlternance, Roles::ManagerStage)) {
            $idUtilisateur = $request->getRouteParams()["idUser"] ?? null;
            $staff = (new StaffRepository([]))->getByIdFull($idUtilisateur);
            $idOffre = $request->getRouteParams()["idOffre"] ?? null;
            $idEtudiant = $request->getRouteParams()["idEtu"] ?? null;
            TuteurRepository::addTuteur($staff->getIdutilisateur(),$idOffre,$idEtudiant);
            NotificationRepository::createNotification(Auth::get_user()->id(),"Vous avez accepté un tuteur pour un étudiant","/candidatures");
            NotificationRepository::createNotification($idEtudiant,"Le Tuteur assigné à votre candidature a été accepté","/candidatures");
            NotificationRepository::createNotification($idUtilisateur,"Vous avez été accepté comme tuteur","/candidatures");
            $tuteutsToRefuse = TuteurRepository::getTuteurWhereIsNotMyId($staff->getIdutilisateur(),$idOffre,$idEtudiant);
            foreach ($tuteutsToRefuse as $tuteurToRefuse){
                NotificationRepository::createNotification($tuteurToRefuse->getIdutilisateur(),"Vous avez été refusé comme tuteur pour l'etudiant ".EtudiantRepository::getFullNameByID($idEtudiant),"/candidatures");
            }
        }
        Application::redirectFromParam("/candidatures");
    }

    /**
     * @throws ServerErrorException
     */
    public function annulerAsTuteur(Request $request): void{
        if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::ManagerAlternance, Roles::ManagerStage)) {
            $idUtilisateur = $request->getRouteParams()["idUser"] ?? null;
            $staff = (new StaffRepository([]))->getByIdFull($idUtilisateur);
            $idOffre = $request->getRouteParams()["idOffre"] ?? null;
            $idEtudiant = $request->getRouteParams()["idEtu"] ?? null;
            TuteurRepository::annulerTuteur($staff->getIdutilisateur(),$idOffre,$idEtudiant);
            NotificationRepository::createNotification(Auth::get_user()->id(),"Vous avez annulé un tuteur pour un étudiant","/candidatures");
            NotificationRepository::createNotification($idEtudiant,"Le Tuteur assigné à votre candidature a été annulé","/candidatures");
            NotificationRepository::createNotification($idUtilisateur,"Vous avez été annulé comme tuteur","/candidatures");
            $tuteurEnAttente = TuteurRepository::getTuteurEnAttente($staff->getIdutilisateur(),$idOffre,$idEtudiant);
            foreach ($tuteurEnAttente as $tuteur){
                NotificationRepository::createNotification($tuteur->getIdutilisateur(),"Vous êtes à nouveau en attente pour être tuteur pour l'etudiant ".EtudiantRepository::getFullNameByID($idEtudiant),"/candidatures");
            }
        }
        Application::redirectFromParam("/candidatures");
    }
}