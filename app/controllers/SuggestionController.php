<?php
/**
 * Controller para sugestões colaborativas — lado do usuário.
 */
class SuggestionController extends Controller
{
    private const CATEGORIES = [
        'feature'     => 'Nova funcionalidade',
        'species'     => 'Nova espécie de árvore',
        'improvement' => 'Melhoria',
        'bug'         => 'Reportar problema',
        'other'       => 'Outro',
    ];

    public function index(): void
    {
        $user = $this->requireAuth();
        $model = new CommunitySuggestion();
        $mySuggestions = $model->byUser($user['id']);

        $this->view('suggestion.index', [
            'currentUser' => $user,
            'suggestions' => $mySuggestions,
            'categories' => self::CATEGORIES,
            'route' => '/sugestoes'
        ]);
    }

    public function create(): void
    {
        $user = $this->requireAuth();
        $this->view('suggestion.create', [
            'currentUser' => $user,
            'categories' => self::CATEGORIES,
            'old' => $_SESSION['suggestion_old'] ?? [],
            'route' => '/sugestoes'
        ]);
        unset($_SESSION['suggestion_old']);
    }

    public function store(): void
    {
        $this->validateCsrf();
        $user = $this->requireAuth();

        $category = trim($_POST['category'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        // Validação
        if (!array_key_exists($category, self::CATEGORIES)) {
            $_SESSION['flash_error'] = 'Selecione uma categoria válida.';
            $_SESSION['suggestion_old'] = $_POST;
            $this->redirect('/sugestoes/nova');
            return;
        }
        if (mb_strlen($title) < 5 || mb_strlen($title) > 150) {
            $_SESSION['flash_error'] = 'O título deve ter entre 5 e 150 caracteres.';
            $_SESSION['suggestion_old'] = $_POST;
            $this->redirect('/sugestoes/nova');
            return;
        }
        if (mb_strlen($description) < 10) {
            $_SESSION['flash_error'] = 'A descrição deve ter pelo menos 10 caracteres.';
            $_SESSION['suggestion_old'] = $_POST;
            $this->redirect('/sugestoes/nova');
            return;
        }

        $model = new CommunitySuggestion();
        $model->create($user['id'], $category, $title, $description);

        $_SESSION['flash_success'] = 'Sugestão enviada com sucesso! A equipe irá analisá-la.';
        $this->redirect('/sugestoes');
    }
}
