<?php
class HomeController extends Controller
{
    public function index(): void
    {
        $user = $this->auth();
        if ($user) {
            $this->redirect('/painel');
            return;
        }
        $this->view('home.index');
    }
}
