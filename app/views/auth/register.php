<?php
$pageTitle = 'Cadastro';
$error = $error ?? null;
$old = $old ?? [];
require ROOT_PATH . '/app/views/layout/header.php';
?>
<div class="auth-page">
    <div class="auth-card auth-card-wide">
        <h1>Criar conta</h1>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" action="<?= BASE_URL ?>registro" class="auth-form" enctype="multipart/form-data">
            <label>
                <span>Nome</span>
                <input type="text" name="name" required value="<?= htmlspecialchars($old['name'] ?? '') ?>">
            </label>
            <label>
                <span>E-mail</span>
                <input type="email" name="email" required value="<?= htmlspecialchars($old['email'] ?? '') ?>">
            </label>
            <label>
                <span>Senha (mín. 6 caracteres)</span>
                <input type="password" name="password" required>
            </label>
            <label>
                <span>Confirmar senha</span>
                <input type="password" name="password_confirm" required>
            </label>
            <label>
                <span>Foto (opcional)</span>
                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp">
            </label>
            
            <p class="hint" style="text-align: center; margin-top: 1rem; margin-bottom: 1.5rem;">
                Ao clicar em Cadastrar, você precisará ler e aceitar nossos <a href="#" id="open_terms_modal">Termos de Uso</a>.
            </p>

            <button type="submit" class="btn btn-primary btn-block">Cadastrar</button>
        </form>
        <p class="auth-footer">Já tem conta? <a href="<?= BASE_URL ?>login">Entrar</a></p>
    </div>
</div>

<!-- Modal de Termos de Uso -->
<div id="terms_modal" class="modal" hidden>
    <div class="modal-content" style="max-width: 600px;">
        <button type="button" class="modal-close" id="close_terms_modal_btn">&times;</button>
        <h2 style="margin-top: 0; font-family: 'Outfit', sans-serif;">Termos de Uso e Privacidade</h2>
        <div style="max-height: 400px; overflow-y: auto; padding-right: 10px; margin-bottom: 1.5rem; font-size: 0.95rem; color: var(--gray-700);">
            <p><strong>Bem-vindo ao Green Air!</strong></p>
            <p>Por favor, leia atentamente estes Termos de Uso e Política de Privacidade antes de começar a usar nossa plataforma de mapeamento colaborativo de árvores urbanas.</p>
            
            <h3>1. Aceitação dos Termos</h3>
            <p>Ao se cadastrar e utilizar o aplicativo Green Air, você concorda expressamente com as regras aqui descritas. Caso não concorde com qualquer parte destes termos, você não deve finalizar o cadastro nem utilizar a plataforma.</p>

            <h3>2. Objetivo da Aplicação</h3>
            <p>O Green Air tem caráter educacional e colaborativo, visando catalogar a vegetação urbana e conscientizar sobre a preservação ambiental. Os dados fornecidos (fotos, localização, condição da árvore) são de natureza pública e poderão ser consultados por qualquer visitante da plataforma.</p>

            <h3>3. Conduta e Responsabilidade do Usuário</h3>
            <ul>
                <li>Você garante que as fotos e dados enviados são de sua autoria ou você tem permissão legal para uso.</li>
                <li>É proibido o envio de imagens inapropriadas, conteúdos de ódio, spam ou dados falsos intencionais (fake locations).</li>
                <li>Caso nossa moderação identifique abusos, reservamo-nos o direito de advertir o usuário, remover os dados envolvidos ou até mesmo banir a conta permanentemente, sem aviso prévio.</li>
                <li>A pontuação e o sistema de ranking da plataforma (níveis Bronze, Prata, Ouro) têm fins meramente motivacionais.</li>
            </ul>

            <h3>4. Coleta, Proteção e Compartilhamento de Dados</h3>
            <p>Nós tratamos sua privacidade com seriedade. Informações coletadas durante o registro e o uso são protegidas:</p>
            <ul>
                <li><strong>Dados Coletados:</strong> Nome, e-mail, senha (criptografada), localização aproximada (através do mapeamento das árvores) e fotos do perfil/árvores.</li>
                <li><strong>Como usamos seus dados:</strong> Seu "Nome" e "Foto de perfil" ficarão visíveis para outros usuários como autor do mapeamento da árvore e nos rankings. Seu <strong>endereço de e-mail nunca será publicado</strong> na plataforma.</li>
                <li><strong>Proteção Técnica:</strong> Utilizamos medidas de segurança alinhadas aos padrões da indústria web, incluindo proteção via hashes fortes (`bcrypt` ou similares) para senhas, a fim de proteger seus dados contra perda, acesso não autorizado e uso indevido.</li>
                <li><strong>Compartilhamento:</strong> Não vendemos, trocamos ou alugamos suas informações de identificação pessoal com terceiros. Estatísticas de vegetação e relatórios ambientais agregados (sem identificar o usuário individualmente) podem ser gerados e compartilhados com entidades públicas ou ONGs visando a melhoria urbana.</li>
            </ul>

            <h3>5. Atualizações dos Termos</h3>
            <p>Os Termos de Uso podem sofrer revisões periódicas. Notificaremos os usuários sobre mudanças substanciais em nossa Política de Privacidade diretamente na plataforma ou pelo e-mail cadastrado.</p>

            <p style="margin-top: 2rem;"><strong>Ao clicar em "Aceitar", você afirma ter lido e compreendido todos os pontos acima e aceita vincular-se a estes Termos legalmente.</strong></p>
        </div>
        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <button type="button" class="btn btn-secondary" id="decline_terms_btn">Recusar</button>
            <button type="button" class="btn btn-primary" id="accept_terms_btn">Li e Aceito</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('terms_modal');
    const btnAccept = document.getElementById('accept_terms_btn');
    const btnDecline = document.getElementById('decline_terms_btn');
    const btnClose = document.getElementById('close_terms_modal_btn');
    const lnkOpen = document.getElementById('open_terms_modal');
    const form = document.querySelector('.auth-form');
    let termsAccepted = false;

    function closeModal() {
        modal.setAttribute('hidden', '');
    }

    if (lnkOpen) {
        lnkOpen.addEventListener('click', (e) => {
            e.preventDefault();
            modal.removeAttribute('hidden');
        });
    }

    btnClose.addEventListener('click', closeModal);

    btnDecline.addEventListener('click', () => {
        termsAccepted = false;
        closeModal();
    });

    btnAccept.addEventListener('click', () => {
        termsAccepted = true;
        closeModal();
        let hiddenInput = document.getElementById('terms_hidden');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'terms';
            hiddenInput.id = 'terms_hidden';
            hiddenInput.value = '1';
            form.appendChild(hiddenInput);
        }
        form.submit();
    });

    form.addEventListener('submit', (e) => {
        if (!termsAccepted) {
            e.preventDefault(); // Impede o envio do formulário
            modal.removeAttribute('hidden'); // Abre o modal
        }
    });
});
</script>
<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
