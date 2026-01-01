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
        header("Location: " . $url);
        exit;
    }

    protected function createThumbnail($src, $dest, $mime, $width, $height) {
        $img = ($mime === 'image/jpeg') ? imagecreatefromjpeg($src) : imagecreatefrompng($src);
        $currWidth = imagesx($img);
        $currHeight = imagesy($img);
        
        $thumb = imagecreatetruecolor($width, $height);
        
        imagecopyresampled($thumb, $img, 0, 0, 0, 0, $width, $height, $currWidth, $currHeight);
        
        ($mime === 'image/jpeg') ? imagejpeg($thumb, $dest) : imagepng($thumb, $dest);
        
        imagedestroy($thumb);
        imagedestroy($img);
    }

    protected function is_ajax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}