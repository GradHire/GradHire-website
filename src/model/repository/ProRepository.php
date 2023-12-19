<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\Form\FormModel;
use DateTime;
use Exception;

class ProRepository extends UtilisateurRepository
{
    protected static string $id_attributes = "email";

    /**
     * @throws ServerErrorException
     */
    public static function login(string $email, string $password, bool $remember, FormModel $form): bool
    {
        try {
            $user = self::find_account($email, $password);
            if (is_null($user)) {
                $form->setError("Email ou mot de passe invalide");
                return false;
            }
            if ($user->archived()) {
                $form->setError("Ce compte à été archivé");
                return false;
            }
            Auth::generate_token($user, $remember);
            return true;
        } catch (Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    private
    static function find_account(string $email, string $password): ProRepository|null
    {
        $user = EntrepriseRepository::check_credentials($email, $password);
        if (!is_null($user)) return $user;
        return TuteurEntrepriseRepository::check_credentials($email, $password);
    }

    /**
     * @throws ServerErrorException
     */
    private
    static function check_credentials(string $email, string $password): static|null
    {
        try {
            $statement = Database::get_conn()->prepare("SELECT * FROM " . static::$view . " WHERE email=?");
            $statement->execute([$email]);
            $user = $statement->fetch();
            if (is_null($user) || $user === false) return null;
            $user = new static($user);
            if (!self::checkPassword($password, $user->attributes()["hash"])) return null;
            return $user;
        } catch
        (Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function checkForgetToken(string $token): array|null
    {
        try {
            $stmt = Database::get_conn()->prepare("SELECT * FROM modifiermdp WHERE token=?");
            $stmt->execute([$token]);
            return $stmt->fetch();
        } catch (Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function forgetPassword(string $email): void
    {
        try {
            $userId = UtilisateurRepository::getIdByEmail($email);
            if (!$userId) return;
            $sql = "SELECT * FROM modifiermdp WHERE idutilisateur=?";
            $stmt = Database::get_conn()->prepare($sql);
            $stmt->execute([$userId]);
            $token = $stmt->fetch();
            if (!$token) {
                self::createForgetToken($email, $userId);
            } else {
                $now = new DateTime();
                $tokenDate = new DateTime($token["datecreation"]);
                $diff = $now->diff($tokenDate);
                if ($diff->h >= 1 || $diff->days > 0)
                    self::createForgetToken($email, $userId);
            }
        } catch (Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    private static function createForgetToken(string $email, int $id): void
    {
        try {
            self::deleteForgetToken($id);
            $token = substr(bin2hex(random_bytes(20)), 0, 20);
            $stmt = Database::get_conn()->prepare("INSERT INTO modifiermdp (idutilisateur, token) values(?,?)");
            $stmt->execute([$id, $token]);
            MailRepository::send_mail([$email], "Changer mon mot de passe", "Voici le lien pour changer votre mot de passe: " . HOST . "/forgetPassword/" . $token . "<br>Ce lien est valide pendant 1 heure.");
        } catch (Exception) {
            throw new ServerErrorException();
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function deleteForgetToken(int $id): void
    {
        try {
            $delete = Database::get_conn()->prepare("DELETE FROM modifiermdp WHERE idutilisateur=?");
            $delete->execute([$id]);
        } catch (Exception) {
            throw new ServerErrorException();
        }
    }

    public static function changePassword(string $password, string $new, FormModel $form): bool
    {
        try {
            $id = Application::getUser()->id();
            $statement = Database::get_conn()->prepare("SELECT * FROM ComptePro WHERE idUtilisateur=?");
            $statement->execute([$id]);
            $user = $statement->fetch();
            if (is_null($user) || $user === false || !password_verify($password, $user["hash"])) {
                $form->setError("Mot de passe invalide");
                return false;
            }
            self::setNewPassword($new, $id);
            return true;
        } catch (Exception) {
            return false;
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function setNewPassword(string $password, int $id): void
    {
        try {
            $hash = self::hashPassword($password);
            $statement = Database::get_conn()->prepare("UPDATE ComptePro SET hash=? WHERE idUtilisateur=?");
            $statement->execute([$hash, $id]);
        } catch (Exception) {
            throw new ServerErrorException();
        }
    }

    protected static function hashPassword(string $password): string
    {
        return password_hash(hash_hmac("sha256", $password, HASH_MDP), PASSWORD_DEFAULT);
    }

    protected static function checkPassword(string $password, string $hash): bool
    {
        return password_verify(hash_hmac("sha256", $password, HASH_MDP), $hash);
    }
}