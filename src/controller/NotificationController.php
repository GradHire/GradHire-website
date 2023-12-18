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
        $notifications = (new NotificationRepository())->getAllNotificationByUserId(Application::getUser()->id());
        $notificationsNonLues = [];
        $notificationsLues = [];
        foreach ($notifications as $notification) {
            if ($notification->isLu() == 0) {
                $notificationsNonLues[] = $notification;
            } else {
                $notificationsLues[] = $notification;
            }
        }
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
            Application::redirectFromParam($notification->getUrl());
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function supprimerNotification(Request $request): void
    {
        $id = $request->getRouteParams()['id'];
        $notification = NotificationRepository::loadNotification($id);
        if ($notification === null) {
            Application::redirectFromParam("/notifications");
        } else {
            NotificationRepository::deleteNotification($id);
            Application::redirectFromParam("/notifications");
        }
    }
}