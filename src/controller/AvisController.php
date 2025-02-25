<?php

namespace app\src\controller;

use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;
use app\src\model\repository\AvisRepository;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\NotificationRepository;
use app\src\model\repository\StaffRepository;
use app\src\model\Request;

class AvisController extends AbstractController
{
    /**
     * @throws ServerErrorException
     */
    public function posterAvis(Request $request): string
    {
        if (Auth::has_role(Roles::Manager, Roles::ManagerAlternance, Roles::ManagerStage, Roles::Staff, Roles::Student, Roles::TutorTeacher)) {
            $identreprise = $request->getRouteParams()["id"];
            $idutilisateur = Auth::get_user()->id();
            $nomEntreprise = EntrepriseRepository::getNomEntrepriseById($identreprise);
            if ($nomEntreprise == null) {
                $nomEntreprise = "Entreprise inconnue";
            }
            if (Auth::has_role(Roles::Student)) {
                $form = new FormModel([
                    'Nom Entreprise' => $nomEntreprise,
                    'Avis' => FormModel::string("Avis")->required()->asterisk()->forget(),
                    'Visibilité' => FormModel::select("Visibilité", [
                        'Public' => 'Public',
                    ])->required()->asterisk()->default('Public')
                ]);
            } else {
                $form = new FormModel([
                    'Nom Entreprise' => $nomEntreprise,
                    'Avis' => FormModel::string("Avis")->required()->asterisk()->forget(),
                    'Visibilité' => FormModel::select("Visibilité", [
                        'Public' => 'Public',
                        'Privé' => 'Privé'
                    ])->required()->asterisk()->default('Public')
                ]);
            }
            if ($request->getMethod() == 'post') {
                if ($form->validate($request->getBody())) {
                    if ($form->getParsedBody()['Visibilité'] == 'Privé') AvisRepository::createAvis($identreprise, $idutilisateur, $form->getParsedBody()['Avis'], 1);
                    else AvisRepository::createAvis($identreprise, $idutilisateur, $form->getParsedBody()['Avis'], 0);

                    NotificationRepository::createNotification(Auth::get_user()->id(), "Vous avez déposer un avis sur l'entreprise " . $nomEntreprise, "/entreprises/" . "$identreprise");
                    $etudiants = EtudiantRepository::getAllId($idutilisateur);
                    foreach ($etudiants as $etudiant) {
                        if ($form->getParsedBody()['Visibilité'] == 'Public')
                            NotificationRepository::createNotification($etudiant['idutilisateur'], "Un avis a été déposé sur l'entreprise " . $nomEntreprise, "/entreprises/" . "$identreprise");
                    }
                    $staff = StaffRepository::getAllId($idutilisateur);
                    foreach ($staff as $staffMember) {
                        NotificationRepository::createNotification($staffMember['idutilisateur'], "Un avis a été déposé sur l'entreprise " . $nomEntreprise, "/entreprises/" . "$identreprise");
                    }
                    Application::redirectFromParam("/entreprises/" . $identreprise);
                }
            }
            return $this->render('avis/Avis', [
                'form' => $form
            ]);
        }
        Application::redirectFromParam("/entreprises");
        return "";
    }

    /**
     * @throws ServerErrorException
     */
    public function modifierAvis(Request $request): string
    {
        if (Auth::has_role(Roles::Manager, Roles::ManagerAlternance, Roles::ManagerStage, Roles::Staff, Roles::Student, Roles::TutorTeacher)) {
            $identreprise = $request->getRouteParams()["id"];
            $idutilisateur = Auth::get_user()->id();
            $nomEntreprise = EntrepriseRepository::getNomEntrepriseById($identreprise);
            if (Auth::has_role(Roles::Student)) {
                $form = new FormModel([
                    'Nom Entreprise' => $nomEntreprise,
                    'Avis' => FormModel::string("Avis")->required()->asterisk()->forget()->default(AvisRepository::getAvisById($identreprise, $idutilisateur)['avis']),
                    'Visibilité' => FormModel::select("Visibilité", [
                        'Public' => 'Public',
                    ])->required()->asterisk()->default('Public')
                ]);
            } else {
                $form = new FormModel([
                    'Nom Entreprise' => $nomEntreprise,
                    'Avis' => FormModel::string("Avis")->required()->asterisk()->forget()->default(AvisRepository::getAvisById($identreprise, $idutilisateur)['avis']),
                    'Visibilité' => FormModel::select("Visibilité", [
                        'Public' => 'Public',
                        'Privé' => 'Privé'
                    ])->required()->asterisk()->default(AvisRepository::getPrivate($identreprise, $idutilisateur)['private'] == 1 ? 'Privé' : 'Public')
                ]);
            }
            if ($request->getMethod() == 'post') {
                if ($form->validate($request->getBody())) {

                    if ($form->getParsedBody()['Visibilité'] == 'Privé') AvisRepository::updateAvis($identreprise, $idutilisateur, $form->getParsedBody()['Avis'], 1);
                    else AvisRepository::updateAvis($identreprise, $idutilisateur, $form->getParsedBody()['Avis'], 0);


                    NotificationRepository::createNotification(Auth::get_user()->id(), "Vous avez modifié votre avis sur l'entreprise " . $nomEntreprise, "/entreprises/" . "$identreprise");
                    $etudiants = EtudiantRepository::getAllId($idutilisateur);
                    foreach ($etudiants as $etudiant) {
                        if ($form->getParsedBody()['Visibilité'] == 'Public')
                            NotificationRepository::createNotification($etudiant['idutilisateur'], "Un avis a été modifié sur l'entreprise " . $nomEntreprise, "/entreprises/" . "$identreprise");
                    }
                    $staff = StaffRepository::getAllId($idutilisateur);
                    foreach ($staff as $staffMember) {
                        NotificationRepository::createNotification($staffMember['idutilisateur'], "Un avis a été modifié sur l'entreprise " . $nomEntreprise, "/entreprises/" . "$identreprise");
                    }
                    Application::redirectFromParam("/entreprises/" . $identreprise);
                }
            }
            return $this->render('avis/Avis', [
                'form' => $form
            ]);
        }
        Application::redirectFromParam("/entreprises");
        return "";
    }
}