<?php
class AdminContributionController extends Controller
{
    public function index(): void
    {
        $user = $this->requireAdmin();
        $model = new ContributionLog();
        $this->view('admin.contributions.index', ['user' => $user, 'contributions' => $model->all(200)]);
    }
}
