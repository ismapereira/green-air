<?php
class HomeController extends Controller
{
    public function index(): void
    {
        $user = $this->auth();
        if ($user) {
            $this->redirect('/painel');
            return;
        }
        $this->view('home.index');
    }

    public function terms(): void
    {
        $this->view('home.terms');
    }

    public function privacy(): void
    {
        $this->view('home.privacy');
    }

    public function publicDashboard(): void
    {
        $treeModel = new Tree();
        $userModel = new User();
        $speciesModel = new TreeSpecies();

        $this->view('home.dashboard', [
            'currentUser' => $this->auth(),
            'totalTrees' => $treeModel->count(),
            'totalUsers' => $userModel->count(),
            'totalSpecies' => count($speciesModel->all()),
            'topContributors' => $userModel->topContributors(5),
            'bySpecies' => $treeModel->countBySpecies(),
            'byNeighborhood' => $treeModel->countByNeighborhood(),
            'speciesList' => $speciesModel->all(),
            'recentTrees' => $treeModel->all(['limit' => 6])
        ]);
    }
}
