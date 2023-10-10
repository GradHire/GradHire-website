<?php

namespace app\src\controller;

use app\src\core\db\Database;
use app\src\core\exception\NotFoundException;
use app\src\model\Application;
use app\src\model\Auth\Auth;
use app\src\model\dataObject\Offre;
use app\src\model\LoginForm;
use app\src\model\OffreForm;
use app\src\model\repository\MailRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\Request;

class MainController extends Controller
{
    public function __construct()
    {
        //$this->registerMiddleware(new AuthMiddleware());
    }

    public function user_test(Request $req)
    {
        if (session_status() !== PHP_SESSION_NONE)
            session_destroy();
        $user = Auth::load_user_by_id($req->getRouteParams()["id"]);
        \app\src\model\Auth\Auth::generate_token($user, "true");
        Application::$app->response->redirect('/');
    }

    public function contact(): string
    {
        return $this->render('contact');
    }

    public function profile(): string
    {
        return $this->render('profile');
    }

    public function dashboard(): string
    {
        return $this->render('dashboard/dashboard');
    }

    public function mailtest(): string
    {
        $to = ["hirchyts.daniil@gmail.com", "daniil.hirchyts@etu.umontpellier.fr"];
        $subject = "Test mail subject";
        $message = "This is a test message";

        $mailSent = MailRepository::send_mail($to, $subject, $message);

        $message = $mailSent ? "Mail sent successfully" : "Mail sending failed";

        return $this->render('test/mailtest', compact('message'));
    }

    public function creeroffre(Request $request): string
    {
        if ($request->getMethod() === 'get') {
            return $this->render('/offres/create');
        } else {
            $type = $_POST['radios'];
            $titre = $_POST['titre'];
            $theme = $_POST['theme'];
            $nbjour = $_POST['nbjour'];
            $nbheure = $_POST['nbheure'];
            if ($type == "alternance") $distanciel = $_POST['distanciel'];
            else $distanciel = null;
            $salaire = $_POST['salaire'];
            $unitesalaire = "heures";
            $statut = "en attente";
            $avantage = $_POST['avantage'];
            $dated = $_POST['dated'];
            $datef = $_POST['datef'];
            $duree = $_POST['duree'];
            $description = $_POST['description'];
            $idUtilisateur = 51122324;
            $idOffre = null;
            if ($duree == 1) {
                $anneeVisee = "2";
            } else {
                $anneeVisee = "3";
            }
            $idAnnee = date("Y");
            $offre = new Offre($idOffre, $duree, $theme, $titre, $nbjour, $nbheure, $salaire, $unitesalaire, $avantage, $dated, $datef, $statut, $anneeVisee, $idAnnee, $idUtilisateur, $description);
            OffreForm::creerOffre($offre, $distanciel);
            return $this->render('/offres/create');
        }
    }

    //TODO: deplacer la logique de filtrage dans le repository @Clement !

