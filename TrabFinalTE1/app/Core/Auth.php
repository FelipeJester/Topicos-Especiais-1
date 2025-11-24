<?php

namespace App\Core;

class Auth
{
    /**
     * Verifica se o usuário está logado.
     * @return bool
     */
    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Tenta logar o usuário.
     * @param object $user Objeto do usuário retornado do banco de dados.
     */
    public static function login(object $user)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->nome;
        session_regenerate_id(true); // Regenera o ID da sessão para segurança
    }

    /**
     * Desloga o usuário.
     */
    public static function logout()
    {
        // Limpa todas as variáveis de sessão
        $_SESSION = [];

        // Invalida o cookie de sessão
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destrói a sessão
        session_destroy();
    }

    /**
     * Protege uma rota, redirecionando para o login se o usuário não estiver autenticado.
     */
    public static function protect()
    {
        if (!self::check()) {
            // Redireciona para a página de login
            header("Location: " . BASE_URL . "login");
            exit();
        }
    }
}
