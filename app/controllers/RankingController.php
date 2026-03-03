<?php
class RankingController extends Controller
{
    public function index(): void
    {
        $user = $this->requireAuth();
        $userModel = new User();
        $weekly = $this->weeklyRanking();
        $monthly = $this->monthlyRanking();
        $allTime = $userModel->topContributors(20);
        $this->view('ranking.index', [
            'user' => $user,
            'currentUser' => $user,
            'weekly' => $weekly,
            'monthly' => $monthly,
            'allTime' => $allTime
        ]);
    }

    private function weeklyRanking(): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare('
            SELECT u.id, u.name, u.photo, u.level_id, ul.name as level_name, SUM(c.points_awarded) as total
            FROM contributions_log c
            JOIN users u ON c.user_id = u.id
            JOIN user_levels ul ON u.level_id = ul.id
            WHERE c.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY u.id ORDER BY total DESC LIMIT 10
        ');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function monthlyRanking(): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare('
            SELECT u.id, u.name, u.photo, u.level_id, ul.name as level_name, SUM(c.points_awarded) as total
            FROM contributions_log c
            JOIN users u ON c.user_id = u.id
            JOIN user_levels ul ON u.level_id = ul.id
            WHERE c.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY u.id ORDER BY total DESC LIMIT 10
        ');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
