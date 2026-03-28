<?php
$pageTitle = 'Termos de Uso';
require ROOT_PATH . '/app/views/layout/header.php';
?>

<div class="container py-5" style="max-width:800px">
    <div class="glass-card p-4 p-md-5" data-aos="fade-up">
        <div class="text-center mb-4">
            <i class="bi bi-file-earmark-text" style="font-size:2.5rem;color:var(--ga-primary)"></i>
            <h1 class="fw-bold mt-2">Termos de Uso</h1>
            <p class="text-muted small">Última atualização: <?= date('d/m/Y') ?></p>
        </div>

        <h5 class="fw-bold mt-4"><i class="bi bi-1-circle-fill text-success me-2"></i>Aceitação dos Termos</h5>
        <p>Ao criar uma conta e utilizar a plataforma Green Air, você declara ter lido, compreendido e aceito integralmente estes Termos de Uso. Caso não concorde com qualquer disposição, por favor não utilize a plataforma.</p>

        <h5 class="fw-bold mt-4"><i class="bi bi-2-circle-fill text-success me-2"></i>Sobre a Plataforma</h5>
        <p>O Green Air é uma plataforma colaborativa e gratuita de mapeamento de árvores urbanas. Nosso objetivo é promover a consciência ambiental e o mapeamento participativo da arborização das cidades.</p>

        <h5 class="fw-bold mt-4"><i class="bi bi-3-circle-fill text-success me-2"></i>Cadastro e Conta</h5>
        <ul>
            <li>Para utilizar os recursos da plataforma, é necessário criar uma conta com informações verdadeiras.</li>
            <li>Você é responsável pela segurança da sua conta e senha.</li>
            <li>Não é permitido criar contas falsas ou com informações enganosas.</li>
            <li>Menores de 13 anos não devem utilizar a plataforma sem o consentimento de um responsável.</li>
        </ul>

        <h5 class="fw-bold mt-4"><i class="bi bi-4-circle-fill text-success me-2"></i>Conteúdo e Contribuições</h5>
        <ul>
            <li>Ao cadastrar árvores, fotos e informações, você concede ao Green Air licença não exclusiva para exibir esse conteúdo publicamente na plataforma.</li>
            <li>As fotos enviadas devem ser de sua autoria ou de uso autorizado.</li>
            <li>Você se compromete a fornecer informações verídicas e precisas sobre as árvores cadastradas.</li>
            <li>Conteúdo ofensivo, falso ou que viole direitos de terceiros será removido e pode resultar em suspensão da conta.</li>
        </ul>

        <h5 class="fw-bold mt-4"><i class="bi bi-5-circle-fill text-success me-2"></i>Localização e GPS</h5>
        <ul>
            <li>A plataforma utiliza geolocalização exclusivamente para registrar a posição das árvores cadastradas.</li>
            <li>Não armazenamos a localização pessoal dos usuários.</li>
            <li>O uso do GPS é opcional e pode ser desativado a qualquer momento nas configurações do navegador.</li>
        </ul>

        <h5 class="fw-bold mt-4"><i class="bi bi-6-circle-fill text-success me-2"></i>Gamificação e Pontos</h5>
        <ul>
            <li>O sistema de pontos, níveis e ranking é meramente ilustrativo e motivacional.</li>
            <li>Tentativas de manipulação do sistema de pontos (cadastros falsos, spam) resultarão em suspensão.</li>
            <li>Pontos e níveis não possuem valor monetário ou conversível.</li>
        </ul>

        <h5 class="fw-bold mt-4"><i class="bi bi-7-circle-fill text-success me-2"></i>Moderação</h5>
        <p>O Green Air reserva-se o direito de moderar, editar ou remover qualquer conteúdo que viole estes termos. Usuários que reincidirem em violações terão suas contas suspensas ou excluídas permanentemente.</p>

        <h5 class="fw-bold mt-4"><i class="bi bi-8-circle-fill text-success me-2"></i>Limitação de Responsabilidade</h5>
        <p>A plataforma é fornecida "no estado em que se encontra". Não garantimos disponibilidade ininterrupta, precisão absoluta dos dados climáticos (fornecidos por APIs de terceiros) ou a integridade das informações cadastradas por outros usuários.</p>

        <h5 class="fw-bold mt-4"><i class="bi bi-9-circle-fill text-success me-2"></i>Alterações</h5>
        <p>Estes termos podem ser atualizados periodicamente. A continuação do uso da plataforma após alterações constitui aceitação dos novos termos.</p>

        <hr class="my-4" style="border-color: var(--ga-border)">
        <div class="text-center">
            <a href="<?= BASE_URL ?>" class="btn btn-outline-success"><i class="bi bi-arrow-left me-2"></i>Voltar ao Início</a>
        </div>
    </div>
</div>

<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
