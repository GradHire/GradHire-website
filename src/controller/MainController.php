<?php

namespace app\src\controller;

use app\src\core\exception\NotFoundException;
use app\src\core\middlewares\AuthMiddleware;
use app\src\model\Application;
use app\src\model\Auth\LdapAuth;
use app\src\model\dataObject\Offre;
use app\src\model\LoginForm;
use app\src\model\OffreForm;
use app\src\model\repository\MailRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\Request;
use app\src\model\Response;
use app\src\model\User;

class MainController extends Controller
{
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware());
    }


    public function contact(): string
    {
        return $this->render('contact');
    }

    public function profile(): string
    {
        return $this->render('profile');
    }

    public function creeroffre(Request $request): string
    {
        if ($request->getMethod() === 'get') {
            return $this->render('creeroffre');
        } else {
            $type = $_POST['radios'];
            $titre = $_POST['titre'];
            $theme = $_POST['theme'];
            $nbjour = $_POST['nbjour'];
            $nbheure = $_POST['nbheure'];
            if ($type == "alternance") {
                $distanciel = $_POST['distanciel'];
            } else {
                $distanciel = null;
            }
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
            $offre = new Offre($idOffre, $duree, $theme, $titre, $nbjour, $nbheure, $salaire, $unitesalaire, $avantage, $dated, $datef, $statut, $anneeVisee, $idAnnee, $idUtilisateur, $description, $distanciel);
            OffreForm::creerOffre($offre);
            return $this->render('/contact');
        }
    }

    public function mailtest(): string
    {
        $to = ["hirchyts.daniil@gmail.com", "daniil.hirchyts@etu.umontpellier.fr"];
        $subject = "Test mail subject";
        $message = "This is a test message";

        $mailSent = MailRepository::send_mail($to, $subject, $message);

        $message = $mailSent ? "Mail sent successfully" : "Mail sending failed";

        return $this->render('offre/mailtest', compact('message'));
    }
    public function dashboard(): string
    {
        return $this->render('dashboard/dashboard');
    }

    public function offres(Request $request): string
    {
        $id = $request->getRouteParams()['id'] ?? null;
        $offre = (new OffresRepository())->recupererParId($id);
        if ($offre == null && $id != null) throw new NotFoundException();
        else if ($offre != null && $id != null) return $this->render('offres/detailOffre', ['offre' => $offre]);

        $search = $_GET['search'] ?? "";
        $filter = self::constructFilter();
        if (empty($search) && empty($filter)) {
            $offres = (new OffresRepository())->recuperer();
            return $this->render('offres/listOffres', ['offres' => $offres]);
        }
        $offres = (new OffresRepository())->search($search, $filter);
        if ($offres == null) {
            return $this->render('offres/listOffres', ['offres' => $offres]);
        }
        return $this->render('offres/listOffres', ['offres' => $offres]);
    }
    public function detailOffre(): string
    {
        $idoffre = $_GET['idoffre'] ?? "";
        $offre = (new OffresRepository())->recupererParId($idoffre);
        return $this->render('offres/detailOffre', ['offre' => $offre]);
    }
    private static function constructFilter():array {
        $filter = array();
        if (isset($_GET['thematique'])) {
            $filter['thematique'] = "";
            foreach ($_GET['thematique'] as $key => $value) {
                if ($filter['thematique'] == null) {
                    $filter['thematique'] = $value;
                } else if ($filter['thematique'] != null){
                    $filter['thematique'] .= ','. $value;
                }
            }
        }
        if (isset($_GET['anneeVisee'])) {
            $filter['anneeVisee'] = $_GET['anneeVisee'];
        }
        if (isset($_GET['duree'])) {
            $filter['duree'] = $_GET['duree'];
        }
        if (isset($_GET['alternance'])) {
            $filter['alternance'] = $_GET['alternance'];
        }
        if (isset($_GET['stage'])) {
            $filter['stage'] = $_GET['stage'];
        }
        if (isset($_GET['gratificationMin'])) {
            if ($_GET['gratificationMin']==""){
                $filter['gratificationMin'] = null;
            }
            elseif ($_GET['gratificationMin'] < 4.05) {
                $filter['gratificationMin'] = 4.05;
            } else if ($_GET['gratificationMin'] > 15){
                $filter['gratificationMin'] = 15;
            } else {
                $filter['gratificationMin'] = $_GET['gratificationMin'];
            }
        }
        if (isset($_GET['gratificationMax'])) {
            if ($_GET['gratificationMax']==""){
                $filter['gratificationMax'] = null;
            }
            else if ($_GET['gratificationMax'] < 4.05) {
                $filter['gratificationMax'] = 4.05;
            } else if ($_GET['gratificationMax'] > 15){
                $filter['gratificationMax'] = 15;
            } else {
                $filter['gratificationMax'] = $_GET['gratificationMax'];
            }
        }
        return $filter;
    }
}
