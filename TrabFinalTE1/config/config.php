<?php

// Configurações do Banco de Dados (PostgreSQL)
define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_NAME', getenv('DB_NAME') ?: 'concessionaria_db');
define('DB_USER', getenv('DB_USER') ?: 'user_concessionaria');
define('DB_PASS', getenv('DB_PASSWORD') ?: 'password_concessionaria');
define('DB_PORT', '5432');

// Configurações da Aplicação
define('APP_NAME', 'Concessionária MVC PHP Puro');
define('BASE_URL', '/'); // Usado para links internos

// Configurações de Upload
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('ALLOWED_MIMES', ['image/png', 'image/jpeg', 'image/jpg']);
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB

// Configurações de Sessão
define('SESSION_NAME', 'concessionaria_session');

// Configurações de Cookie (Modo Escuro)
define('COOKIE_NAME_THEME', 'theme_mode');
define('COOKIE_EXPIRY_THEME', time() + (86400 * 30)); // 30 dias
define('DEFAULT_THEME', 'light');

// Configurações de Debug
define('DEBUG_MODE', true);

// Inicia a sessão
session_name(SESSION_NAME);
session_start();

// Configura o fuso horário
date_default_timezone_set('America/Sao_Paulo');

// Função de tratamento de erros
if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
