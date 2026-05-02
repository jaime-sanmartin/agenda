<?php
// core/Controller.php
class Controller {
    protected function view($view, $data = []) {
        extract($data);
        $viewFile = "views/" . $view . ".php";
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Vista no encontrada: " . $view);
        }
    }

    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($url) {
        header("Location: " . $url);
        exit;
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    protected function getPost($key, $default = null) {
        return isset($_POST[$key]) ? Security::sanitize($_POST[$key]) : $default;
    }

    protected function getQuery($key, $default = null) {
        return isset($_GET[$key]) ? Security::sanitize($_GET[$key]) : $default;
    }
}
?>