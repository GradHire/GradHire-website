<?php

namespace app\src\controller;

use app\src\core\exception\ForbiddenException;
use app\src\core\exception\NotFoundException;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;
use app\src\model\repository\ConventionRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\NotesRepository;
use app\src\model\repository\SoutenanceRepository;
use app\src\model\Request;

class SoutenanceController extends AbstractController
{

    /**
     * @throws ServerErrorException
     * @throws NotFoundException
     */
    public function createSoutenance(Request $request)
    {
        $numConvention = $request->getRouteParam("numConvention");
        if ($numConvention === null) {
            return $this->render("error", [
                "error" => "Numéro de convention non valide"
            ]);
        } else {
            $infosConvention = ConventionRepository::getByNumConvention($numConvention);
            if (!$infosConvention) throw new NotFoundException();
            $form = new FormModel([
                'debut_soutenance' => FormModel::Date("Début de la soutenance")->Required()->asterisk()->forget()->withHour(),
                'fin_soutenance' => FormModel::Date("Fin de la soutenance")->Required()->asterisk()->forget()->withHour()
            ]);
            if ($request->getMethod() == "post") {
                if ($form->validate($request->getBody())) {
                    print_r($infosConvention);
                    $values = array_merge($form->getParsedBody(), [
                        'numconvention' => $infosConvention['numconvention'],
                        'idtuteurprof' => $infosConvention['idtuteurprof'],
                        'idtuteurentreprise' => $infosConvention['idtuteurentreprise'],
                    ]);
                    SoutenanceRepository::createSoutenance($values);
                    Application::redirectFromParam("/conventions");
                }
            }
            return $this->render("soutenance/create", [
                "form" => $form
            ]);
        }
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function etreJury(Request $request): void
    {
        if (Auth::has_role(Roles::Teacher, Roles::TutorTeacher)) {
            $numConvention = $request->getRouteParam("numConvention");
            $idtuteurprof = Application::getUser()->id();
            SoutenanceRepository::seProposerCommeJury($idtuteurprof, $numConvention);
            Application::redirectFromParam("/conventions");
        } else {
            throw new ForbiddenException();
        }
    }

    public function voirSoutenance(Request $request)
    {
        $numConvention = $request->getRouteParam("numConvention");
        $soutenance = SoutenanceRepository::getSoutenanceByNumConvention($numConvention);
        return $this->render("soutenance/details", [
            "soutenance" => $soutenance
        ]);
    }

    public function noteSoutenance(Request $request)
    {

        $numConvention = $request->getRouteParam("numConvention");
        $soutenance = SoutenanceRepository::getSoutenanceByNumConvention($numConvention);
        $convention = $soutenance->getNumConvention();
        $convention = ConventionRepository::getById($convention);
        $etudiant = $convention['idutilisateur'];
        $etudiant = (new EtudiantRepository([]))->getByIdFull($etudiant);

        $form = new FormModel([
            'etudiant' => FormModel::string("Etudiant *")->Required()->default($etudiant->getPrenom() . " " . $etudiant->getNom()),
            'presenttuteur' => FormModel::select("Présence du tuteur d'entreprise *", ["presentiel" => "Présentiel", "visio" => "Visio", "pas" => "Pas du tout"])->Required()->default("presentiel"),
            'renduretard' => FormModel::select("Rendu du rapport *", ["alheure" => "Le 25 avant minuit", "retard" => "En retard"])->required()->default("alheure"),
            'noterapport' => FormModel::int("Note proposée pour le rapport (hors retard éventuel) *")->required(),
            'commentairerapport' => FormModel::string("remarque sur le rapport (optionnel)"),
            'noteoral' => FormModel::int("Note proposé pour l'oral *")->required(),
            'commentaireoral' => FormModel::string("Remarque sur l'oral (optionnel)"),
            'noterelation' => FormModel::int("Note proposée pour les relations interpersonnelles *")->required(),
            'langage' => FormModel::select("Démarches (langages de programmation) *", ["faible" => "Faible: Exemple site web vitrine ou CMS", "moyen" => "Langage étudié à l'IUT", "fort" => "Necessité de se former à un nouveau langage / framework (préciser)"])->required(),
            'nouveau' => FormModel::string("Si nouveau(x) langage(s) précisez"),
            'difficulte' => FormModel::string("Démarche: au moins une difficulté technique majeure a été résolue: (donner le détail) *")->required(),
            'notedemarche' => FormModel::int("Note proposée pour la démarche")->required(),
            'noteresultat' => FormModel::int("Note proposée pour le résultat *")->required(),
            'commentaireresultat' => FormModel::string("Remarque sur le résultat (optionnel)"),
            'recherche' => FormModel::select("Recherche alternant 2024 *", ["1" => "A déjà recruté un BUT3 cette année", "2" => "A déjà proposé mais pas encore trouvé", "3" => "Maitre Apprentissage à recontacter", "4" => "Pas d'alternant cette année"])->required(),
            'recontact' => FormModel::string("En cas de recontact, rappelez l'email du MA"),
        ]);
        if ($request->getMethod() == "post") {
            if ($form->validate($request->getBody())) {
                $values = array_merge($form->getParsedBody(), [
                    'idsoutenance' => $soutenance->getIdSoutenance(),
                ]);
                (new NotesRepository())->create($values);
                header("Location: /voirSoutenance/$numConvention");
            }
        }

        return $this->render("soutenance/note", [
            "soutenance" => $soutenance, "form" => $form
        ]);
    }
}