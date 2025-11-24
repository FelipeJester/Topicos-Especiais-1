<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\UserModel;

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
    }

    /**
     * Exibe o formulário de login.
     */
    public function login()
    {
        if (Auth::check()) {
            $this->redirect('/');
        }

        $data = [
            'title' => 'Login Administrativo',
            'flash' => $this->getFlashMessage()
        ];

        // Se o método for POST, tenta autenticar
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
            return;
        }

        $this->view('auth/login', $data);
    }

    /**
     * Processa a tentativa de login.
     */
    private function handleLogin()
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);

        if (!$email || !$senha) {
            $this->setFlashMessage('error', 'Preencha todos os campos.');
            $this->redirect('login');
            return;
        }

        $user = $this->userModel->authenticate($email, $senha);

        if ($user) {
            Auth::login($user);
            $this->setFlashMessage('success', 'Login realizado com sucesso!');
            $this->redirect('/');
        } else {
            $this->setFlashMessage('error', 'Email ou senha inválidos.');
            $this->redirect('login');
        }
    }

    /**
     * Realiza o logout do usuário.
     */
    public function logout()
    {
        Auth::logout();
        $this->setFlashMessage('info', 'Você foi desconectado.');
        $this->redirect('/');
    }
}
