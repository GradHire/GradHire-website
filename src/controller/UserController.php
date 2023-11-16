<?php

namespace app\src\controller;

use app\src\core\exception\ForbiddenException;
use app\src\core\exception\NotFoundException;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\dataObject\Roles;
use app\src\model\Form\FormModel;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\OffresRepository;
use app\src\model\Request;

class UserController extends AbstractController
{
    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws ServerErrorException
     */
    public function profile(Request $req): string
    {
        $id = $req->getRouteParams()["id"] ?? null;
        if (!is_null($id)) {
            $user = Auth::load_user_by_id($id);
            if (is_null($user)) throw new NotFoundException();
        } else {
            $user = Application::getUser();
            if (is_null($user)) throw new ForbiddenException();
            $user->setId($user->attributes["idutilisateur"]);
        }
        return $this->render('/profile', [
            'user' => $user,
            'form' => null
        ]);
    }


    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws ServerErrorException
     */
    public function edit_profile(Request $request): string
    {
        if (Application::isGuest()) throw new ForbiddenException();
        $id = $request->getRouteParams()["id"] ?? null;
        if (!is_null($id))
            if (!Auth::has_role(Roles::Manager, Roles::Staff) && !(Application::getUser()->role() == Roles::Enterprise && Application::getUser()->id() == $id))
                throw new ForbiddenException();
        $user = is_null($id) ? Application::getUser() : Auth::load_user_by_id($id);
        if (is_null($user)) throw new NotFoundException();
        $user->setId($user->attributes["idutilisateur"]);
        $attr = [];
        switch ($user->role()) {
            case Roles::Enterprise:
                $attr = array_merge($attr, [
                    "name" => FormModel::string("Nom entreprise")->required()->default($user->attributes()["nom"]),
                    "email" => FormModel::email("Adresse mail")->required()->default($user->attributes()["email"]),
                    "phone" => FormModel::phone("Téléphone")->default($user->attributes()["numtelephone"]),
                ]);
                break;
            case Roles::Tutor:
                $attr = array_merge($attr, [
                    "name" => FormModel::string("Prénom")->required()->default($user->attributes()["nom"]),
                    "surname" => FormModel::string("Nom")->required()->default($user->attributes()["prenom"]),
                    "email" => FormModel::string("Adresse mail")->required()->default($user->attributes()["email"]),
                    "fonction" => FormModel::select("Fonction", [
                        "tuteur" => "Tuteur",
                        "responsable" => "Responsable"
                    ])->required()->default($user->attributes()["fonction"]),
                ]);
                break;
            case  Roles::Student:
                $attr = array_merge($attr, [
                    "email" => FormModel::email("Adresse mail perso")->default($user->attributes()["emailperso"]),
                    "tel" => FormModel::phone("Téléphone")->numeric()->default($user->attributes()["numtelephone"])->nullable(),
                    "date" => FormModel::date("Date de naissance")->default($user->attributes()["datenaissance"])->before(new \DateTime()),
                    "studentnum" => FormModel::string("Numéro Etudiant")->default($user->attributes()["numetudiant"]),
                ]);
                break;
            case Roles::Teacher:
            case Roles::Manager:
            case Roles::Staff:
                $attr = array_merge($attr, [
                    "role" => FormModel::select("Role", [
                        "responsable" => "Responsable",
                        "enseignant" => "Enseignant",
                        "secretariat" => "Secretariat"
                    ])->required()->default($user->attributes()["role"]),
                ]);
                break;
        }
        $attr = array_merge(
            ["picture" => FormModel::file("Photo de profile")->id("image")->image()],
            $attr,
            ["bio" => FormModel::string("Biographie")->default($user->attributes()["bio"])->max(200)]
        );
        $form = new FormModel($attr);
        $form->useFile();
        if ($request->getMethod() === 'post') {
            if ($form->validate($request->getBody())) {
                $picture = $form->getFile("picture");
                if (!is_null($picture)) $picture->save("pictures", $user->id());
                $user->update($form->getParsedBody());
                Application::redirectFromParam('/profile');
                return '';
            }

        }
        return $this->render('profile', [
            'form' => $form,
            'user' => $user
        ]);
    }

    /**
     * @throws NotFoundException
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public function entreprises(Request $request): string
    {
        $id = $request->getRouteParams()['id'] ?? null;
        $entreprise = null;
        if ($id != null) $entreprise = (new EntrepriseRepository([]))->getByIdFull($id);
        if ($entreprise == null && $id != null) {
            throw new NotFoundException();
        } else if ($entreprise != null && $id != null) {
            $offres = (new OffresRepository())->getOffresByIdEntreprise($id);
            return $this->render('entreprise/detailEntreprise', ['entreprise' => $entreprise, 'offres' => $offres]);
        }
        if (Auth::has_role(Roles::Manager, Roles::Staff, Roles::Enterprise, Roles::Student, Roles::Teacher)) {
            $entreprises = (new EntrepriseRepository([]))->getAll();
            return $this->render('entreprise/entreprise', ['entreprises' => $entreprises]);
        } else {
            throw new ForbiddenException();
        }
    }
}