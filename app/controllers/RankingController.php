<?php
class RankingController extends Controller
{
    public function index(): void
    {
        $user = $this->requireAuth();
        $userModel = new User();

        $this->view('ranking.index', [
            'user' => $user,
            'currentUser' => $user,
            'weekly' => $userModel->weeklyRanking(10),
            'monthly' => $userModel->monthlyRanking(10),
            'allTime' => $userModel->topContributors(20)
        ]);
    }
}
