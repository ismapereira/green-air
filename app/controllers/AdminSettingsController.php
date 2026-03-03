<?php
class AdminSettingsController extends Controller
{
    public function index(): void
    {
        $user = $this->requireAdmin();
        $model = new Setting();
        $this->view('admin.settings.index', ['user' => $user, 'settings' => $model->all()]);
    }

    public function save(): void
    {
        $this->requireAdmin();
        $model = new Setting();
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'setting_') === 0) {
                $k = substr($key, 8);
                $model->set($k, trim($value));
            }
        }
        $_SESSION['admin_success'] = 'Configurações salvas.';
        $this->redirect('/admin/configuracoes');
    }
}
