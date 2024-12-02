<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class Controller {
    public function loadModel($model) {
        $modelPath = "../models/$model.php";
        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $model();
        }
        throw new Exception("Model $model not found at $modelPath");
    }

    public function loadView($view, $data = []) {
        $viewPath = "../views/$view.php";
        if (file_exists($viewPath)) {
            extract($data);
            require_once $viewPath;
        } else {
            throw new Exception("View $view not found at $viewPath");
        }
    }
}

