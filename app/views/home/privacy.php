<?php
$pageTitle = 'Política de Privacidade';
require ROOT_PATH . '/app/views/layout/header.php';
?>

<div class="container py-5" style="max-width:800px">
    <div class="glass-card p-4 p-md-5" data-aos="fade-up">
        <div class="text-center mb-4">
            <i class="bi bi-shield-check" style="font-size:2.5rem;color:var(--ga-primary)"></i>
            <h1 class="fw-bold mt-2">Política de Privacidade</h1>
            <p class="text-muted small">Última atualização: <?= date('d/m/Y') ?></p>
        </div>

        <h5 class="fw-bold mt-4"><i class="bi bi-database text-success me-2"></i>Dados Coletados</h5>
        <p>Ao utilizar o Green Air, coletamos os seguintes dados:</p>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead><tr><th>Dado</th><th>Finalidade</th><th>Base Legal</th></tr></thead>
                <tbody>
                    <tr><td>Nome e E-mail</td><td>Identificação e comunicação</td><td>Consentimento</td></tr>
                    <tr><td>Senha (hash)</td><td>Autenticação segura</td><td>Consentimento</td></tr>
                    <tr><td>Foto de perfil</td><td>Personalização (opcional)</td><td>Consentimento</td></tr>
                    <tr><td>Fotos de árvores</td><td>Registro visual do mapeamento</td><td>Consentimento</td></tr>
                    <tr><td>Coordenadas GPS</td><td>Geolocalização das árvores</td><td>Consentimento</td></tr>
                    <tr><td>Endereço IP</td><td>Segurança (rate limiting)</td><td>Interesse legítimo</td></tr>
                </tbody>
            </table>
        </div>

        <h5 class="fw-bold mt-4"><i class="bi bi-geo-alt text-success me-2"></i>Geolocalização</h5>
        <ul>
            <li><strong>Não rastreamos sua localização em tempo real.</strong></li>
            <li>As coordenadas GPS são utilizadas exclusivamente para registrar a posição das árvores no mapa.</li>
            <li>A geolocalização é acionada apenas quando você cadastra uma árvore e pode ser negada no navegador.</li>
            <li>Dados de localização pessoal do usuário não são armazenados em banco de dados.</li>
        </ul>

        <h5 class="fw-bold mt-4"><i class="bi bi-lock text-success me-2"></i>Segurança</h5>
        <p>Adotamos medidas técnicas para proteger seus dados:</p>
        <ul>
            <li><strong>Senhas</strong> são armazenadas com hash criptográfico (bcrypt) — nunca em texto plano.</li>
            <li><strong>Sessões</strong> utilizam cookies HttpOnly e SameSite para prevenir ataques.</li>
            <li><strong>Formulários</strong> são protegidos com tokens CSRF contra requisições forjadas.</li>
            <li><strong>Rate limiting</strong> impede ataques de força bruta no login.</li>
            <li><strong>Uploads</strong> são validados por tipo MIME e tamanho antes de serem aceitos.</li>
        </ul>

        <h5 class="fw-bold mt-4"><i class="bi bi-cloud text-success me-2"></i>Serviços de Terceiros</h5>
        <p>A plataforma utiliza APIs externas para dados climáticos:</p>
        <ul>
            <li><strong>OpenWeather API</strong> — dados de clima, qualidade do ar e previsão. Os dados são cacheados no servidor e não enviam informações pessoais do usuário.</li>
            <li><strong>Nominatim/OpenStreetMap</strong> — reverse geocoding (coordenadas → endereço). A consulta é feita no frontend e não envia dados pessoais.</li>
            <li><strong>Google Fonts</strong>, <strong>Bootstrap CDN</strong>, <strong>unpkg</strong> — recursos estáticos. Nenhum dado pessoal é compartilhado.</li>
        </ul>

        <h5 class="fw-bold mt-4"><i class="bi bi-cookie text-success me-2"></i>Cookies</h5>
        <p>Utilizamos apenas cookies essenciais:</p>
        <ul>
            <li><strong>Cookie de sessão</strong> — mantém o login ativo. Expira ao fechar o navegador.</li>
            <li><strong>LocalStorage</strong> — armazena a preferência de tema (claro/escuro) no navegador.</li>
        </ul>
        <p>Não utilizamos cookies de rastreamento, analytics ou publicidade.</p>

        <h5 class="fw-bold mt-4"><i class="bi bi-person-check text-success me-2"></i>Seus Direitos</h5>
        <p>Você possui os seguintes direitos sobre seus dados:</p>
        <ul>
            <li><strong>Acesso</strong> — consulte seus dados na página de Perfil.</li>
            <li><strong>Retificação</strong> — edite seu nome, e-mail e foto no Perfil.</li>
            <li><strong>Exclusão</strong> — solicite a exclusão da sua conta entrando em contato conosco.</li>
            <li><strong>Portabilidade</strong> — seus dados de contribuições podem ser exportados mediante solicitação.</li>
        </ul>

        <h5 class="fw-bold mt-4"><i class="bi bi-arrow-repeat text-success me-2"></i>Retenção de Dados</h5>
        <ul>
            <li>Dados de conta são mantidos enquanto a conta estiver ativa.</li>
            <li>Árvores cadastradas permanecem no mapa mesmo após exclusão da conta (dados anonimizados).</li>
            <li>Registros de tentativas de login são removidos automaticamente após 24 horas.</li>
            <li>Tokens de redefinição de senha expiram em 24 horas.</li>
        </ul>

        <h5 class="fw-bold mt-4"><i class="bi bi-pencil-square text-success me-2"></i>Alterações nesta Política</h5>
        <p>Esta política pode ser atualizada periodicamente. Notificaremos os usuários por meio de notificações internas na plataforma sobre mudanças significativas.</p>

        <h5 class="fw-bold mt-4"><i class="bi bi-envelope text-success me-2"></i>Contato</h5>
        <p>Para dúvidas sobre privacidade, entre em contato pelo e-mail: <strong>ismaelpereirafeitosa@hotmail.com</strong></p>

        <hr class="my-4" style="border-color: var(--ga-border)">
        <div class="text-center">
            <a href="<?= BASE_URL ?>" class="btn btn-outline-success"><i class="bi bi-arrow-left me-2"></i>Voltar ao Início</a>
        </div>
    </div>
</div>

<?php require ROOT_PATH . '/app/views/layout/footer.php'; ?>
