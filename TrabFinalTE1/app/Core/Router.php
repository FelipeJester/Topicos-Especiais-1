<?php

namespace App\Core;

class Router
{
    protected $routes = [];

    public function __construct()
    {
        // Define as rotas iniciais
        $this->defineRoutes();
    }

    private function defineRoutes()
    {
        // Rotas da Aplicação
        // Formato: 'URL' => ['Controller', 'metodo']

        // Rotas Públicas
        $this->routes['/'] = ['Home', 'index'];
        $this->routes['/login'] = ['Auth', 'login'];
        $this->routes['/logout'] = ['Auth', 'logout'];
        $this->routes['/carros'] = ['Carro', 'index']; // Listagem pública de carros

        // Rotas Administrativas (Protegidas por Login)
        $this->routes['/admin'] = ['Admin', 'index'];
        $this->routes['/admin/carros/create'] = ['Carro', 'create'];
        $this->routes['/admin/carros/edit/{id}'] = ['Carro', 'edit'];
        $this->routes['/admin/carros/delete/{id}'] = ['Carro', 'delete'];
        $this->routes['/admin/carros/store'] = ['Carro', 'store'];
        $this->routes['/admin/carros/update'] = ['Carro', 'update'];
        $this->routes['/admin/carros/delete'] = ['Carro', 'delete'];
        
        // Rota para o toggle do modo escuro (pode ser acessada de qualquer lugar)
        $this->routes['/toggle-theme'] = ['Theme', 'toggle'];
    }

    public function run()
    {
        $uri = $this->getUri();
        $method = $_SERVER['REQUEST_METHOD'];

        // Tenta encontrar uma rota exata
        if (isset($this->routes[$uri])) {
            $route = $this->routes[$uri];
            $controllerName = 'App\\Controllers\\' . $route[0] . 'Controller';
            $methodName = $route[1];
            $params = [];
        } else {
            // Se não for uma rota exata, tenta encontrar rotas com parâmetros (ex: /admin/carros/edit/1)
            $found = false;
            foreach ($this->routes as $routeUri => $route) {
                // Converte a rota definida para uma regex
                $pattern = preg_replace('/\//', '\\/', $routeUri);
                $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $pattern);
                $pattern = '/^' . $pattern . '$/';

                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches); // Remove a correspondência completa
                    $controllerName = 'App\\Controllers\\' . $route[0] . 'Controller';
                    $methodName = $route[1];
                    $params = $matches;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                // Trata 404
                header("HTTP/1.0 404 Not Found");
                echo "<h1>404 Not Found</h1>";
                return;
            }
        }

        // Verifica se o controller e o método existem
        if (class_exists($controllerName) && method_exists($controllerName, $methodName)) {
            $controller = new $controllerName();
            call_user_func_array([$controller, $methodName], $params);
        } else {
            // Trata erro interno
            header("HTTP/1.0 500 Internal Server Error");
            echo "<h1>500 Internal Server Error</h1>";
            echo "<p>Controller ou método não encontrado: {$controllerName}::{$methodName}</p>";
        }
    }

    private function getUri()
    {
        // Obtém a URI e remove a base do diretório se houver
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = str_replace(BASE_URL, '', $uri);
        $uri = '/' . trim($uri, '/');

        // Remove o index.php se estiver presente (pode ocorrer em algumas configurações de servidor)
        if (strpos($uri, '/index.php') === 0) {
            $uri = substr($uri, strlen('/index.php'));
        }
        
        // Garante que a URI não termine com barra, exceto para a raiz
        if ($uri !== '/' && substr($uri, -1) === '/') {
            $uri = substr($uri, 0, -1);
        }

        return $uri;
    }
}
