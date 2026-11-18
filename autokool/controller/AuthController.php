<?php
// controller/AuthController.php
require_once __DIR__ . '/../model/UserModel.php';

class AuthController {
    public function showLogin(): void {
        if (isLoggedIn()) {
            redirect(isAdmin() ? '/login/admin' : '/profile');
        }

        renderView('login', [
            'title' => 'Sisselogimine',
            'error' => $_SESSION['login_error'] ?? null,
            'success' => $_SESSION['login_success'] ?? null,
            'actionUrl' => '/login'
        ]);

        unset($_SESSION['login_error'], $_SESSION['login_success']);
    }

    public function login(): void {
        global $pdo;
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $userModel = new UserModel($pdo);
        $user = $userModel->verifyPassword($username, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];

            if ($user['role'] === 'admin') {
                redirect('/login/admin');
            } else {
                redirect('/profile');
            }
        } else {
            $_SESSION['login_error'] = "Vale kasutajanimi või parool.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/login');
        }
    }

    public function showRegister(): void {
        if (isLoggedIn()) {
            redirect('/profile');
        }

        renderView('register', [
            'title' => 'Registreerimine',
            'error' => $_SESSION['register_error'] ?? null
        ]);

        unset($_SESSION['register_error']);
    }

    public function register(): void {
        global $pdo;
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $passwordConfirm = trim($_POST['password_confirm'] ?? '');

        if (empty($username) || empty($password)) {
            $_SESSION['register_error'] = "Palun täitke kõik väljad.";
            redirect('/register');
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['register_error'] = "Paroolid ei kattu.";
            redirect('/register');
        }

        $userModel = new UserModel($pdo);
        if ($userModel->findByUsername($username)) {
            $_SESSION['register_error'] = "See kasutajanimi on juba hõivatud.";
            redirect('/register');
        }

        if ($userModel->create($username, $password, 'user')) {
            $user = $userModel->findByUsername($username);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = 'user';
            redirect('/profile');
        } else {
            $_SESSION['register_error'] = "Registreerimisel tekkis viga.";
            redirect('/register');
        }
    }

    public function logout(): void {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        redirect('/login');
    }
}
