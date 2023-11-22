<?php

namespace app\src\model;

use app\src\core\db\Database;
use app\src\core\exception\ForbiddenException;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Roles;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\repository\EtudiantRepository;
use app\src\model\repository\StaffRepository;
use app\src\model\repository\TuteurRepository;
use app\src\model\repository\UtilisateurRepository;


class Auth
{
    /**
     * @throws ForbiddenException
     * @throws ServerErrorException
     */
    public static function check_role(Roles ...$roles): void
    {
        if (Application::isGuest()) header("Location: /login?" . Application::getRedirect());
        if (!in_array(Application::getUser()->role(), $roles)) throw new ForbiddenException();
    }

    /**
     * @throws ServerErrorException
     */
    public static function has_role(Roles ...$roles): bool
    {
        return !Application::isGuest() && in_array(Application::getUser()->role(), $roles);
    }

    public static function generate_token(UtilisateurRepository $user, bool $remember): void
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        Application::setUser($user);
        $duration = $remember ? 604800 : 3600;
        setcookie("token", Token::generate(["id" => $user->id()], $duration), time() + $duration, "/");
    }

    /**
     * @throws ServerErrorException
     */
    public static function load_user_by_id(string $id): ?UtilisateurRepository
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * from getRole(?);");
            $statement->execute([$id]);
            $role = $statement->fetchColumn();
            if (!$role)
                return null;
            switch ($role) {
                case Roles::Tutor->value:
                    return TuteurRepository::find_by_id($id);
                case Roles::Student->value:
                    return EtudiantRepository::find_by_id($id);
                case Roles::Enterprise->value:
                    return EntrepriseRepository::find_by_id($id);
                case "staff":
                    return StaffRepository::find_by_id($id);
            }
        } catch (\Exception) {
            throw new ServerErrorException();
        }

        return null;
    }

    public
    static function logout(): void
    {
        unset($_COOKIE["token"]);
        setcookie('token', '', -1);
        session_destroy();
    }

    public
    static function get_user()
    {
        return Application::getUser();
    }
}