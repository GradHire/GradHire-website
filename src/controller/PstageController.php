<?php

namespace app\src\controller;

use app\src\core\exception\ForbiddenException;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;
use app\src\model\Import;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\NotificationRepository;
use app\src\model\repository\ServiceAccueilRepository;
use app\src\model\repository\SignataireRepository;
use app\src\model\repository\SimulationPstageRepository;
use app\src\model\repository\StaffRepository;
use app\src\model\repository\TuteurEntrepriseRepository;
use app\src\model\Request;
use app\src\view\components\ui\Notification;


class PstageController extends AbstractController
{

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function importercsv(Request $request): string
    {
        if (Auth::has_role(Roles::Staff, Roles::Manager)) {
            $form = new FormModel([
                "type" => FormModel::select("Type", ["studea" => "Studea", "pstage" => "Pstage", "scodoc" => "Scodoc"])->required()->default("pstage")->sm(),
                "file" => FormModel::file("CSV")->required()->accept([".csv"])->sm()
            ]);
            $form->useFile();
            if ($request->getMethod() === 'post') {
                if ($form->validate($request->getBody())) {
                    $this->Import($form, $form->getParsedBody()['type']);
                    NotificationRepository::createNotification(Auth::get_user()->id(), "Importation CSV réussi", "/conventions");
                }
            }
            return $this->render('Import', [
                'form' => $form
            ]);
        } else throw new ForbiddenException();
    }

