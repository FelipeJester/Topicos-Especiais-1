<?php

namespace App\Controllers;

use App\Core\Controller;

class ThemeController extends Controller
{
    /**
     * Alterna o tema entre 'light' e 'dark' e salva a preferência em um cookie.
     */
    public function toggle()
    {
        // 1. Determina o tema atual (lendo o cookie ou usando o padrão)
        $currentTheme = $_COOKIE[COOKIE_NAME_THEME] ?? DEFAULT_THEME;

        // 2. Define o novo tema
        $newTheme = ($currentTheme === 'dark') ? 'light' : 'dark';

        // 3. Define o cookie com o novo tema
        // O cookie expira em 30 dias (COOKIE_EXPIRY_THEME)
        setcookie(COOKIE_NAME_THEME, $newTheme, COOKIE_EXPIRY_THEME, BASE_URL, '', false, true);

        // 4. Redireciona o usuário de volta para a página anterior
        $referrer = $_SERVER['HTTP_REFERER'] ?? BASE_URL;
        header("Location: " . $referrer);
        exit();
    }
}
