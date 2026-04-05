<?php
$pageTitle = 'Nova Sugestão';
require ROOT_PATH . '/app/views/layout/header.php';
$categories = $categories ?? [];
$old = $old ?? [];
$categoryIcons = [
    'feature'     => 'bi-lightbulb',
    'species'     => 'bi-flower1',
    'improvement' => 'bi-arrow-up-circle',
    'bug'         => 'bi-bug',
    'other'       => 'bi-chat-dots',
];
?>

<div class="container py-4" style="max-width: 600px">
    <a href="<?= BASE_URL ?>sugestoes" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>

    <div class="ga-glass-card p-4">
        <h3 class="fw-bold mb-1"><i class="bi bi-lightbulb me-2 text-warning"></i>Nova Sugestão</h3>
        <p class="text-muted small mb-4">Compartilhe sua ideia para melhorar o Green Air. Todas as sugestões são analisadas pela equipe.</p>

        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger py-2 small"><i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <form method="post" action="<?= BASE_URL ?>sugestoes/nova">
            <input type="hidden" name="_csrf" value="<?= $csrfToken ?>">

            <div class="mb-3">
                <label class="form-label fw-bold">Categoria *</label>
                <div class="row g-2">
                    <?php foreach ($categories as $key => $label): ?>
                    <div class="col-6 col-md-4">
                        <input type="radio" class="btn-check" name="category" id="cat-<?= $key ?>" value="<?= $key ?>" <?= ($old['category'] ?? '') === $key ? 'checked' : '' ?> required>
                        <label class="btn btn-outline-secondary w-100 d-flex align-items-center gap-2 py-2" for="cat-<?= $key ?>">
                            <i class="bi <?= $categoryIcons[$key] ?? 'bi-chat-dots' ?>"></i>
                            <span class="small"><?= htmlspecialchars($label) ?></span>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="suggestion-title" class="form-label fw-bold">Título *</label>
                <input type="text" name="title" id="suggestion-title" class="form-control" placeholder="Resumo da sua sugestão" value="<?= htmlspecialchars($old['title'] ?? '') ?>" required minlength="5" maxlength="150">
                <div class="form-text">5 a 150 caracteres</div>
            </div>

            <div class="mb-4">
                <label for="suggestion-desc" class="form-label fw-bold">Descrição *</label>
                <textarea name="description" id="suggestion-desc" class="form-control" rows="5" placeholder="Descreva sua ideia com detalhes. Quanto mais informações, melhor a equipe poderá avaliar." required minlength="10"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                <div class="form-text">Mínimo 10 caracteres</div>
            </div>

            <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                <i class="bi bi-send me-2"></i>Enviar Sugestão
            </button>
        </form>
    </div>

    <div class="mt-4 p-3 ga-glass-card">
        <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-1 text-info"></i>Dicas</h6>
        <ul class="small text-muted mb-0 ps-3">
            <li>Use a categoria <strong>Nova espécie</strong> para sugerir árvores que não estão no catálogo.</li>
            <li>Para reportar um problema, descreva os passos para reproduzi-lo.</li>
            <li>Sugestões de melhorias na interface são muito bem-vindas!</li>
            <li>Todas as sugestões são revisadas pela equipe.</li>
        </ul>
    </div>
</div>

<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
