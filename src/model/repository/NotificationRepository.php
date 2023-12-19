<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Notifications;
use PDOException;

class NotificationRepository extends AbstractRepository
{

    private static string $nomTable = "notifications";
    private static NotificationRepository $notificationRepository;

    public static function getInstance(): NotificationRepository
    {
        if (!isset(self::$notificationRepository)) {
            self::$notificationRepository = new NotificationRepository([]);
        }
        return self::$notificationRepository;
    }

    /**
     * @throws ServerErrorException
     */
    public static function getNotificationLue(int $id)
    {
        $sql = Database::get_conn()->prepare("SELECT * FROM " . self::$nomTable . " WHERE idutilisateur = :id AND lu = true ORDER BY date ASC");
        $sql->execute(['id' => $id]);
        $dataObjects = [];
        foreach ($sql as $dataObjectFormatTableau) $dataObjects[] = self::getInstance()->construireDepuisTableau($dataObjectFormatTableau);
        return $dataObjects;
    }

    /**
     * @throws ServerErrorException
     */
    public static function createNotification(int $idutilisateur, string $message, string $url): void
    {
        try {
            $sql = Database::get_conn()->prepare("INSERT INTO " . self::$nomTable . " (idutilisateur, notification, date, url, lu) VALUES (:idutilisateur, :notification, :date, :url, :lu)");
            $sql->execute(['idutilisateur' => $idutilisateur, 'notification' => $message, 'date' => date('Y-m-d H:i:s', time()), 'url' => $url, 'lu' => 0]);
        } catch (PDOException $e) {
            throw new ServerErrorException("Erreur lors de la création de la notification :" . $e->getMessage());
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function deleteNotification(int $id): void
    {
        try {
            $sql = Database::get_conn()->prepare("DELETE FROM " . self::$nomTable . " WHERE idnotification =:id");
            $sql->execute(['id' => $id]);
        } catch (PDOException) {
            throw new ServerErrorException("Erreur lors de la suppression de la notification");
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function deleteAllNotificationLue(int $id): void
    {
        try {
            $sql = Database::get_conn()->prepare("DELETE FROM " . self::$nomTable . " WHERE idutilisateur =:id AND lu = true");
            $sql->execute(['id' => $id]);
        } catch (PDOException) {
            throw new ServerErrorException("Erreur lors de la suppression de la notification");
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function deleteAllNotificationNonLue(int $id): void
    {
        try {
            $sql = Database::get_conn()->prepare("DELETE FROM " . self::$nomTable . " WHERE idutilisateur =:id AND lu = false");
            $sql->execute(['id' => $id]);
        } catch (PDOException) {
            throw new ServerErrorException("Erreur lors de la suppression de la notification");
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function setLuToTrue(int $id): void
    {
        try {
            $sql = Database::get_conn()->prepare("UPDATE " . self::$nomTable . " SET lu = true WHERE idnotification =:id");
            $sql->execute(['id' => $id]);
        } catch (PDOException) {
            throw new ServerErrorException("Erreur lors de la modification de la notification");
        }
    }

    /**
     * @throws ServerErrorException
     */
    public static function loadNotification (int $id): ?Notifications
    {
        $sql = Database::get_conn()->prepare("SELECT * FROM " . self::$nomTable . " WHERE idnotification = :id");
        $sql->execute(['id' => $id]);
        $dataObjectFormatTableau = $sql->fetch();
        if ($dataObjectFormatTableau === false) return null;
        return self::getInstance()->construireDepuisTableau($dataObjectFormatTableau);
    }

    /**
     * @throws ServerErrorException
     */
    public static function getNotificationNonLue (int $id): ?array
    {
        $sql = Database::get_conn()->prepare("SELECT * FROM " . self::$nomTable . " WHERE idutilisateur = :id AND lu = false ORDER BY date ASC");
        $sql->execute(['id' => $id]);
        $dataObjects = [];
        foreach ($sql as $dataObjectFormatTableau) $dataObjects[] = self::getInstance()->construireDepuisTableau($dataObjectFormatTableau);
        return $dataObjects;
    }

    protected function getNomTable(): string
    {
        return self::$nomTable;
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Notifications
    {
        return new Notifications($dataObjectFormatTableau);
    }

    protected function getNomColonnes(): array
    {
        return [
            "idnotification",
            "idutilisateur",
            "message",
            "date",
        ];
    }
}