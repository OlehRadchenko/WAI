<?php
namespace Src\Controllers;
use Src\Database;

class UserController extends Controller {
    public function register() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('./');
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $login = $_POST['login'];
            $pass = $_POST['password'];
            $pass2 = $_POST['repeat_password'];

            if ($pass !== $pass2) { $this->render('register_view', ['error' => 'Hasła różne']); return; }

            $db = Database::getInstance();
            if ($db->users->findOne(['login' => $login])) {
                $this->render('register_view', ['error' => 'Login zajęty']); return;
            }
            if ($db->users->findOne(['email' => $email])) {
                $this->render('register_view', ['error' => 'Email jest już przypisany do innego użytkownika']); return;
            }

            $profilePhotoName = 'default.png';

            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['profile_image'];
                $allowed = ['image/jpeg', 'image/png', 'image/pjpeg', 'image/jpg'];
                
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($file['tmp_name']);

                if (in_array($mime, $allowed)) {
                    $ext = ($mime === 'image/jpeg') ? '.jpg' : '.png';
                    $profilePhotoName = 'profile_' . uniqid() . $ext;
                    $destPath = __DIR__ . '/../../ProfilesFoto/' . $profilePhotoName;

                    $this->createThumbnail($file['tmp_name'], $destPath, $mime, 100, 100);
                }
            }elseif (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
                $errorCode = $_FILES['profile_image']['error'];
                $msg = "Błąd przesyłania pliku (kod: $errorCode). Sprawdź rozmiar zdjęcia.";
                if ($errorCode == UPLOAD_ERR_INI_SIZE) $msg = "Plik przekracza limit serwera (upload_max_filesize).";
                
                $this->render('register_view', ['error' => $msg]);
                return;
            }

            $db->users->insertOne([
                'email' => $email,
                'login' => $login,
                'password' => password_hash($pass, PASSWORD_DEFAULT),
                'profile_photo' => $profilePhotoName
            ]);
            $this->redirect('login');
        }
        $this->render('register_view');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::getInstance();
            $user = $db->users->findOne(['login' => $_POST['login']]);

            if ($user && password_verify($_POST['password'], $user['password'])) {
                $_SESSION['user_id'] = (string)$user['_id'];
                $_SESSION['user_login'] = $user['login'];
                $_SESSION['profile_photo_path'] = '../ProfilesFoto/' . $user['profile_photo'];
                $this->redirect('./');
            } else {
                $this->render('login_view', ['error' => 'Błędne dane']);
            }
        } else if(!isset($_SESSION['user_id'])) {
            $this->render('login_view');
        } else {
            $this->redirect('./');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('./');
    }
}