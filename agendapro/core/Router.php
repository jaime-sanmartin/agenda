<?php
// core/Router.php
class Router {
    private $routes = [];

    public function add($route, $controller, $action) {
        $this->routes[$route] = ['controller' => $controller, 'action' => $action];
    }

    public function dispatch($url) {
        $url = $this->sanitizeUrl($url);
        
        // Buscar en rutas exactas
        if (array_key_exists($url, $this->routes)) {
            $controllerName = $this->routes[$url]['controller'];
            $action = $this->routes[$url]['action'];
            
            $controllerFile = "controllers/" . $controllerName . ".php";
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                $controller = new $controllerName();
                if (method_exists($controller, $action)) {
                    $controller->$action();
                } else {
                    $this->show404();
                }
            } else {
                $this->show404();
            }
            return;
        }
        
        // Buscar rutas con parámetros
        // Formato: controller/action/id (ej: users/details/1)
        $parts = explode('/', $url);
        
        if (count($parts) >= 2) {
            // Intentar encontrar la ruta base (controller/action)
            $baseRoute = $parts[0] . '/' . $parts[1];
            
            // Verificar si la ruta base existe en las rutas registradas
            if (array_key_exists($baseRoute, $this->routes)) {
                $controllerName = $this->routes[$baseRoute]['controller'];
                $action = $this->routes[$baseRoute]['action'];
                $params = array_slice($parts, 2);
                
                $controllerFile = "controllers/" . $controllerName . ".php";
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    if (class_exists($controllerName)) {
                        $controller = new $controllerName();
                        if (method_exists($controller, $action)) {
                            // Llamar al método con los parámetros
                            call_user_func_array([$controller, $action], $params);
                            return;
                        }
                    }
                }
            }
        }
        
        // Si no se encontró ninguna ruta
        $this->show404();
    }
    
    private function sanitizeUrl($url) {
        // Eliminar parámetros GET y normalizar
        $url = strtok($url, '?');
        return trim($url, '/');
    }
    
    private function show404() {
        header("HTTP/1.0 404 Not Found");
        echo "404 - Página no encontrada";
        exit;
    }
}
?>