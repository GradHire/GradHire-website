<?php

namespace app\src\view\components\dashboard;

use app\src\view\components\ComponentInterface;
use InvalidArgumentException;

class OfferCard implements ComponentInterface
{
    public static function render(array $params): void
    {
        if (!isset($params['offer']) || !is_array($params['offer'])) throw new InvalidArgumentException('Offer array is missing or not an array');

        $offre = $params['offer'];

        $mail = substr($offre['emailentreprise'], 0, 6) . '…' . substr($offre['emailentreprise'], -3);
        $description = substr($offre['description'], 0, 50) . '…';

        $date = date_create($offre['datecreation']);
        $date = date_format($date, 'd/m/Y');

        $url = "https://yandex.com/map-widget/v1/?ll=3.850089%2C43.634623&mode=search&sll=10.854186%2C49.182076&sspn=73.212891%2C44.753627&text=" . urlencode($offre['adresse']) . "&z=16.97";

        $email = urlencode($offre['emailentreprise']);
        $telephone = urlencode($offre['telephoneentreprise']);

        echo <<<EOT
            <div class="h-[125px] w-full border-gray-200 border rounded-[8px] flex flex-col justify-between items-center bg-white relative px-6 py-3 mb-5">
                        <div class="w-full flex flex-row items-center justify-between">
                            <a class="font-semibold hover:underline" href="/offres/{$offre['idoffre']}">Offre n°{$offre['idoffre']}</a>
                            <p class="text-zinc-400 font-light text-xs">$date</p>
                        </div>
                        <div class="w-full flex flex-row justify-between items-center text-xs">
                            <div class="grid grid-cols-2 max-w-[200px]">
                            <p class="font-light text-zinc-400">Nom Entreprise : </p>
                                                <a class="underline text-blue-500" href="/entreprises/{$offre['idutilisateur']}">{$offre['nomentreprise']}</a>
                            <p class="font-light text-zinc-400">Sujet : </p>
                                                <p>{$offre['sujet']}</p>
            
                            <p class="font-light text-zinc-400">Thematique : </p>
                                                <p>{$offre['thematique']}</p>
                            </div>
                            <div class="h-full w-[1px] bg-zinc-300"></div>
                            <div class="grid grid-cols-2 max-w-[200px]">
                            <p class="font-light text-zinc-400">Description : </p>
                                                <p>$description</p>
                            <p class="font-light text-zinc-400">Email : </p>
                                                <p><a class="underline text-blue-500" href="mailto:$email">$mail</a></p>
            
                            <p class="font-light text-zinc-400">Telephone : </p>
                            <p><a class="underline text-blue-500" href="tel:$telephone">{$offre['telephoneentreprise']}</a></p>
                            </div>
                            <div class="h-full w-[1px] bg-zinc-300"></div>
                            <div class="rounded-[8px] border overflow-hidden">
                                <iframe src="$url" width="150" height="60"  allowfullscreen></iframe>
                            </div>
                        </div>
                        <div class="h-[50px] w-full border-zinc-200 border rounded-[8px] bg-zinc-50 absolute bottom-0 scale-[98%] translate-y-[8px] -z-[1]"></div>
                        <div class="h-[50px] w-full border-zinc-200 border rounded-[8px] bg-zinc-100  absolute bottom-0 scale-[95%] translate-y-[16px] -z-[2]"></div>
            </div>
        EOT;
    }
}