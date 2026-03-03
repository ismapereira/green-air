<?php
/**
 * Rotas da aplicação (front controller usa este array)
 * Green Air - Mapeamento de Árvores Urbanas
 */

return [
    // Públicas
    'GET /'                    => ['HomeController', 'index'],
    'GET /login'               => ['AuthController', 'loginForm'],
    'POST /login'              => ['AuthController', 'login'],
    'GET /logout'              => ['AuthController', 'logout'],
    'GET /registro'            => ['AuthController', 'registerForm'],
    'POST /registro'            => ['AuthController', 'register'],
    'GET /esqueci-senha'       => ['AuthController', 'forgotForm'],
    'POST /esqueci-senha'      => ['AuthController', 'forgot'],
    'GET /redefinir-senha'     => ['AuthController', 'resetForm'],
    'POST /redefinir-senha'    => ['AuthController', 'resetPassword'],

    // Mapa público (visualização)
    'GET /mapa'                => ['MapController', 'index'],
    'GET /api/mapa/arvores'    => ['MapController', 'apiTrees'],

    // Área logada (usuário)
    'GET /painel'              => ['DashboardController', 'index'],
    'GET /minhas-arvores'      => ['TreeController', 'myTrees'],
    'GET /cadastrar-arvore'    => ['TreeController', 'create'],
    'POST /cadastrar-arvore'   => ['TreeController', 'store'],
    'GET /arvore/editar/{id}'  => ['TreeController', 'edit'],
    'POST /arvore/atualizar/{id}' => ['TreeController', 'update'],
    'POST /arvore/sugerir/{id}'   => ['TreeController', 'suggest'],
    'GET /perfil'              => ['UserController', 'profile'],
    'POST /perfil'             => ['UserController', 'updateProfile'],
    'GET /ranking'             => ['RankingController', 'index'],

    // API clima (dashboard)
    'GET /api/clima'           => ['DashboardController', 'apiClima'],

    // Admin (CRUD completo)
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
    'GET /admin/configuracoes' => ['AdminSettingsController', 'index'],
    'POST /admin/configuracoes' => ['AdminSettingsController', 'save'],
];
