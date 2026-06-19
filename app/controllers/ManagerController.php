<?php
require_once __DIR__ . "/../models/UserModel.php";
class ManagerController {
    private $userModel;
    public function __construct() {
        $this->userModel = new UserModel();
    }
    public function index() {
        $users = $this->userModel->getAllUsers();
        require_once __DIR__ . "/../views/manager.php";
    }
    public function delete($id) {
        header('Content-Type: application/json');
        $result = $this->userModel->deleteUser($id);
        echo json_encode([
            "status" => $result ? "success" : "error"
        ]);
    }
    public function show($id) {
        $user = $this->userModel->getUserById($id);
        $songs = $this->userModel->getUserSongs($id);
        require_once __DIR__ . "/../views/user_details.php";
    }
}