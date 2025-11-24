<?php

namespace App\Core;

class Autoload
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
            // Converte o namespace da classe para um caminho de arquivo
            // Ex: App\Controllers\HomeController -> app/Controllers/HomeController.php
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

            // Remove o prefixo 'App\' para começar a busca no diretório raiz 'app/'
            $file = str_replace('App' . DIRECTORY_SEPARATOR, 'app' . DIRECTORY_SEPARATOR, $file);

            // Verifica se o arquivo existe e o inclui
            if (file_exists($file)) {
                require_once $file;
                return true;
            }
            return false;
        });
    }
}
