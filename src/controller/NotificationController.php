<?php

namespace app\src\controller;

use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\repository\NotificationRepository;
use app\src\model\Request;

class NotificationController extends AbstractController
{
    /**
     * @throws ServerErrorException
     */
    public function listeNotifications(): string
    {
        $notificationsNonLues = NotificationRepository::getNotificationNonLue(Application::getUser()->id());
        $notificationsLues = NotificationRepository::getNotificationLue(Application::getUser()->id());
        return $this->render('notifications/notifications', [
            'notificationsNonLues' => $notificationsNonLues,
            'notificationsLues' => $notificationsLues
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public function lireNotification(Request $request): void
    {
        $id = $request->getRouteParams()['id'];
        $notification = NotificationRepository::loadNotification($id);
        if ($notification === null) {
            Application::redirectFromParam("/notifications");
        } else {
            if (!$notification->isLu()) {
                NotificationRepository::setLuToTrue($id);
            }
            if ($notification->getUrl() != "") Application::redirectFromParam($notification->getUrl());
            else Application::redirectFromParam("/notifications");
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function supprimerNotification(Request $request): void
    {
        $id = $request->getRouteParams()['id'];
        $notification = NotificationRepository::loadNotification($id);
        if ($notification !== null) {
            NotificationRepository::deleteNotification($id);
        }
        Application::redirectFromParam("/notifications");
    }

    /**
     * @throws ServerErrorException
     */
    public function supprimerAllNotificationsNonLue(): void
    {
        NotificationRepository::deleteAllNotificationNonLue(Application::getUser()->id());
        Application::redirectFromParam("/notifications");
    }

    /**
     * @throws ServerErrorException
     */
    public function supprimerAllNotificationsLue(): void
    {
        NotificationRepository::deleteAllNotificationLue(Application::getUser()->id());
        Application::redirectFromParam("/notifications");
    }
}