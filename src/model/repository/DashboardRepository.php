<?php

namespace app\src\model\repository;

use app\src\core\db\Database;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Request;

class DashboardRepository
{
    private const TYPE_SECTIONS = 'sections';
    private const TYPE_ACTIONS = 'actions';

    /**
     * @throws ServerErrorException
     */
    public function modifyParams(Request $request): void
    {
        $typeBlock = $request->getRouteParams()['type'];
        $filteredParams = $this->filterRequestedParams($request->getBody(), $typeBlock);

        if (!isset($_SESSION['parametres'])) {
            $_SESSION['parametres'] = [
                self::TYPE_SECTIONS => [],
                self::TYPE_ACTIONS => []
            ];
        }

        switch ($typeBlock) {
            case self::TYPE_SECTIONS:
            case self::TYPE_ACTIONS:
                $_SESSION['parametres'][$typeBlock] = $filteredParams;
                break;
        }

        $configJson = json_encode($_SESSION['parametres']);
        $statement = Database::get_conn()->prepare("SELECT * FROM updateParametres(?, ?);");
        $userId = Application::getUser()->id();
        $statement->execute([$userId, $configJson]);
        Application::redirectFromParam("/dashboard");
    }

    private function filterRequestedParams(array $body, string $typeBlock): array
    {
        $filteredParams = [];
        if (in_array($typeBlock, [self::TYPE_SECTIONS, self::TYPE_ACTIONS])) {
            $prefix = $typeBlock === self::TYPE_SECTIONS ? 'S' : 'A';
            foreach ($body as $key => $value) if (str_starts_with($key, $prefix)) $filteredParams[] = $key;
        }
        return $filteredParams;
    }

    /**
     * @throws ServerErrorException
     */
    public function fetchDashboardData(): array
    {
        $data = [];
        $conventionRepo = new ConventionRepository();
        $offresRepo = new OffresRepository();
        $postulerRepo = new PostulerRepository();

        $data['percentageBlockData1'] = $conventionRepo->getPourcentageEtudiantsConventionCetteAnnee();
        $data['numBlockData1'] = $offresRepo->getStatsDensembleStageEtAlternance();
        $data['barChartHorizontalData1'] = $offresRepo->getTop5DomainesPlusDemandes();
        $data['pieChartData1'] = $offresRepo->getStatsDistributionDomaine();
        $data['barChartVerticalData1'] = $offresRepo->getMoyenneCandidaturesParOffreParDomaine();
        $data['lineChartData1'] = $postulerRepo->getStatsCandidaturesParMois();
        $data['lastActionsData1'] = $offresRepo->getOffresDernierSemaine();

        return $data;
    }
}