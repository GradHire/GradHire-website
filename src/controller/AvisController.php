<?php
namespace app\src\controller;

use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\Form\FormModel;
use app\src\model\repository\AvisRepository;
use app\src\model\repository\EntrepriseRepository;
use app\src\model\Request;

class AvisController extends AbstractController
{
    public function posterAvis(Request $request): string
    {
        $identreprise = $request->getRouteParams()["id"];
        $nomEntreprise = EntrepriseRepository::getNomEntrepriseById($identreprise);
        if ($nomEntreprise == null) {
            $nomEntreprise = "Entreprise inconnue";
        }
        $form = new FormModel([
            'Nom Entreprise' => $nomEntreprise,
            'Avis' => FormModel::string("Avis")->required()->asterisk()->forget(),
        ]);
        if ($request->getMethod() == 'post') {
            if ($form->validate($request->getBody())){
                AvisRepository::createAvis($identreprise, Auth::get_user()->id(), $form->getParsedBody()['Avis']);
                Application::redirectFromParam("/entreprises");
            }
        }
        return $this->render('avis/Avis', [
            'form' => $form
        ]);
    }

    public function modifierAvis(Request $request): string
    {
        $identreprise = $request->getRouteParams()["id"];
        $idutilisateur = Auth::get_user()->id();
        $nomEntreprise = EntrepriseRepository::getNomEntrepriseById($identreprise);
        $form = new FormModel([
            'Nom Entreprise' => $nomEntreprise,
            'Avis' => FormModel::string("Avis")->required()->asterisk()->default(AvisRepository::getAvisById($identreprise, Auth::get_user()->id())['avis']),
        ]);
        if ($request->getMethod() == 'post') {
            if ($form->validate($request->getBody())){
                AvisRepository::updateAvis($identreprise, Auth::get_user()->id(), $form->getParsedBody()['Avis']);
                Application::redirectFromParam("/entreprises");
            }
        }
        return $this->render('avis/Avis', [
            'form' => $form
        ]);
    }
}