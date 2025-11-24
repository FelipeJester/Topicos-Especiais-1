<?php

namespace App\Core;

class Controller
{
    /**
     * Carrega a classe View.
     * @param string $viewName
     * @param array $data
     */
    protected function view(string $viewName, array $data = [])
    {
        View::render($viewName, $data);
    }

    /**
     * Redireciona o usuário para uma URL.
     * @param string $url
     */
    protected function redirect(string $url)
    {
        header("Location: " . BASE_URL . ltrim($url, '/'));
        exit();
    }

    /**
     * Define uma mensagem de sessão (flash message).
     * @param string $type 'success', 'error', 'warning', 'info'
     * @param string $message
     */
    protected function setFlashMessage(string $type, string $message)
    {
        $_SESSION['flash_message'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * Obtém e limpa a mensagem de sessão.
     * @return array|null
     */
    protected function getFlashMessage(): ?array
    {
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            return $message;
        }
        return null;
    }
}
