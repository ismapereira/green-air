<?php
class AdminDashboardController extends Controller
{
    public function index(): void
    {
        $user = $this->requireAdmin();
        $treeModel = new Tree();
        $userModel = new User();
        $speciesModel = new TreeSpecies();
        $statusModel = new TreeStatus();
        $totalTrees = $treeModel->count();
        $totalUsers = $userModel->count();
        $topContributors = $userModel->topContributors(10);
        $riskCount = $treeModel->riskCount();
        $bySpecies = $treeModel->countBySpecies();
        $byStatus = [];
        foreach ($statusModel->all() as $st) {
            $byStatus[$st['name']] = $treeModel->countByStatus((int)$st['id']);
        }
        $byNeighborhood = $treeModel->countByNeighborhood();
        $speciesList = $speciesModel->all();
        $speciesNames = [];
        foreach ($speciesList as $s) $speciesNames[$s['id']] = $s['name'];
        $this->view('admin.dashboard', [
            'user' => $user,
            'totalTrees' => $totalTrees,
            'totalUsers' => $totalUsers,
            'topContributors' => $topContributors,
            'riskCount' => $riskCount,
            'bySpecies' => $bySpecies,
            'speciesNames' => $speciesNames,
            'byStatus' => $byStatus,
            'byNeighborhood' => $byNeighborhood
        ]);
    }
}
