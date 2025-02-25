<?php

namespace app\src\controller;

use app\src\core\exception\ForbiddenException;
use app\src\core\exception\NotFoundException;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;
use app\src\model\repository\CompteRenduRepository;
use app\src\model\repository\ConventionRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\NotificationRepository;
use app\src\model\repository\SuperviseRepository;
use app\src\model\repository\VisiteRepository;
use app\src\model\Request;
use DateTime;

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
            if (!Auth::has_role(Roles::TutorTeacher, Roles::Tutor, Roles::Student, Roles::Manager, Roles::ManagerAlternance, Roles::ManagerStage)) throw new ForbiddenException("Vous n'avez pas le droit de modifier les visites");
        } else {
            if (!Auth::has_role(Roles::TutorTeacher, Roles::Tutor, Roles::Manager)) throw new ForbiddenException("Vous n'avez pas le droit de modifier les visites");
        }

        $numConvention = $req->getRouteParam("id");
        if (!ConventionRepository::exist($numConvention)) throw new NotFoundException("La convention n'existe pas");

        $studentId = ConventionRepository::getStudentId($numConvention);
        if (!$studentId) throw new NotFoundException("La convention n'est pas encore validée");
        if (Auth::has_role(Roles::Student) && Application::getUser()->id() !== $studentId) throw new ForbiddenException();

        $visite = VisiteRepository::getByStudentId($studentId);

        $commentaires = CompteRenduRepository::getCommentaires($numConvention, $studentId);

        if (!$visite && !Auth::has_role(Roles::TutorTeacher, Roles::Tutor, Roles::Manager)) throw new NotFoundException();

        $supervise = SuperviseRepository::getByConvention($numConvention);
        if (!$supervise) throw new NotFoundException();
        if (Auth::has_role(Roles::Tutor) && Application::getUser()->id() !== $supervise->getIdtuteurentreprise()) throw new ForbiddenException("vous n'êtes pas le tuteur de l'entreprise");

        if ((Auth::has_role(Roles::TutorTeacher, Roles::Tutor) && ConventionRepository::imOneOfTheTutor(Application::getUser()->id(), $numConvention)) || Auth::has_role(Roles::Manager, Roles::ManagerAlternance, Roles::ManagerStage)) {
            $form = new FormModel([
                "start" => FormModel::date("Début de la visite")->withHour()->default($visite ? $visite->getDebutVisite()->format('Y-m-d H:i:s') : (new DateTime())->format('Y-m-d H:i:s'))->required(),
                "end" => FormModel::date("Fin de la visite")->withHour()->default($visite ? $visite->getFinVisite()->format('Y-m-d H:i:s') : (new DateTime())->format('Y-m-d H:i:s'))->required(),
            ]);

            if ($req->getMethod() == "post") {
                if ($form->validate($req->getBody())) {
                    $data = $form->getParsedBody();
                    $convention = ConventionRepository::getByNumConvention($numConvention);
                    if (!$visite) {
                        VisiteRepository::createVisite($data["start"], $data["end"], $numConvention);
                        NotificationRepository::createNotification(Auth::get_user()->id(), "Vous avez créé une visite", "/visite/" . $numConvention);
                        NotificationRepository::createNotification($studentId, "Une visite a été créée pour votre convention", "/visite/" . $numConvention);
                        if (!Auth::has_role(Roles::Tutor))
                            NotificationRepository::createNotification($convention["idtuteurentreprise"], "Une visite a été créée pour votre convention", "/visite/" . $numConvention);
                        if (!Auth::has_role(Roles::TutorTeacher))
                            NotificationRepository::createNotification($convention["idtuteurprof"], "Une visite a été créée pour votre convention", "/visite/" . $numConvention);
                    } else {
                        VisiteRepository::update($numConvention, $data["start"], $data["end"]);
                        NotificationRepository::createNotification(Auth::get_user()->id(), "Vous avez modifié une visite", "/visite/" . $numConvention);
                        NotificationRepository::createNotification($studentId, "Une visite a été modifiée pour votre convention", "/visite/" . $numConvention);
                        if (!Auth::has_role(Roles::Tutor))
                            NotificationRepository::createNotification($convention["idtuteurentreprise"], "Une visite a été modifiée pour votre convention", "/visite/" . $numConvention);
                        if (!Auth::has_role(Roles::TutorTeacher))
                            NotificationRepository::createNotification($convention["idtuteurprof"], "Une visite a été modifiée pour votre convention", "/visite/" . $numConvention);
                    }
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