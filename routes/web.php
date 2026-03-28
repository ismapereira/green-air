<?php
/**
 * Rotas da aplicação - Green Air v2.0
 */

return [
    // Públicas
    'GET /'                    => ['HomeController', 'index'],
    'GET /login'               => ['AuthController', 'loginForm'],
    'POST /login'              => ['AuthController', 'login'],
    'GET /logout'              => ['AuthController', 'logout'],
    'POST /logout'             => ['AuthController', 'logout'],
    'GET /registro'            => ['AuthController', 'registerForm'],
    'POST /registro'           => ['AuthController', 'register'],
    'GET /esqueci-senha'       => ['AuthController', 'forgotForm'],
    'POST /esqueci-senha'      => ['AuthController', 'forgot'],
    'GET /redefinir-senha'     => ['AuthController', 'resetForm'],
    'POST /redefinir-senha'    => ['AuthController', 'resetPassword'],

    // Mapa público
    'GET /mapa'                => ['MapController', 'index'],
    'GET /api/mapa/arvores'    => ['MapController', 'apiTrees'],

    // Visualização pública de árvore
    'GET /arvore/{id}'         => ['TreeController', 'show'],

    // Área logada (usuário)
    'GET /painel'              => ['DashboardController', 'index'],
    'GET /minhas-arvores'      => ['TreeController', 'myTrees'],
    'GET /cadastrar-arvore'    => ['TreeController', 'create'],
    'POST /cadastrar-arvore'   => ['TreeController', 'store'],
    'GET /arvore/editar/{id}'  => ['TreeController', 'edit'],
    'POST /arvore/atualizar/{id}' => ['TreeController', 'update'],
    'POST /arvore/excluir/{id}'   => ['TreeController', 'delete'],
    'POST /arvore/sugerir/{id}'   => ['TreeController', 'suggest'],
    'GET /perfil'              => ['UserController', 'profile'],
    'POST /perfil'             => ['UserController', 'updateProfile'],
    'GET /ranking'             => ['RankingController', 'index'],

    // API
    'GET /api/clima'           => ['DashboardController', 'apiClima'],
    'GET /api/notificacoes'    => ['DashboardController', 'apiNotifications'],
    'POST /api/notificacoes/ler/{id}' => ['DashboardController', 'markNotificationRead'],

    // Admin
    'GET /admin'               => ['AdminDashboardController', 'index'],
    'GET /admin/usuarios'      => ['AdminUserController', 'index'],
    'GET /admin/usuarios/novo' => ['AdminUserController', 'create'],
    'POST /admin/usuarios'     => ['AdminUserController', 'store'],
    'GET /admin/usuarios/editar/{id}' => ['AdminUserController', 'edit'],
    'POST /admin/usuarios/editar/{id}' => ['AdminUserController', 'update'],
    'POST /admin/usuarios/excluir/{id}' => ['AdminUserController', 'delete'],

    'GET /admin/arvores'       => ['AdminTreeController', 'index'],
    'GET /admin/arvores/editar/{id}' => ['AdminTreeController', 'edit'],
    'POST /admin/arvores/editar/{id}' => ['AdminTreeController', 'update'],
    'POST /admin/arvores/excluir/{id}' => ['AdminTreeController', 'delete'],

    'GET /admin/especies'      => ['AdminSpeciesController', 'index'],
    'POST /admin/especies'     => ['AdminSpeciesController', 'store'],
    'POST /admin/especies/editar/{id}' => ['AdminSpeciesController', 'update'],
    'POST /admin/especies/excluir/{id}' => ['AdminSpeciesController', 'delete'],

    'GET /admin/status'        => ['AdminTreeStatusController', 'index'],
    'POST /admin/status'       => ['AdminTreeStatusController', 'store'],
    'POST /admin/status/editar/{id}' => ['AdminTreeStatusController', 'update'],
    'POST /admin/status/excluir/{id}' => ['AdminTreeStatusController', 'delete'],

    'GET /admin/contribuicoes' => ['AdminContributionController', 'index'],

    'GET /admin/sugestoes'     => ['AdminSuggestionController', 'index'],
    'POST /admin/sugestoes/aprovar/{id}' => ['AdminSuggestionController', 'approve'],
    'POST /admin/sugestoes/rejeitar/{id}' => ['AdminSuggestionController', 'reject'],

    'GET /admin/configuracoes' => ['AdminSettingsController', 'index'],
    'POST /admin/configuracoes' => ['AdminSettingsController', 'save'],
];
