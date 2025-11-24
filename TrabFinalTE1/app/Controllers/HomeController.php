<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // Exemplo de como passar dados para a view
        $data = [
            'title' => 'Bem-vindo à Concessionária',
            'flash' => $this->getFlashMessage()
        ];

        $this->view('home/index', $data);
    }
}
