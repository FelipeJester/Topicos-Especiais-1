<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Protege todas as ações deste controller
        Auth::protect();
    }

    public function index()
    {
        $data = [
            'title' => 'Painel Administrativo',
            'flash' => $this->getFlashMessage()
        ];

        $this->view('admin/index', $data);
    }
}
