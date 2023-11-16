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
use app\src\model\repository\LdapRepository;
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
    public function validateConventionPedagogiquement(Request $request): string {
        $id = $request->getRouteParams()["id"];
        (new ConventionRepository())->getById($id)->validerPedagogiquement($id);
        Application::redirectFromParam("/conventions");
    }

    /**
     * @throws ServerErrorException
     */
    public function unvalidateConventionPedagogiquement(Request $request): string {
        $id = $request->getRouteParams()["id"];
        (new ConventionRepository())->getById($id)->unvalidatePedagogiquement($id);
        Application::redirectFromParam("/conventions");
    }

    /**
     * @throws ServerErrorException
     */
    public function unvalidateConvention(Request $request): string {
        $id = $request->getRouteParams()["id"];
        (new ConventionRepository())->getById($id)->unvalidate($id);
        Application::redirectFromParam("/conventions");
    }

    /**
     * @throws ServerErrorException
     */
    public function validateConvention(Request $request): string {
        $id = $request->getRouteParams()["id"];
        (new ConventionRepository())->getById($id)->valider($id);
        Application::redirectFromParam("/conventions");
    }
}