    /**
     * @throws ServerErrorException
     */
    private function Import(FormModel $form, string $type): void
    {
        if ($type == "pstage")
            $path = "Import/";
        else if ($type == "studea")
            $path = "ImportStudea/";
        else
            $path = "ImportScodoc/";
        if (!$form->getFile("file")->save($path, "file")) {
            $form->setError("Impossible de télécharger tous les fichiers");
            return;
        }
        $path = fopen($path . "file.csv", "r");
        $i = 0;
        $importer = new Import();
        while (($data = fgetcsv($path, 100000, ";")) !== FALSE) {

            if ($i == 0) {
                $i++;
                continue;
            }
            if ($type == "pstage")
                $importer->importerligne($data);
            else if ($type == "studea")
                $importer->importerligneStudea($data);
            else
                $importer->importerligneScodoc($data);
        }
        Notification::createNotification("Importation réussi");
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function explicationSimu(): string
    {
        if (Auth::has_role(Roles::Student)) return $this->render('simulateurP/General', ['vueChemin' => "explicationSimu.php"]);
        else throw new ForbiddenException();
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function simulateur(Request $request): string
    {
        if (Auth::has_role(Roles::Student)) {
            $id = Application::getUser()->getId();
            $etudiant = (new EtudiantRepository([]))->getByIdFull($id);
            $formData = $_SESSION['simulateurEtu'] ?? [];
            $form = new FormModel([
                "numEtudiant" => FormModel::string("Numéro étudiant *")->required()->min(8)->max(8)->default($formData['numEtudiant'] ?? $etudiant->getNumEtudiant()),
                "nom" => FormModel::string("Nom *")->required()->default($formData['nom'] ?? $etudiant->getNom()),
                "prenom" => FormModel::string("Prénom *")->required()->default($formData['prenom'] ?? $etudiant->getPrenom()),
                "adresse" => FormModel::string("Adresse *")->required()->default($formData['adresse'] ?? $etudiant->getAdresse()),
                "codePostal" => FormModel::string("Code postal *")->required()->length(5)->default($formData['codePostal'] ?? $etudiant->getCodePostal()),
                "ville" => FormModel::string("Ville *")->required()->default($formData['ville'] ?? $etudiant->getNomVille()),
                "telephone" => FormModel::phone("Téléphone *")->default($formData['telephone'] ?? $etudiant->getNumtelephone()),
                "emailPerso" => FormModel::email("Email perso *")->required()->default($formData['emailPerso'] ?? $etudiant->getEmailPerso()),
                "emailUniv" => FormModel::email("Email universitaire *")->required()->default($formData['emailUniv'] ?? $etudiant->getEmail()),
                "CPAM" => FormModel::string("CPAM et Adresse postal *")->required()->default($formData['CPAM'] ?? ""),
                "anneeUni" => FormModel::select("Année universitaire *", ["2023-2024" => "2023-2024", "2024-2025" => "2024-2025", "2025-2026" => "2025-2026"])->required()->default($formData['anneeUni'] ?? null),
                "nbHeure" => FormModel::int("Nombre d'heure *")->required()->default($formData['nbHeure'] ?? 1)->min(1)
            ]);

            if ($request->getMethod() === 'post') {
                if ($form->validate($request->getBody())) {
                    $_SESSION['simulateurEtu'] = $form->getParsedBody();
                    (new EtudiantRepository([]))->updateEtu($form->getParsedBody()['numEtudiant'], $form->getParsedBody()['nom'], $form->getParsedBody()['prenom'], $form->getParsedBody()['telephone'], $form->getParsedBody()['emailPerso'], $form->getParsedBody()['emailUniv'], $form->getParsedBody()['adresse'], $form->getParsedBody()['codePostal'], $form->getParsedBody()['ville'], "France", "");
                    return $this->render('simulateurP/General', ['vueChemin' => "preview.php", 'form' => $form, 'array' => $_SESSION['simulateurEtu']]);
                }
            }
            return $this->render('simulateurP/General', ['vueChemin' => "simulateur.php", 'form' => $form, "nom" => "Etudiant"]);
        } else throw new ForbiddenException();
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function previewOffre(): string
    {
        if (Auth::has_role(Roles::Student) && isset($_SESSION["simulateurEtu"])) {
            $id = $_GET['idEntreprise'];
            $_SESSION["idEntreprise"] = $id;
            $entreprise = (new EntrepriseRepository([]))->getByIdFull($id);
            $array = [
                "Nom Entreprise" => $entreprise->getNom(),
                "Type d'établissement" => $entreprise->getTypestructure(),
                "Effectif" => $entreprise->getEffectif(),
                "Siret" => $entreprise->getSiret(),
                "Adresse" => $entreprise->getAdresse(),
                "Code Postal" => $entreprise->getCodePostal(),
                "Ville" => $entreprise->getVille(),
                "Pays" => $entreprise->getPays(),
                "Code Naf" => $entreprise->getCodenaf()
            ];
            return $this->render('simulateurP/General', ['id' => $id, 'vueChemin' => "preview.php", 'array' => $array]);
        } else throw new ForbiddenException();
    }

    public function listEntreprise(): string
    {
        return $this->render('simulateurP/General', ['vueChemin' => "listEntreprise.php"]);
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function creerEntreprise(Request $request): string
    {
        if (Auth::has_role(Roles::Student) && isset($_SESSION["simulateurEtu"])) {
            $form = new FormModel([
                "nomEnt" => FormModel::string("Nom de l'entreprise *")->required(),
                "effectif" => FormModel::select("Effectif *", ["0" => "0", "1 à 9" => "1 à 9", "10 à 49" => "10 à 49", "50 à 199" => "50 à 199", "200 à 999" => "200 à 999", "1000 et +" => "1000 et +"])->required()->default("0"),
                "siret" => FormModel::string("Numéro Siret *")->required()->length(14),
                "type" => FormModel::select("Type d'entreprise *", ["Administration" => "Administration", "Associations" => "Associations", "Entreprise d'insertion" => "Entreprise d'insertion", "Entreprise privée" => "Entreprise privée", "Entreprise public/SEM" => "Entreprise public/SEM", "Mutuelle/Coopérative/CE" => "Mutuelle/Coopérative/CE", "NON CONNU" => "NON CONNU", "Société civile" => "Société civile", "Société étrangère" => "Société étrangère", "Sté Cdite Actions" => "Sté Cdite Actions", "Association" => "Association", "Entreprise public / SEM" => "Entreprise public / SEM", "Mutuelle Coopérative" => "Mutuelle Coopérative", "ONG" => "ONG", "EPIC" => "EPIC", "Syndicat mixte" => "Syndicat mixte"])->required(),
                "codeNaf" => FormModel::string("Code NAF *")->required()->length(5),
                "activite" => FormModel::string("Activité Principale")->default(""),
                "voie" => FormModel::string("Adresse Voie*")->required(),
                "residence" => FormModel::string("Bâtiment / Résidence / Z.I.")->default(""),
                "cedex" => FormModel::string("Cedex")->default(""),
                "codePostal" => FormModel::string("Code postal *")->required()->length(5),
                "ville" => FormModel::string("Ville *")->required(),
                "pays" => FormModel::select("Pays *", ["France" => "France", "Allemagne" => "Allemagne", "Angleterre" => "Angleterre", "Espagne" => "Espagne", "Italie" => "Italie", "Portugal" => "Portugal", "Suisse" => "Suisse", "Autre" => "Autre"])->required(),
                "email" => FormModel::email("Email")->default(""),
                "web" => FormModel::string("Site web")->default(""),
                "tel" => FormModel::phone("Téléphone *")->required(),
                "fax" => FormModel::string("Fax")->default("")
            ]);
            if ($request->getMethod() === 'post') {
                $form->setError("Impossible de créer l'entreprise");
                if ($form->validate($request->getBody())) {
                    $formData = $form->getParsedBody();
                    $ent = new EntrepriseRepository([]);
                    $ent->create($formData['nomEnt'], $formData['email'], $formData['tel'], "", $formData['type'], $formData['effectif'], $formData['codeNaf'], $formData['fax'], $formData['web'], $formData['voie'], $formData['cedex'], $formData['residence'], $formData['codePostal'], $formData['pays'], $formData['ville'], $formData['siret']);
                    return $this->render('simulateurP/General', ['vueChemin' => "listEntreprise.php"]);
                }
            }
            return $this->render('simulateurP/General', ['form' => $form, 'vueChemin' => "creer.php", 'nom' => "Créer une entreprise"]);
        } else throw new ForbiddenException();
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function simulateurServiceAccueil(Request $request): string
    {
        if (Auth::has_role(Roles::Student) && isset($_SESSION["simulateurEtu"]) && isset($_SESSION["idEntreprise"])) {
            $serviceaccueil = (new ServiceAccueilRepository())->getFullByEntreprise($_SESSION["idEntreprise"]);
            $serviceaccueil["Non renseigné"] = "Non renseigné";
            $form = new FormModel([
                "accueil" => FormModel::select("Accueil", $serviceaccueil)->required()->default("Non renseigné")->id("accueil"),
            ]);
            if ($request->getMethod() === 'post') {
                if ($form->validate($request->getBody())) {
                    $formData = $form->getParsedBody();
                    $_SESSION['accueil'] = $formData['accueil'];
                    $accueil = (new ServiceAccueilRepository())->getFullByEntrepriseNom($_SESSION['idEntreprise'], $_SESSION['accueil']);
                    $array = [
                        "Nom Accueil" => $accueil->getNomService(),
                        "Voie" => $accueil->getAdresse(),
                        "Code Postal" => $accueil->getCodePostal(),
                        "Commune" => $accueil->getCommune(),
                        "Pays" => $accueil->getPays()
                    ];
                    return $this->render('simulateurP/General', ['vueChemin' => 'preview.php', 'array' => $array]);
                }
            }
            return $this->render('simulateurP/General', ['form' => $form, 'vueChemin' => 'simulateur.php', 'nom' => "Service"]);
        } else throw new ForbiddenException();
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function creerService(Request $request): string
    {
        if (Auth::has_role(Roles::Student) && isset($_SESSION["simulateurEtu"]) && isset($_SESSION["idEntreprise"])) {
            $entreprise = new EntrepriseRepository([]);
            $entreprise = $entreprise->getByIdFull($_SESSION["idEntreprise"]);
            $cp = $entreprise->getCodePostal();
            $ville = $entreprise->getVille();
            $adresse = $entreprise->getAdresse();
            $pays = $entreprise->getPays();
            $form = new FormModel([
                "nomService" => FormModel::string("Nom du service")->required(),
                "memeAdresse" => FormModel::radio("Adresse identique à l'entreprise", ["Oui" => "Oui", "Non" => "Non"])->required()->default("Oui")->id("memeAdresse"),
                "voie" => FormModel::string("Adresse Voie")->id("voie")->default($adresse),
                "residence" => FormModel::string("Bâtiment / Résidence / Z.I.")->default(""),
                "cp" => FormModel::string("Code postal")->length(5)->id("cp")->default($cp),
                "ville" => FormModel::string("Ville")->id("ville")->default($ville),
                "pays" => FormModel::select("Pays", ["France" => "France", "Allemagne" => "Allemagne", "Angleterre" => "Angleterre", "Espagne" => "Espagne", "Italie" => "Italie", "Portugal" => "Portugal", "Suisse" => "Suisse", "Autre" => "Autre"])->id("pays")->default($pays)
            ]);
            if ($request->getMethod() === 'post') {
                if ($form->validate($request->getBody())) {
                    $formData = $form->getParsedBody();
                    $service = new ServiceAccueilRepository();
                    $service->create($formData['nomService'], $_SESSION["idEntreprise"], $formData['voie'], $formData['residence'], $formData['cp'], $formData['ville'], $formData['pays']);
                    $serviceaccueil = (new ServiceAccueilRepository())->getFullByEntreprise($_SESSION["idEntreprise"]);
                    $serviceaccueil["Non renseigné"] = "Non renseigné";
                    $form = new FormModel([
                        "accueil" => FormModel::select("Accueil", $serviceaccueil)->required()->default("Non renseigné"),
                    ]);
                    Application::$app->response->redirect("/simulateurServiceAccueil");
                }
            }
            return $this->render('simulateurP/General', ['form' => $form, 'vueChemin' => 'creer.php', "nom" => "Créer un service d'accueil"]);
        } else throw new ForbiddenException();
    }


    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function simulateurTuteur(): string
    {
        if (Auth::has_role(Roles::Student) && isset($_SESSION["simulateurEtu"]) && isset($_SESSION["idEntreprise"]) && isset($_SESSION["accueil"])) {
            $tut = new TuteurEntrepriseRepository([]);
            $tut = $tut->getFullByEntreprise($_SESSION["idEntreprise"]);
            return $this->render('simulateurP/General', ['listTuteur' => $tut, 'vueChemin' => 'listTuteur.php']);
        } else throw new ForbiddenException();
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function creerTuteur(Request $request): string
    {
        if (Auth::has_role(Roles::Student) && isset($_SESSION["simulateurEtu"]) && isset($_SESSION["idEntreprise"]) && isset($_SESSION["accueil"])) {
            $form = new FormModel([
                "nom" => FormModel::string("Nom")->required(),
                "prenom" => FormModel::string("Prénom")->required(),
                "fonction" => FormModel::string("Fonction")->required(),
                "tel" => FormModel::phone("Téléphone"),
                "email" => FormModel::email("Email")->required(),
            ]);
            if ($request->getMethod() == 'post') {
                if ($form->validate($request->getBody())) {
                    $formData = $form->getParsedBody();
                    $tut = new TuteurEntrepriseRepository([]);
                    $tut->create($formData['nom'], $formData['prenom'], $formData['fonction'], $formData['tel'], $formData['email'], $_SESSION["idEntreprise"]);
                    $tut = new TuteurEntrepriseRepository([]);
                    $tut = $tut->getFullByEntreprise($_SESSION["idEntreprise"]);
                    Application::$app->response->redirect("/simulateurTuteur");
                    return $this->render('simulateurP/General', ['listTuteur' => $tut, 'vueChemin' => 'listTuteur.php']);
                }
            }
            return $this->render('simulateurP/General', ['form' => $form, 'vueChemin' => 'creer.php', "nom" => "Créer un tuteur"]);
        } else throw new ForbiddenException();
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function simulateurCandidature(Request $request): string
    {
        if (isset($_GET['idTuteur'])) {
            $_SESSION['idTuteur'] = $_GET['idTuteur'];
        }
        if (Auth::has_role(Roles::Student) && isset($_SESSION["simulateurEtu"]) && isset($_SESSION["idEntreprise"]) && isset($_SESSION["accueil"]) && isset($_SESSION["idTuteur"])) {

            $formData = $_SESSION['simulateurCandidature'] ?? [];

            $form = new FormModel([
                "typeStage" => FormModel::select("Type de stage", ["StageO" => "Stage Obligatoire", "StageC" => "Stage Conseillé"])->required()->default($formData["typeStage"] ?? "StageO"),
                "Thématique" => FormModel::select("Thématique", ["Gestion" => "Gestion", "Reseaux" => "Reseaux", "Securite" => "Securite", "BD" => "Base de Donnée", "DevWeb" => "Dévelopement web", "DevApp" => "Dévelopement d'application"])->required()->default($formData["Thématique"] ?? ""),
                "Sujet" => FormModel::string("Sujet")->required()->default($formData["Sujet"] ?? ""),
                "fonction" => FormModel::string("Fonctions et taches")->required()->default($formData["fonction"] ?? ""),
                "competence" => FormModel::string("Compétences à acquérir")->required()->default($formData["competence"] ?? ""),
                "dateDebut" => FormModel::date("Date de début")->required()->default($formData["dateDebut"] ?? ""),
                "dateFin" => FormModel::date("Date de fin")->required()->default($formData["dateFin"] ?? ""),
                "interruption" => FormModel::radio("Interruption", ["Oui" => "Oui", "Non" => "Non"])->required()->default($formData["interruption"] ?? "Non")->id("interruption"),
                "dateDebutInterruption" => FormModel::date("Date de début de l'interruption")->default($formData["dateDebutInterruption"] ?? ""),
                "dateFinInterruption" => FormModel::date("Date de fin de l'interruption")->default($formData["dateFinInterruption"] ?? ""),
                "duree" => FormModel::int("Durée effective du stage en heure")->required()->default($formData["duree"] ?? 1)->min(1),
                "nbJour" => FormModel::int("Nombre de jours par semaine")->required()->default($formData["nbJour"] ?? 5)->min(1)->max(5),
                "nbHeure" => FormModel::double("Nombre d'heure par semaine (en heure)")->required()->default($formData["nbHeure"] ?? 1.00)->min(1.00),
                "nbjourConge" => FormModel::string("Nombre de jour de congé")->default($formData["nbjourConge"] ?? ""),
                "commentairetravail" => FormModel::string("Commentaire sur le travail")->required()->default($formData["commentairetravail"] ?? ""),
                "gratification" => FormModel::radio("Gratification", ["Oui" => "Oui", "Non" => "Non"])->required()->default($formData["gratification"] ?? "Non")->id("gratification"),
                "montant" => FormModel::double("Montant")->default($formData["montant"] ?? 4.05),
                "heureoumois" => FormModel::radio("", ["Heure" => "Heure", "Mois" => "Mois"])->default($formData["heureoumois"] ?? "Heure"),
                "modalite" => FormModel::string("Modalité de versement")->default($formData["modalite"] ?? ""),
                "commenttrouve" => FormModel::string("Comment avez-vous trouvé ce stage ?")->required()->default($formData["commenttrouve"] ?? ""),
                "confconvention" => FormModel::radio("Confidentialité du sujet du stage", ["Oui" => "Oui", "Non" => "Non"])->required()->default($formData["confconvention"] ?? "Non"),
                "modalsuivi" => FormModel::string("Modalité de suivi du stage")->default($formData["modalsuivi"] ?? ""),
                "avantage" => FormModel::string("Avantages nature")->default($formData["avantage"] ?? "")
            ]);
            if ($request->getMethod() == 'post') {
                $form->setError("Impossible de créer la candidature");
                if ($form->validate($request->getBody())) {
                    $_SESSION['simulateurCandidature'] = $form->getParsedBody();
                    return $this->render('simulateurP/General', ['vueChemin' => 'preview.php', 'array' => $_SESSION['simulateurCandidature']]);
                }
            }
            return $this->render('simulateurP/General', ['form' => $form, 'vueChemin' => 'simulateurCandidature.php']);
        } else throw new ForbiddenException();
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function simulateurProfReferent(Request $request): string
    {
        if (Auth::has_role(Roles::Student) && isset($_SESSION["simulateurEtu"]) && isset($_SESSION["idEntreprise"]) && isset($_SESSION["accueil"]) && isset($_SESSION["idTuteur"]) && isset($_SESSION["simulateurCandidature"])) {
            $listProf = new StaffRepository([]);
            $listProf = $listProf->getAll();
            $form = new FormModel([
                "nom" => FormModel::string("Nom du professeur référent")->required(),
                "prenom" => FormModel::string("Prénom du professeur référent")->required()
            ]);
            if ($request->getMethod() === "post") {
                $form->setError("Impossible de trouver le professeur référent");
                if ($form->validate($request->getBody())) {
                    return $this->render('simulateurP/General', ["listProf" => $listProf, "vueChemin" => "listProf.php"]);
                }
            }
            return $this->render('simulateurP/General', ["form" => $form, "vueChemin" => "listProf.php", "listProf" => $listProf]);
        } else throw new ForbiddenException();
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function simulateurSignataire(Request $request): string
    {
        if (Auth::has_role(Roles::Student) && isset($_SESSION["simulateurEtu"]) && isset($_SESSION["idEntreprise"]) && isset($_SESSION["accueil"]) && isset($_SESSION["idTuteur"]) && isset($_SESSION["simulateurCandidature"])) {
            $id = $_GET['idProfRef'] ?? null;
            if ($id != null) {
                $_SESSION['idProfRef'] = $id;
            }
            $signataire = (new SignataireRepository())->getFullByEntreprise($_SESSION["idEntreprise"]);
            $signataire["Non renseigné"] = "Non renseigné";
            $form = new FormModel([
                "signataire" => FormModel::select("Signataire", $signataire)->required()->default("Non renseigné")->id("signataire"),
            ]);
            if ($request->getMethod() === 'post') {
                if ($form->validate($request->getBody())) {
                    $formData = $form->getParsedBody();
                    $_SESSION['signataire'] = $formData['signataire'];
                    $signataire = (new SignataireRepository())->getFullByEntrepriseNom($_SESSION['signataire'], $_SESSION["idEntreprise"]);
                    $array = [
                        "Nom Signataire" => $signataire->getNomSignataire(),
                        "Prénom Signataire" => $signataire->getPrenomSignataire(),
                        "Fonction Signataire" => $signataire->getFonctionSignataire(),
                        "Mail Signataire" => $signataire->getMailSignataire()
                    ];
                    return $this->render('simulateurP/General', ['vueChemin' => 'preview.php', 'array' => $array]);
                }
            }
            return $this->render('simulateurP/General', ["form" => $form, "vueChemin" => "simulateur.php", "nom" => "Signataire"]);
        } else throw new ForbiddenException();
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function creerSignataire(Request $request): string
    {
        if (Auth::has_role(Roles::Student) && isset($_SESSION["simulateurEtu"]) && isset($_SESSION["idEntreprise"]) && isset($_SESSION["accueil"]) && isset($_SESSION["idTuteur"]) && isset($_SESSION["simulateurCandidature"])) {
            $form = new FormModel([
                "nom" => FormModel::string("Nom du signataire")->required(),
                "prenom" => FormModel::string("Prénom du signataire")->required(),
                "fonction" => FormModel::string("Fonction du signataire")->required(),
                "mail" => FormModel::string("Mail du signataire")->required(),
            ]);
            if ($request->getMethod() === 'post') {
                $form->setError("Impossible de créer le signataire");
                if ($form->validate($request->getBody())) {
                    $formData = $form->getParsedBody();
                    (new SignataireRepository())->create($formData['nom'], $formData['prenom'], $formData['fonction'], $formData['mail'], $_SESSION["idEntreprise"]);
                    $signataire = (new SignataireRepository())->getFullByEntreprise($_SESSION["idEntreprise"]);
                    $signataire["Non renseigné"] = "Non renseigné";
                    $form = new FormModel([
                        "signataire" => FormModel::select("Signataire", $signataire)->required()->default("Non renseigné")->id("signataire"),
                    ]);
                    Application::$app->response->redirect("/simulateurSignataire");
                }
            }
            return $this->render('simulateurP/General', ["form" => $form, "vueChemin" => "creer.php", "nom" => "Créer un signataire"]);
        } else throw new ForbiddenException();
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function visuRecapConv(): string
    {
        if (Auth::has_role(Roles::Student) && isset($_SESSION["simulateurEtu"]) && isset($_SESSION["idEntreprise"]) && isset($_SESSION["accueil"]) && isset($_SESSION["idTuteur"]) && isset($_SESSION["simulateurCandidature"]) && isset($_SESSION["signataire"])) {
            return $this->render('simulateurP/General', ['vueChemin' => 'visuRecapConv.php']);
        } else throw new ForbiddenException();
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function validersimulation(): string
    {
        if (Auth::has_role(Roles::Student)) {
            NotificationRepository::createNotification(Auth::get_user()->id(), "Vous avez finalisez votre simulation de convention", "/gererSimulPstage");
            return $this->render('simulateurP/validersimulation');
        } else throw new ForbiddenException();
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function gererSimulPstage(): string
    {
        if (Auth::has_role(Roles::Staff, Roles::Manager, Roles::Student)) {
            return $this->render('pstageConv/gererSimulPstage');
        } else throw new ForbiddenException();
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function gererSimulPstagevalide(Request $request): string
    {
        if (Auth::has_role(Roles::Staff, Roles::Manager)) {
            $id = $request->getRouteParams()['id'] ?? null;
            if ($id != null) {
                $simul = (new SimulationPstageRepository());
                $idetudiant = SimulationPstageRepository::getStudentId($id);
                $simul->updatevalide($id);
                NotificationRepository::createNotification(Auth::get_user()->id(), "Vous avez validé une simulation de convention", "/gererSimulPstage");
                NotificationRepository::createNotification($idetudiant, "Votre simulation de convention a été validé", "/gererSimulPstage");
                Application::$app->response->redirect("/gererSimulPstage");
            }
            return $this->render('pstageConv/gererSimulPstage');
        } else throw new ForbiddenException();
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function gererSimulPstagerefuse(Request $request): string
    {
        if (Auth::has_role(Roles::Staff, Roles::Manager)) {
            $id = $request->getRouteParams()['id'] ?? null;
            if ($id != null) {
                $form = new FormModel([
                    "motif" => FormModel::string("Motif du refus")->required()
                ]);
                if ($request->getMethod() === 'post') {
                    if ($form->validate($request->getBody())) {
                        $formData = $form->getParsedBody();
                        $simul = (new SimulationPstageRepository([]));
                        $simul->updaterefuse($id);
                        $simul->updateMotif($id, $formData['motif']);
                        $idetudiant = SimulationPstageRepository::getStudentId($id);
                        NotificationRepository::createNotification(Auth::get_user()->id(), "Vous avez refusé une simulation de convention avec pour motif ". $formData['motif'], "/gererSimulPstage");
                        NotificationRepository::createNotification($idetudiant, "Votre simulation de convention a été refusé avec pour motif ". $formData['motif'] , "/gererSimulPstage");
                        Application::$app->response->redirect("/gererSimulPstage");
                    }
                }
                return $this->render('pstageConv/refusepage', ['form' => $form]);
            }
            return $this->render('pstageConv/gererSimulPstage');
        } else throw new ForbiddenException();
    }

    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function motifRefus(Request $request): string
    {
        if (Auth::has_role(Roles::Staff, Roles::Manager, Roles::Student)) {
            $id = $request->getRouteParams()['id'] ?? null;
            if ($id != null) {
                $simul = (new SimulationPstageRepository([]));
                $motif = $simul->getMotifById($id);
                return $this->render('pstageConv/motifRefus', ['motif' => $motif]);
            }
            return $this->render('pstageConv/gererSimulPstage');
        } else throw new ForbiddenException();
    }
}
