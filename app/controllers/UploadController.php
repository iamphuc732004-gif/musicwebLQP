<?php
require_once "../app/models/MusicModel.php";
class UploadController {

    public function index() {
        $view = "../app/views/upload.php";
        require_once "../app/views/layout.php";
    }

    public function upload() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']['id'])) {
            header("Location: index.php?url=login");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user']['id'];
            $track  = $_POST['track'] ?? '';
            $artist = $_POST['artist'] ?? '';
            $type   = $_POST['type'] ?? '';

            if (!isset($_FILES['music']) || !isset($_FILES['image'])) {
                die("Thiếu file upload");
            }
            $musicName = time() . "_" . $_FILES['music']['name'];
            $imageName = time() . "_" . $_FILES['image']['name'];
            
            move_uploaded_file($_FILES['music']['tmp_name'],"../public/ok/music/" . $musicName);

            move_uploaded_file($_FILES['image']['tmp_name'],"../public/ok/images/" . $imageName);

            $model = new MusicModel();
            $model->insert($user_id,$musicName,$track,$artist,$type,$imageName);
            header("Location: index.php?url=home");
            exit;
        }
    }
}