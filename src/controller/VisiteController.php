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
use app\src\model\repository\SuperviseRepository;
use app\src\model\repository\VisiteRepository;
use app\src\model\Request;

class VisiteController extends AbstractController
{
    /**
     * @throws ServerErrorException
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function visite(Request $req): string
    {
        if ($req->getMethod() == "get") {
            if (!Auth::has_role(Roles::TutorTeacher, Roles::Tutor, Roles::Student, Roles::Manager, Roles::ManagerAlternance, Roles::ManagerStage)) throw new ForbiddenException();
        } else {
            if (!Auth::has_role(Roles::TutorTeacher)) throw new ForbiddenException();
        }

        $numConvention = $req->getRouteParam("id");
        if (!ConventionRepository::exist($numConvention)) throw new NotFoundException();

        $studentId = ConventionRepository::getStudentId($numConvention);
        if (Auth::has_role(Roles::Student) && Application::getUser()->id() !== $studentId) throw new ForbiddenException();

        $visite = VisiteRepository::getByStudentId($studentId);

        $commentaires = VisiteRepository::getCommentaires($numConvention, $studentId);

        if (!$visite && !Auth::has_role(Roles::TutorTeacher)) throw new NotFoundException();

        $supervise = SuperviseRepository::getByConvention($numConvention);
        if (!$supervise) throw new NotFoundException();
        if (Auth::has_role(Roles::Tutor) && Application::getUser()->id() !== $supervise->getIdTutor()) throw new ForbiddenException();
        if (Auth::has_role(Roles::TutorTeacher) && Application::getUser()->id() !== $supervise->getIdTeacher()) throw new ForbiddenException();

        if (Auth::has_role(Roles::TutorTeacher)) {
            $form = new FormModel([
                "start" => FormModel::date("Début de la visite")->withHour()->default($visite ? $visite->getDebutVisite()->format('Y-m-d H:i:s') : (new \DateTime())->format('Y-m-d H:i:s'))->required(),
                "end" => FormModel::date("Fin de la visite")->withHour()->default($visite ? $visite->getFinVisite()->format('Y-m-d H:i:s') : (new \DateTime())->format('Y-m-d H:i:s'))->required(),
            ]);

            if ($req->getMethod() == "post") {
                if ($form->validate($req->getBody())) {
                    $data = $form->getParsedBody();
                    if (!$visite)
                        VisiteRepository::createVisite($data["start"], $data["end"], $numConvention);
                    else
                        VisiteRepository::update($numConvention, $data["start"], $data["end"]);
                    header("Location: /visite/" . $numConvention);
                }
            }

            return $this->render("visite/visite", [
                'visite' => $visite,
                'form' => $form,
                'name' => EtudiantRepository::getFullNameByID($studentId),
                "addresse" => ConventionRepository::getAddress($numConvention),
                "commentaires" => $commentaires
            ]);
        }

        return $this->render("visite/visite", [
            'visite' => $visite,
            'name' => EtudiantRepository::getFullNameByID($studentId),
            "addresse" => ConventionRepository::getAddress($numConvention),
            "commentaires" => $commentaires
        ]);
    }

}