    public function offres(Request $request): string
    {
        $id = $request->getRouteParams()['id'] ?? null;
        $offre = (new OffresRepository())->recupererParId($id);
        if ($offre == null && $id != null) throw new NotFoundException();
        else if ($offre != null && $id != null) return $this->render('offres/detailOffre', ['offre' => $offre]);

        $filter = self::constructFilter();
        if (empty($search) && empty($filter)) {
            $offres = (new OffresRepository())->getAll();
            return $this->render('offres/listOffres', ['offres' => $offres]);
        }

        $offres = (new OffresRepository())->search($filter);
        if ($offres == null) return $this->render('offres/listOffres', ['offres' => $offres]);
        return $this->render('offres/listOffres', ['offres' => $offres]);
    }
    public function postuler(Request $request): string {
        $id = $request->getRouteParams()['id'] ?? null;
        $offre = (new OffresRepository())->recupererParId($id);
        if($request->getMethod()==='get'){
            return $this->render('candidature/postuler', ['offre' => $offre]);
        }
        else{
            $idoffre=$offre->getIdOffre();
            $iduser= Application::getUser()->id();
            $nomfichier= "uploads/".$idoffre."_".$iduser;
            if (!file_exists($nomfichier)) {
                mkdir($nomfichier, 0777, true);
                $target_dir = $nomfichier . "/"; // utiliser le nouveau dossier comme répertoire cible

                $target_file = $target_dir . basename($_FILES["cv"]["name"]);
                $target_file2 = $target_dir . basename($_FILES["ltm"]["name"]);

                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $imageFileType2 = strtolower(pathinfo($target_file2, PATHINFO_EXTENSION));
                if (isset($_POST["submit"])) {
                    if (move_uploaded_file($_FILES["cv"]["tmp_name"], $target_file)) {
                        echo "Le fichier " . htmlspecialchars(basename($_FILES["cv"]["name"])) . " a été téléversé.";
                    } else {
                        echo "Il y a eu une erreur lors du téléversement de votre fichier.";
                    }
                    if (move_uploaded_file($_FILES["ltm"]["tmp_name"], $target_file2)) {
                        echo "Le fichier " . htmlspecialchars(basename($_FILES["ltm"]["name"])) . " a été téléversé.";
                    } else {
                        echo "Il y a eu une erreur lors du téléversement de votre fichier.";
                    }
                }

                rename($target_file, $target_dir . "cv." . $imageFileType);
                rename($target_file2, $target_dir . "ltm." . $imageFileType2);

                $datecourante=date("Y-m-d");
                $sql = "INSERT INTO Candidature VALUES (NULL, '$datecourante', 'En attente', $idoffre, $iduser)";
                $requete = Database::get_conn()->prepare($sql);
                $requete->execute();
            }
            else {
                echo "Vous avez déja postulé pour cette offre";
            }
            $offres = (new OffresRepository())->getAll();
            return $this->render('offres/listOffres', ['offres' => $offres]);
        }

    }
    private static function constructFilter(): array
    {
        $filter = array();
//        if (Auth::has_role(["student"])) {
//            if (isset($_GET['statut'])) $filter['statut'] = $_GET['statut'];
//        } else {
//            $filter['statut'] = "staff";
//        }
        if (isset($_GET['sujet'])) {
            $filter['sujet'] = $_GET['sujet'];
        } else {
            $filter['sujet'] = "";
        }
        if (isset($_GET['thematique'])) {
            $filter['thematique'] = "";
            foreach ($_GET['thematique'] as $key => $value) {
                if ($filter['thematique'] == null) $filter['thematique'] = $value;
                else if ($filter['thematique'] != null) $filter['thematique'] .= ',' . $value;
            }
        }
        if (isset($_GET['anneeVisee'])) $filter['anneeVisee'] = $_GET['anneeVisee'];
        if (isset($_GET['duree'])) $filter['duree'] = $_GET['duree'];
        if (isset($_GET['alternance'])) $filter['alternance'] = $_GET['alternance'];
        if (isset($_GET['stage'])) $filter['stage'] = $_GET['stage'];
        if (isset($_GET['gratificationMin'])) {
            if ($_GET['gratificationMin'] == "") $filter['gratificationMin'] = null;
            else if ($_GET['gratificationMin'] < 4.05) $filter['gratificationMin'] = 4.05;
            else if ($_GET['gratificationMin'] > 15) $filter['gratificationMin'] = 15;
            else $filter['gratificationMin'] = $_GET['gratificationMin'];
        }
        if (isset($_GET['gratificationMax'])) {
            if ($_GET['gratificationMax'] == "") $filter['gratificationMax'] = null;
            else if ($_GET['gratificationMax'] < 4.05) $filter['gratificationMax'] = 4.05;
            else if ($_GET['gratificationMax'] > 15) $filter['gratificationMax'] = 15;
            else $filter['gratificationMax'] = $_GET['gratificationMax'];
        }
        return $filter;
    }
}
