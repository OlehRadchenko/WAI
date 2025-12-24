<?php
namespace Src\Controllers;

use Src\Database;

class GalleryController extends Controller {

    public function index() {
        $db = Database::getInstance();
        
        // Paginacja
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 5; 
        $skip = ($page - 1) * $limit;

        // Logika wyświetlania: Publiczne LUB (Prywatne I Moje)
        $filter = ['$or' => [['access' => 'public']]];
        if (isset($_SESSION['user_id'])) {
            $filter['$or'][] = ['author_id' => $_SESSION['user_id']];
        }

        $options = ['limit' => $limit, 'skip' => $skip, 'sort' => ['_id' => -1]];
        $images = $db->images->find($filter, $options)->toArray();
        $total = $db->images->count($filter);

        $this->render('gallery_view', [
            'images' => $images, 
            'page' => $page, 
            'pages' => ceil($total / $limit)
        ]);
    }

    public function upload() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Walidacja pliku
            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $this->render('upload_view', ['error' => 'Błąd przesyłania pliku.']); return;
            }

            $file = $_FILES['image'];
            $allowed = ['image/jpeg', 'image/png'];
            
            // Wymóg: max 1MB [cite: 39]
            if ($file['size'] > 1024 * 1024) { 
                $this->render('upload_view', ['error' => 'Plik za duży (max 1MB).']); return;
            }

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($file['tmp_name']);
            
            // Wymóg: tylko PNG lub JPG [cite: 39]
            if (!in_array($mime, $allowed)) {
                $this->render('upload_view', ['error' => 'Tylko JPG i PNG.']); return;
            }

            // 2. Przetwarzanie ścieżek
            $ext = ($mime === 'image/jpeg') ? '.jpg' : '.png';
            $fileName = uniqid() . $ext;
            
            // Uwaga: folder web/ jest punktem wejścia, więc wychodzimy poziom wyżej (../)
            $uploadDir = __DIR__ . '/../../images/';
            $thumbDir = __DIR__ . '/../../thumbnails/';

            // 3. Zapis oryginału
            if (move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
                
                // 4. Generowanie miniatury 200x125 (Wymagane [cite: 45])
                $this->createThumbnail($uploadDir . $fileName, $thumbDir . $fileName, $mime);
                
                // TU BYŁ ZNAK WODNY - USUNIĘTO

                // 5. Zapis do bazy MongoDB
                $author = isset($_SESSION['user_login']) ? $_SESSION['user_login'] : ($_POST['author'] ?? 'Anonim');
                
                // Domyślnie publiczne, chyba że zaznaczono inaczej (dla zalogowanych)
                $access = (isset($_SESSION['user_id']) && isset($_POST['access'])) ? $_POST['access'] : 'public';

                Database::getInstance()->images->insertOne([
                    'name' => $fileName,
                    'title' => $_POST['title'],
                    'author' => $author,
                    'author_id' => $_SESSION['user_id'] ?? null,
                    'access' => $access
                ]);

                $this->redirect('/');
            }
        } else {
            $this->render('upload_view');
        }
    }

    // Funkcja pomocnicza tylko do miniatur
    private function createThumbnail($src, $dest, $mime) {
        $img = ($mime === 'image/jpeg') ? imagecreatefromjpeg($src) : imagecreatefrompng($src);
        $width = imagesx($img);
        $height = imagesy($img);

        $newW = 200; 
        $newH = 125;
        
        $thumb = imagecreatetruecolor($newW, $newH);
        
        // Kopiowanie ze skalowaniem
        imagecopyresampled($thumb, $img, 0, 0, 0, 0, $newW, $newH, $width, $height);
        
        ($mime === 'image/jpeg') ? imagejpeg($thumb, $dest) : imagepng($thumb, $dest);
        
        imagedestroy($thumb);
        imagedestroy($img);
    }

    // --- Reszta metod bez zmian ---
    
    public function addToCart() {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        
        if (isset($_POST['selected_ids']) && is_array($_POST['selected_ids'])) {
            foreach ($_POST['selected_ids'] as $id) {
                if (!in_array($id, $_SESSION['cart'])) {
                    $_SESSION['cart'][] = $id;
                }
            }
        }
        $this->redirect('selected');
    }

    public function showSelected() {
        $db = Database::getInstance();
        $images = [];
        if (!empty($_SESSION['cart'])) {
            // Zamiana stringów ID na ObjectId dla MongoDB
            $ids = array_map(function($id) { return new \MongoDB\BSON\ObjectId($id); }, $_SESSION['cart']);
            $images = $db->images->find(['_id' => ['$in' => $ids]])->toArray();
        }
        $this->render('selected_view', ['images' => $images]);
    }

    public function clearCart() {
        if (isset($_POST['remove_ids']) && is_array($_POST['remove_ids'])) {
             $_SESSION['cart'] = array_diff($_SESSION['cart'], $_POST['remove_ids']);
        }
        $this->redirect('selected');
    }

    public function search() {
        $q = $_POST['query'] ?? '';
        $db = Database::getInstance();
        $images = $db->images->find([
            'title' => ['$regex' => $q, '$options' => 'i'],
            'access' => 'public'
        ])->toArray();
        
        foreach ($images as $img) {
            echo '<div style="display:inline-block; margin:5px;">';
            echo '<img src="../thumbnails/'.$img['name'].'" style="width:100px"><br>';
            echo '<small>'.htmlspecialchars($img['title']).'</small>';
            echo '</div>';
        }
    }
}