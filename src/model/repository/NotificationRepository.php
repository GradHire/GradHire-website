<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\dataObject\Notifications;

class NotificationRepository extends AbstractRepository
{

    private static string $nomTable = "notifications";
    private static NotificationRepository $notificationRepository;

    /**
     * @throws ServerErrorException
     */
    public static function getNotificationLue(int $id): array
    {
        $sql = Database::get_conn()->prepare("SELECT * FROM " . self::$nomTable . " WHERE idutilisateur = :id AND lu = true ORDER BY date ASC");
        $sql->execute(['id' => $id]);
        $dataObjects = [];
        foreach ($sql as $dataObjectFormatTableau) $dataObjects[] = self::getInstance()->construireDepuisTableau($dataObjectFormatTableau);
        return $dataObjects;
    }

    protected function construireDepuisTableau(array $dataObjectFormatTableau): Notifications
    {
        return new Notifications($dataObjectFormatTableau);
    }

    public static function getInstance(): NotificationRepository
    {
        if (!isset(self::$notificationRepository))
            self::$notificationRepository = new NotificationRepository([]);
        return self::$notificationRepository;
    }

    /**
     * @throws ServerErrorException
     */
    public static function createNotification(int $idutilisateur, string $message, string $url): void
    {
        self::Execute("INSERT INTO " . self::$nomTable . " (idutilisateur, notification, date, url, lu) VALUES (:idutilisateur, :notification, :date, :url, :lu)", [
            'idutilisateur' => $idutilisateur,
            'notification' => $message,
            'date' => date('Y-m-d H:i:s', time()),
            'url' => $url,
            'lu' => 0
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function deleteNotification(int $id): void
    {
        self::Execute("DELETE FROM " . self::$nomTable . " WHERE idnotification =:id", ['id' => $id]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function deleteAllNotificationLue(int $id): void
    {
        self::Execute("DELETE FROM " . self::$nomTable . " WHERE idutilisateur =:id AND lu = true", ['id' => $id]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function deleteAllNotificationNonLue(int $id): void
    {
        self::Execute("DELETE FROM " . self::$nomTable . " WHERE idutilisateur =:id AND lu = false", ['id' => $id]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function setLuToTrue(int $id): void
    {
        self::Execute("UPDATE " . self::$nomTable . " SET lu = true WHERE idnotification =:id", ['id' => $id]);
    }

    /**
     * @throws ServerErrorException
     */
    public static function loadNotification(int $id): ?Notifications
    {
        $data = self::Fetch("SELECT * FROM " . self::$nomTable . " WHERE idnotification = :id", ['id' => $id]);
        return $data ? self::getInstance()->construireDepuisTableau($data) : null;
    }

    /**
     * @throws ServerErrorException
     */
    public static function getNotificationNonLue(int $id): ?array
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