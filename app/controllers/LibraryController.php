<?php
require_once "../app/models/MusicModel.php";
class LibraryController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user']['id'])) {
            header("Location: index.php?url=login");
            exit;
        }
        $user_id = $_SESSION['user']['id'];
        $model = new MusicModel();
        $songs = $model->getSongsByUser($user_id);
        $view = "../app/views/library.php";
        require_once "../app/views/layout.php";
    }
}