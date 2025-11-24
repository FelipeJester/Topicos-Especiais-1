<?php

// Define o diretório raiz do projeto
define('ROOT_PATH', dirname(__DIR__));

// Carrega as configurações e inicia a sessão
require_once ROOT_PATH . '/config/config.php';

// Carrega o Autoload
require_once ROOT_PATH . '/app/Core/Autoload.php';
\App\Core\Autoload::register();

// Carrega o Roteador
require_once ROOT_PATH . '/app/Core/Router.php';

// Instancia e executa o roteador
$router = new \App\Core\Router();
$router->run();