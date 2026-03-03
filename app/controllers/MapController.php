<?php
class MapController extends Controller
{
    public function index(): void
    {
        $speciesModel = new TreeSpecies();
        $statusModel = new TreeStatus();
        $this->view('map.index', [
            'currentUser' => $this->auth(),
            'species' => $speciesModel->all(),
            'statuses' => $statusModel->all()
        ]);
    }

    public function apiTrees(): void
    {
        $treeModel = new Tree();
        $filters = [];
        if (!empty($_GET['species_id'])) $filters['species_id'] = (int)$_GET['species_id'];
        if (!empty($_GET['status_id'])) $filters['status_id'] = (int)$_GET['status_id'];
        if (!empty($_GET['size'])) $filters['size'] = $_GET['size'];
        if (!empty($_GET['address'])) $filters['address'] = $_GET['address'];
        $trees = $treeModel->allForMap($filters);
        foreach ($trees as &$t) {
            if (!empty($t['photo'])) {
                $t['photo_url'] = BASE_URL . 'uploads/trees/' . $t['photo'];
            } else {
                $t['photo_url'] = null;
            }
        }
        $this->json($trees);
    }
}
