<?php
namespace Src\Controllers;

abstract class Controller {
    protected function render($view_name, $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../../src/views/' . $view_name . '.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo "Błąd: Brak widoku $view_name";
        }
    }

    protected function redirect($url) {
        // Obsługa przekierowań wewnętrznych
        header("Location: index.php?action=" . $url);
        exit;
    }
}