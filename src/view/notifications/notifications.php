<?php
/** @var $notificationsNonLues  \app\src\model\dataObject\Notifications[] */
/** @var $notificationsLues  \app\src\model\dataObject\Notifications[] */

use app\src\model\View;
use app\src\view\components\ui\Table;
View::setCurrentSection('Notifications');
$this->title = 'Notifications';
?>

<div class="flex flex-col gap-1 w-full gap-4 mx-auto">
    <h2 class="font-bold text-lg">Notifications non lues</h2>
    <div class=" gap-4 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 grid-cols-1 content-start place-items-stretch justify-items-stretch">
        <?php if ($notificationsNonLues!=null){ ?>
        <a href="/deleteAllNotificationsNonLue" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Supprimer toutes les notifications non lues</a>
        <?php } ?>
        <div class="overflow-x-auto w-full">
            <?php
            Table::createTable($notificationsNonLues, ["Message", "Date"], function ($notification) {
                    Table::cell($notification->getNotification());
                    Table::cell($notification->getDate());
                    Table::button("/notifications/lu/" . $notification->getIdnotification(), "Lire");
                    Table::button("/notifications/supprimer/" . $notification->getIdnotification(), "Supprimer");
                }
            );
            ?>
        </div>
    </div>
    <h2 class="font-bold text-lg">Notifications lues</h2>
    <div class=" gap-4 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 grid-cols-1 content-start place-items-stretch justify-items-stretch">
        <?php if ($notificationsLues!=null){ ?>
        <a href="/deleteAllNotificationsLue" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Supprimer toutes les notifications lues</a>
        <?php } ?>
        <div class="overflow-x-auto w-full">
            <?php
            Table::createTable($notificationsLues, ["Message", "Date"], function ($notification) {
                Table::cell($notification->getNotification());
                Table::cell($notification->getDate());
                Table::button("/notifications/lu/" . $notification->getIdnotification(), "Lire");
                Table::button("/notifications/supprimer/" . $notification->getIdnotification(), "Supprimer");
            }
            );
            ?>
        </div>
    </div>
</div>
