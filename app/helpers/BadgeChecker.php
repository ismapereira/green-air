<?php
/**
 * Helper para verificação automática de conquistas.
 * Chamado após ações-chave (cadastrar árvore, enviar sugestão, etc.)
 */
class BadgeChecker
{
    /**
     * Verifica todas as conquistas e desbloqueia automaticamente as que forem atingidas.
     * Retorna array com slugs dos badges recém-desbloqueados.
     */
    public static function check(int $userId): array
    {
        $badgeModel = new Badge();
        $allBadges = $badgeModel->all();
        $unlocked = [];
        $db = Database::getConnection();

        foreach ($allBadges as $badge) {
            // Já possui? Pular
            if ($badgeModel->hasBadge($userId, (int)$badge['id'])) {
                continue;
            }

            $met = false;
            $type = $badge['criteria_type'];
            $required = (int)$badge['criteria_value'];

            switch ($type) {
                case 'trees_count':
                    $r = $db->prepare('SELECT COUNT(*) as c FROM trees WHERE user_id = ?');
                    $r->execute([$userId]);
                    $met = (int)$r->fetch(PDO::FETCH_ASSOC)['c'] >= $required;
                    break;

                case 'community_suggestions_count':
                    $r = $db->prepare('SELECT COUNT(*) as c FROM community_suggestions WHERE user_id = ?');
                    $r->execute([$userId]);
                    $met = (int)$r->fetch(PDO::FETCH_ASSOC)['c'] >= $required;
                    break;

                case 'accepted_suggestions_count':
                    $r = $db->prepare("SELECT COUNT(*) as c FROM community_suggestions WHERE user_id = ? AND status = 'accepted'");
                    $r->execute([$userId]);
                    $count = (int)$r->fetch(PDO::FETCH_ASSOC)['c'];
                    // Também contar sugestões "implemented"
                    $r2 = $db->prepare("SELECT COUNT(*) as c FROM community_suggestions WHERE user_id = ? AND status = 'implemented'");
                    $r2->execute([$userId]);
                    $count += (int)$r2->fetch(PDO::FETCH_ASSOC)['c'];
                    $met = $count >= $required;
                    break;

                case 'distinct_species_count':
                    $r = $db->prepare('SELECT COUNT(DISTINCT species_id) as c FROM trees WHERE user_id = ?');
                    $r->execute([$userId]);
                    $met = (int)$r->fetch(PDO::FETCH_ASSOC)['c'] >= $required;
                    break;

                case 'tree_suggestions_count':
                    $r = $db->prepare('SELECT COUNT(*) as c FROM tree_suggestions WHERE user_id = ?');
                    $r->execute([$userId]);
                    $met = (int)$r->fetch(PDO::FETCH_ASSOC)['c'] >= $required;
                    break;

                case 'streak_days':
                    $met = self::checkStreak($userId, $required, $db);
                    break;
            }

            if ($met) {
                $badgeModel->unlock($userId, (int)$badge['id']);
                $unlocked[] = $badge;

                // Notificar o usuário
                $notif = new Notification();
                $notif->create(
                    $userId,
                    'badge_unlocked',
                    'Conquista desbloqueada!',
                    'Você desbloqueou a conquista "' . $badge['name'] . '": ' . $badge['description'],
                    '/painel'
                );
            }
        }

        return $unlocked;
    }

    /**
     * Verifica streak de dias consecutivos cadastrando árvores.
     */
    private static function checkStreak(int $userId, int $requiredDays, PDO $db): bool
    {
        $stmt = $db->prepare(
            'SELECT DISTINCT DATE(created_at) as d FROM trees WHERE user_id = ? ORDER BY d DESC LIMIT ?'
        );
        $stmt->execute([$userId, $requiredDays + 10]); // margem
        $dates = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (count($dates) < $requiredDays) {
            return false;
        }

        $streak = 1;
        $maxStreak = 1;
        for ($i = 1; $i < count($dates); $i++) {
            $prev = new DateTime($dates[$i - 1]);
            $curr = new DateTime($dates[$i]);
            $diff = $prev->diff($curr)->days;
            if ($diff === 1) {
                $streak++;
                $maxStreak = max($maxStreak, $streak);
            } else {
                $streak = 1;
            }
        }

        return $maxStreak >= $requiredDays;
    }
}
