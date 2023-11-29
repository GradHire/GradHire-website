<?php

namespace app\src\controller;

use app\src\controller\AbstractController;
use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\Form\FormModel;
use app\src\model\repository\ConventionRepository;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\LdapRepository;
use app\src\model\repository\MailRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\repository\ProRepository;
use app\src\model\repository\TuteurEntrepriseRepository;
use app\src\model\Request;
use app\src\model\Response;

class ConventionsController extends AbstractController {

    public function afficherListeConventions(Request $request): string {
        $conventions = (new ConventionRepository())->getAll();
        return $this->render('convention/listeConventions', [
            'conventions' => $conventions
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public function detailConvention(Request $request): string {
        $id = $request->getRouteParams()["id"];
        $convention = (new ConventionRepository())->getById($id);
        return $this->render('convention/detailConvention', [
            'convention' => $convention
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public function validateConventionPedagogiquement(Request $request): void {
        $id = $request->getRouteParams()["id"];
        $array = ConventionRepository::validerPedagogiquement($id);
        $mail = new MailRepository();
        $offre = (new OffresRepository())->getById($array["idoffre"]);
        $entreprise = (new EntrepriseRepository([]))->getByIdFull($offre->getIdUtilisateur());
        $mail->send_Mail(
            [$entreprise->getEmailutilisateur()],
            "Convention validée pédagogiquement",
            "La convention n°" . $id . " de l'offre " . $offre->getSujet() . " a été validée pédagogiquement par le Staff "
        );
        Application::redirectFromParam("/conventions");
    }

    /**
     * @throws ServerErrorException
     */
    public function unvalidateConventionPedagogiquement(Request $request): void {
        $id = $request->getRouteParams()["id"];
        $array = ConventionRepository::unvalidatePedagogiquement($id);
        $mail = new MailRepository();
        $offre = (new OffresRepository())->getById($array["idoffre"]);
        $entreprise = (new EntrepriseRepository([]))->getByIdFull($offre->getIdUtilisateur());
        $mail->send_Mail(
            [$entreprise->getEmailutilisateur()],
            "Convention non validée pédagogiquement",
            "La convention n°" . $id . " de l'offre " . $offre->getSujet() . " n'a pas été validée pédagogiquement par le Staff "
        );
        Application::redirectFromParam("/conventions");
    }

    /**
     * @throws ServerErrorException
     */
    public function unvalidateConvention(Request $request): void {
        $id = $request->getRouteParams()["id"];
        $array = ConventionRepository::unvalidate($id);
        $mail = new MailRepository();
        $offre = (new OffresRepository())->getById($array["idoffre"]);
        $entreprise = (new EntrepriseRepository([]))->getByIdFull($offre->getIdUtilisateur());
        $etudiant = (new EtudiantRepository([]))->getByIdFull($array["idutilisateur"]);
        $mail->send_Mail(
            [$etudiant->getEmailutilisateur()],
            "Convention non validée par l'enreprise " . $entreprise->getNomutilisateur(),
            "La convention n°" . $id . " de l'offre " . $offre->getSujet() . " n'a pas été validée par l'entreprise " . $entreprise->getNomutilisateur()
        );
        Application::redirectFromParam("/conventions");
    }

    /**
     * @throws ServerErrorException
     */
    public function validateConvention(Request $request): void {
        $id = $request->getRouteParams()["id"];
        $array = ConventionRepository::validate($id);
        $mail = new MailRepository();
        $offre = (new OffresRepository())->getById($array["idoffre"]);
        $entreprise = (new EntrepriseRepository([]))->getByIdFull($offre->getIdUtilisateur());
        $etudiant = (new EtudiantRepository([]))->getByIdFull($array["idutilisateur"]);
        $mail->send_Mail(
            [$etudiant->getEmailutilisateur()],
            "Convention validée par l'enreprise " . $entreprise->getNomutilisateur(),
            "La convention n°" . $id . " de l'offre " . $offre->getSujet() . " a été validée par l'entreprise " . $entreprise->getNomutilisateur()
        );
        Application::redirectFromParam("/conventions");
    }

}