<?php
/** @var $notificationsNonLues  Notifications[] */

/** @var $notificationsLues  Notifications[] */

use app\src\model\dataObject\Notifications;
use app\src\model\View;
use app\src\view\components\ui\Separator;
use app\src\view\components\ui\Table;
use app\src\view\resources\icons\I_Supprimer;

View::setCurrentSection('Notifications');
$this->title = 'Notifications';
?>

<div class="flex flex-col w-full gap-4 mx-auto">
    <div class="flex flex-col w-full gap-4 mx-auto">
        <div class="flex flex-row w-full justify-between items-center">
            <h2 class="font-bold text-lg">Notifications non lues</h2>
            <?php if ($notificationsNonLues != null) { ?>
                <a href="/deleteAllNotificationsNonLue"
                   class="cursor-pointer -space-x-px overflow-hidden rounded-md border bg-white hover:bg-red-500 duration-200 group shadow-sm w-[30px] h-[30px] flex items-center justify-center">
                    <?= I_Supprimer::render("w-5 h-5 group-hover:stroke-white duration-200"); ?>
                </a>
            <?php } ?>
        </div>
        <div class=" gap-4 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 grid-cols-1 content-start place-items-stretch justify-items-stretch">
            <div class=" overflow-x-auto w-full example ">
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
    </div>
    <?php Separator::render([]); ?>
    <div class="flex flex-col w-full gap-4 mx-auto">
        <div class="flex flex-row w-full justify-between items-center">
            <h2 class="font-bold text-lg">Notifications lues</h2>
            <?php if ($notificationsLues != null) { ?>
                <a href="/deleteAllNotificationsLue"
                   class="cursor-pointer -space-x-px overflow-hidden rounded-md border bg-white hover:bg-red-500 duration-200 group shadow-sm w-[30px] h-[30px] flex items-center justify-center">
                    <?= I_Supprimer::render("w-5 h-5 group-hover:stroke-white duration-200"); ?>
                </a>
            <?php } ?>
        </div>
        <div class=" gap-4 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 grid-cols-1 content-start place-items-stretch justify-items-stretch">

            <div class=" overflow-x-auto w-full example ">
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
</div>
