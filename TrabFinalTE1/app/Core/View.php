<?php

namespace App\Core;

class View
{
    /**
     * Renderiza uma view.
     * @param string $viewName Nome do arquivo da view (sem extensão .php)
     * @param array $data Dados a serem passados para a view
     */
    public static function render(string $viewName, array $data = [])
    {
        // Extrai os dados para que as chaves se tornem variáveis na view
        extract($data);

        // Define o caminho completo para o arquivo da view
        $viewPath = ROOT_PATH . '/views/' . $viewName . '.php';

        if (file_exists($viewPath)) {
            // Inicia o buffer de saída
            ob_start();
            // Inclui o arquivo da view
            require $viewPath;
            // Retorna o conteúdo do buffer
            echo ob_get_clean();
        } else {
            // Trata erro de view não encontrada
            die("Erro: View '{$viewName}' não encontrada.");
        }
    }
}
