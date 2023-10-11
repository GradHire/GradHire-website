<?php
/** @var $utilisateur \app\src\model\dataObject\Utilisateur */
use app\src\model\repository\UtilisateurRepository;
use app\src\model\dataObject\Utilisateur;
use app\src\model\Auth\Auth;

if(isset($_POST['save'])) {

    $type = Auth::load_user_by_id($utilisateur->getIdutilisateur())->role();
    $id = $utilisateur->getIdutilisateur();
    $email = "";
    $nom = "";
    $tel = "";

    if (isset($_POST['emailutilisateur']) && $_POST['emailutilisateur'] != $utilisateur->getEmailutilisateur()) {
        $email = $_POST['emailutilisateur'];
        $utilisateur->setEmailutilisateur($email);
    } else {
        $email = null;
    }
    if (isset($_POST['nomutilisateur']) && $_POST['nomutilisateur'] != $utilisateur->getNomutilisateur()) {
        $nom = $_POST['nomutilisateur'];
           $utilisateur->setNomutilisateur($nom);
    } else {
        $nom = null;
    }
    if (isset($_POST['numtelutilisateur']) && $_POST['numtelutilisateur'] != $utilisateur->getNumtelutilisateur()) {
        $tel = $_POST['numtelutilisateur'];
        $utilisateur->setNumtelutilisateur($tel);
    } else {
        $tel = null;
    }

    if ($email != null && $nom != null && $tel != null) {
        $newutilisateur = new Utilisateur(
            $utilisateur->getIdutilisateur(),
            $_POST['emailutilisateur'],
            $_POST['nomutilisateur'],
            $_POST['numtelutilisateur']
        );
    }
}
?>

<form method="POST" name="save" action="/utilisateurs/<?= $utilisateur->getIdutilisateur() ?>" class="mt-6 border-t border-gray-100 w-full" id="detailUtilisateur">
    <div class="w-full flex md:flex-row flex-col justify-between items-start">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-semibold leading-7 text-gray-900"><?= $utilisateur->getNomutilisateur() ?></h3>
        </div>
        <div>
            <td class="whitespace-nowrap px-4 py-2">
                <input type="button" value="edit" name="edit" id="buttunEdit" class="inline-block rounded bg-orange-400 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700"></input>
            </td>
            <td class="whitespace-nowrap px-4 py-2">
                <button type="submit" value="save" name="save" id="buttunSave" class="inline-block rounded bg-orange-400 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700 hidden">Save</button>
            </td>
            <td class="whitespace-nowrap px-4 py-2">
                <input type="button" value="delete" name="delete" class="inline-block rounded bg-red-600 px-4 py-2 text-xs font-medium text-white hover:bg-zinc-700"></input>
            </td>
        </div>
    </div>
    <dl class="divide-y divide-gray-100">
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-gray-900">Nom d'utilisateur</dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0" id="nom">
                <?php
                $nom = $utilisateur->getNomutilisateur();
                if ($nom != null) echo $nom;
                else echo("Non renseigné");
                ?></dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-gray-900">Email</dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0" id="email">
                <?php
                $email = $utilisateur->getEmailutilisateur();
                if ($email != null) echo $email;
                else echo("Non renseigné");
                ?></dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
            <dt class="text-sm font-medium leading-6 text-gray-900">Numéro de téléphone</dt>
            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0" id="tel">
                <?php
                $tel = $utilisateur->getNumtelutilisateur();
                if ($tel != null) echo $tel;
                else echo("Non renseigné");
                ?></dd>
        </div>
    </dl>
</form>

<script>
    const editButton = document.getElementById("buttunEdit");
    const saveButton = document.getElementById("buttunSave");

    const form = document.querySelector("#detailUtilisateur");

    editButton.addEventListener("click", function() {
        editButton.classList.add("hidden");
        saveButton.classList.remove("hidden");

        const nomElement = document.getElementById("nom")
        const emailElement = document.getElementById("email")
        const telElement = document.getElementById("tel")

        const nomInput = document.createElement("input");
        nomInput.value = nomElement.textContent;
        nomInput.name = "nomutilisateur";
        const emailInput = document.createElement("input");
        emailInput.value = emailElement.textContent;
        emailInput.name = "emailutilisateur";
        const telInput = document.createElement("input");
        telInput.value = telElement.textContent;
        telInput.name = "numtelutilisateur";

        nomElement.innerHTML = "";
        nomElement.appendChild(nomInput);
        emailElement.innerHTML = "";
        emailElement.appendChild(emailInput);
        telElement.innerHTML = "";
        telElement.appendChild(telInput);
    });

</script>

