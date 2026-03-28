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

    public function terms(): void
    {
        $this->view('home.terms');
    }

    public function privacy(): void
    {
        $this->view('home.privacy');
    }
}
