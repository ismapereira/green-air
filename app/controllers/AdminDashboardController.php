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
        $suggModel = new TreeSuggestion();

        $totalTrees = $treeModel->count();
        $totalUsers = $userModel->count();
        $topContributors = $userModel->topContributors(10);
        $riskCount = $treeModel->riskCount();
        $pendingSuggestions = $suggModel->pendingCount();

        // Query otimizada - uma única query para status
        $byStatus = [];
        $db = Database::getConnection();
        $statusRows = $db->query('SELECT ts.name, COUNT(t.id) as total FROM tree_status ts LEFT JOIN trees t ON t.status_id = ts.id GROUP BY ts.id, ts.name')->fetchAll(PDO::FETCH_ASSOC);
        foreach ($statusRows as $row) {
            $byStatus[$row['name']] = (int)$row['total'];
        }

        $bySpecies = $treeModel->countBySpecies();
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
            'pendingSuggestions' => $pendingSuggestions,
            'bySpecies' => $bySpecies,
            'speciesNames' => $speciesNames,
            'byStatus' => $byStatus,
            'byNeighborhood' => $byNeighborhood
        ]);
    }
}
