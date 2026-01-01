<?php
namespace Src\Controllers;

use Src\Database;

class GalleryController extends Controller {

    public function index() {
        $db = Database::getInstance();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 5; 
        $skip = ($page - 1) * $limit;

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
            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $this->render('upload_view', ['error' => 'Błąd przesyłania pliku.']); return;
            }

            $file = $_FILES['image'];
            $allowed = ['image/jpeg', 'image/png', 'image/pjpeg', 'image/jpg'];
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($file['tmp_name']);

            if ($file['size'] > 1024 * 1024 && !in_array($mime, $allowed)) { 
                $this->render('upload_view', ['error' => 'Plik za duży (max 1MB) oraz musi być w formacie JPG lub PNG.']); return;
            }else{
                if (!in_array($mime, $allowed)) {
                    $this->render('upload_view', ['error' => 'Tylko JPG i PNG.']); return;
                }
                if ($file['size'] > 1024 * 1024) { 
                $this->render('upload_view', ['error' => 'Plik za duży (max 1MB).']); return;
                }
            }

            $ext = ($mime === 'image/jpeg') ? '.jpg' : '.png';
            $fileName = uniqid() . $ext;
            
            $uploadDir = __DIR__ . '/../../images/';
            $thumbDir = __DIR__ . '/../../thumbnails/';

            if (move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
                $this->createThumbnail($uploadDir . $fileName, $thumbDir . $fileName, $mime, 200, 125);
                $author = isset($_SESSION['user_login']) ? $_SESSION['user_login'] : ($_POST['author'] ?? 'Anonim');
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

    public function addToCart() {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        
        if (isset($_POST['selected_ids']) && is_array($_POST['selected_ids'])) {
            foreach ($_POST['selected_ids'] as $id) {
                if($_POST['amounts'][$id] != '' && is_numeric($_POST['amounts'][$id]) && $_POST['amounts'][$id] > 0){
                    $_SESSION['cart'][$id] = $_POST['amounts'][$id] ?? 1;
                }
            }
        }
        $this->redirect('selected');
    }

    public function showSelected() {
        $db = Database::getInstance();
        $images = [];
        if (!empty($_SESSION['cart'])) {
            $cartIds = array_keys($_SESSION['cart']);
            $ids = array_map(function($id) { 
                return new \MongoDB\BSON\ObjectId($id); 
            }, $cartIds);
            $images = $db->images->find(['_id' => ['$in' => $ids]])->toArray();
        }
        $this->render('selected_view', ['images' => $images]);
    }

    public function clearCart() {
        if (isset($_POST['remove_ids']) && is_array($_POST['remove_ids'])) {
            foreach ($_POST['remove_ids'] as $id) {
                unset($_SESSION['cart'][$id]);
            }
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
        if ($this->is_ajax()) {
            $this->render('partial/search_results', ['images' => $images]);
        } else {
            $this->render('search_view', ['images' => $images]);
        }
    }
}