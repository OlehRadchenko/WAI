<?php
namespace Src\Controllers;
use Src\Database;

class UserController extends Controller {
    public function register() {
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

            $db->users->insertOne([
                'email' => $email,
                'login' => $login,
                'password' => password_hash($pass, PASSWORD_DEFAULT) // [cite: 69]
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
                $_SESSION['user_login'] = $user['login']; // [cite: 74]
                $this->redirect('/');
            } else {
                $this->render('login_view', ['error' => 'Błędne dane']);
            }
        } else {
            $this->render('login_view');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('/');
    }
}