<?php

namespace app\src\view\components\ui;

use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\dataObject\Avis;
use app\src\model\repository\UtilisateurRepository;
use app\src\view\components\ComponentInterface;
use app\src\view\resources\icons\I_Accepter;
use app\src\view\resources\icons\I_Archiver;
use app\src\view\resources\icons\I_Edit;

class Detail implements ComponentInterface
{
    /**
     * @throws ServerErrorException
     */
    public static function render(array $params): void
    {
        $utilisateur = $params[0];
        $nom = $utilisateur->getNom();
        $id = $utilisateur->getIdutilisateur();
        $url = Application::getRedirect();
        echo <<<HTML
 <div class="w-full gap-4 mx-auto">
        <div class="w-full flex md:flex-row flex-col justify-between items-start">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-semibold leading-7 text-zinc-900">$nom</h3>
</div>
<div class="flex flex-row gap-4">
    <span class="inline-flex cursor-pointer  -space-x-px overflow-hidden rounded-md border bg-white shadow-sm">
    <a href="/edit_profile/$id?$url" class="cursor-pointer inline-block px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 focus:relative">
HTML;
        echo I_Edit::render("w-5 h-5");
        echo "</a>";
        if ((new UtilisateurRepository([]))->isArchived($utilisateur)) {
            echo("<a href=\"/utilisateurs/" . $utilisateur->getIdutilisateur() . "/archiver\" class=\"inline-block px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 focus:relative\">");
            echo I_Accepter::render("w-5 h-5");
            echo("</a>");
        } else {
            echo("<a href=\"/utilisateurs/" . $utilisateur->getIdutilisateur() . "/archiver\" class=\"inline-block px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 focus:relative\">");
            echo I_Archiver::render("w-5 h-5");
            echo("</a>");
        }
        echo "</span></div>
</div>
<div class=\"divide-y divide-zinc-100\"></div>
<div class=\"px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0\">
                <div class=\"text-sm font-medium leading-6 text-zinc-900\">Numéro de téléphone</div>
                <div class=\"mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0\">";
        $numTel = $utilisateur->getNumtelephone();
        if ($numTel != null) echo $numTel;
        else echo("Non renseigné");
        echo "</div></div>
<div class=\"px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0\">
    <div class=\"text-sm font-medium leading-6 text-zinc-900\">Email</div>
    <div class=\"mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0\">";
        $email = $utilisateur->getEmail();
        if ($email != null) echo "<a href=\"mailto:" . $email . "\">" . $email . "</a>";
        else echo("Non renseigné");
        echo "</div></div>";
    }

    public static function addDetail(string $title, ?string $content): void
    {
        echo "
<div class=\"px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0\">
<div class=\"text-sm font-medium leading-6 text-zinc-900\">$title</div>
<div class=\"mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0\">";
        if ($content != null) echo $content;
        else echo("Non renseigné");
        echo "</div></div>";
    }

    public static function start(): void
    {
        echo "<div class=\"mt-6 border-t border-zinc-100\">
        <div class=\"divide-y divide-zinc-100\">";
    }

    public static function end(): void
    {
        echo "</div></div>";
    }

    public static function space(): void
    {
        echo "<div class=\"my-4\"></div>";
    }

    public static function addDetailLink(string $title, null|string $link): void
    {
        echo "
        <div class=\"px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0\">
                <div class=\"text-sm font-medium leading-6 text-zinc-900\">$title</div>
                <div class=\"mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0\"> ";
        if ($link != null) echo "<a target=\"_blank\" href=\"" . $link . "\">" . $link . "</a>";
        else echo("Non renseigné");
        echo "</div>
</div>
        ";
    }

    public static function addDetailBool(string $title, null|bool $boolean): void
    {
        echo "<div class=\"px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0\">
            <div class=\"text-sm font-medium leading-6 text-zinc-900\">$title</div>
            <div class=\"mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0\">";
        if ($boolean == 1) echo "Oui";
        else if ($boolean == 0) echo "Non";
        else echo("Non renseigné");
        echo "</div>
</div>";
    }

    public static function addDetailAvis(string $title, Avis|array $avisPublic): void
    {
        echo "<div class=\"px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0\">
                        <div class=\"text-sm font-medium leading-6 text-zinc-900\">$title</div>
                        <div class=\"mt-1 text-sm leading-6 text-zinc-700 sm:col-span-2 sm:mt-0\">";
        foreach ($avisPublic as $avi) {
            echo "- " . $avi['avis'] . "<br>";
        }
        echo "</div></div>";
    }
}
