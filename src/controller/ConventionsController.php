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
        ConventionRepository::validerPedagogiquement($id);
        $array = ConventionRepository::getConventionXOffreById($id);
        print_r($array);
        $mail = new MailRepository();
        $entreprise = EntrepriseRepository::getEmailById($array['idutilisateur']);
        $mail->send_Mail(
            [$entreprise['email']],
            "Convention validée pédagogiquement",
            "La convention n°" . $id . " de l'offre " . $array['sujet'] . " a été validée pédagogiquement par le Staff "
        );
        Application::redirectFromParam("/conventions");
    }

    /**
     * @throws ServerErrorException
     */
    public function unvalidateConventionPedagogiquement(Request $request): void {
        $id = $request->getRouteParams()["id"];
        ConventionRepository::unvalidatePedagogiquement($id);
        $array = ConventionRepository::getConventionXOffreById($id);
        $mail = new MailRepository();
        $entreprise = EntrepriseRepository::getEmailById($array['idutilisateur']);
        echo "Email : ";
        print_r($entreprise);
        $mail->send_Mail(
            [$entreprise['email']],
            "Convention non validée pédagogiquement",
            "La convention n°" . $id . " de l'offre " . $array['sujet'] . " n'a pas été validée pédagogiquement par le Staff "
        );
        Application::redirectFromParam("/conventions");
    }

    /**
     * @throws ServerErrorException
     */
    public function unvalidateConvention(Request $request): void {
        $id = $request->getRouteParams()["id"];
        ConventionRepository::unvalidate($id);
        $array = ConventionRepository::getConventionXOffreById($id);
        $mail = new MailRepository();
        $entreprise = (new EntrepriseRepository([]))->getByIdFull($array['idoffre']);
        $etudiant = EtudiantRepository::getEmailById($array["idutilisateur"]);
        $mail->send_Mail(
            [$etudiant['email']],
            "Convention non validée par l'enreprise " . $entreprise->getNomutilisateur(),
            "La convention n°" . $id . " de l'offre " . $array['sujet'] . " n'a pas été validée par l'entreprise " . $entreprise->getNomutilisateur()
        );
        Application::redirectFromParam("/conventions");
    }

    /**
     * @throws ServerErrorException
     */
    public function validateConvention(Request $request): void {
        $id = $request->getRouteParams()["id"];
        ConventionRepository::validate($id);
        $array = ConventionRepository::getConventionXOffreById($id);
        $mail = new MailRepository();
        $entreprise = (new EntrepriseRepository([]))->getByIdFull($array['idoffre']);
        $etudiant = EtudiantRepository::getEmailById($array["idutilisateur"]);
        $mail->send_Mail(
            [$etudiant['email']],
            "Convention validée par l'enreprise " . $entreprise->getNomutilisateur(),
            "La convention n°" . $id . " de l'offre " . $array['sujet'] . " a été validée par l'entreprise " . $entreprise->getNomutilisateur()
        );
        Application::redirectFromParam("/conventions");
    }

}