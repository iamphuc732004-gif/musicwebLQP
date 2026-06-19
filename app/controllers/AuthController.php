<?php
require_once __DIR__ . "/../models/UserModel.php";
class AuthController {
    public function login() {
        $view = "../app/views/login.php";
        include "../app/views/layout.php";
    }
    public function register() {
        $view = "../app/views/register.php";
        include "../app/views/layout.php";
    }
    public function doLogin() {
        $model = new UserModel();
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
    
        $user = $model->login($username);
    
        if ($user && password_verify($password, $user['password'])) {
    
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role']
            ];
    
            if ($user['role'] === 'admin') {
                header("Location: ?url=manager");
            } else {
                header("Location: ?url=home");
            }
            exit;
    
        } else {
    
            $_SESSION['login_error'] = "Sai tài khoản hoặc mật khẩu!";
            header("Location: ?url=login");
            exit;
        }
    }
    public function doRegister() {
        $model = new UserModel();
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if (!$username || !$email || !$password) {
            echo "Vui lòng nhập đầy đủ thông tin!";
            return;
        }
        if ($model->checkUserExists($username, $email)) {
            echo "Username hoặc Email đã tồn tại!";
            return;
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        if ($model->register($username, $email, $hashedPassword)) {
            header("Location: ?url=login");
            exit;
        } else {
            echo "Đăng ký thất bại!";
        }
    }
    public function logout() {
        session_destroy();
        header("Location: ?url=home");
        exit;
    }
}