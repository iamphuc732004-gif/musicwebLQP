<?php
require_once __DIR__ . "/../models/PlaylistModel.php";
class PlaylistController {
    public function index() {
        $model = new PlaylistModel();
        $user_id = $_SESSION['user']['id'];
        $playlists = $model->getByUser($user_id);
        $view = "../app/views/playlists.php";
        include "../app/views/layout.php";
    }
    public function detail() {
        $model = new PlaylistModel();
        $playlist_id = $_GET['id'];
        $songs = $model->getSongs($playlist_id);
        $view = "../app/views/playlist_detail.php";
        include "../app/views/layout.php";
    }
    public function getSongsAjax() {
        header('Content-Type: application/json');
        $playlist_id = $_GET['id'] ?? 0;
        require_once __DIR__ . "/../models/PlaylistModel.php";
        $model = new PlaylistModel();
        $songs = $model->getSongs($playlist_id);
        echo json_encode($songs);
    }
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = new PlaylistModel();
            $user_id = $_SESSION['user']['id'];
            $name = $_POST['name'];
            $imageName = "default-playlist.jpg";
            
            if(isset($_FILES['image']) &&
               $_FILES['image']['error'] == 0){
                $ext = pathinfo(
                    $_FILES['image']['name'],
                    PATHINFO_EXTENSION
                );
                $imageName =
                    time() . "_" .
                    rand(1000,9999) .
                    "." . $ext;
                move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    __DIR__ . "/../../public/ok/images/" . $imageName
                );
            }
            $model->create(
                $user_id,
                $name,
                $imageName
            );
            header("Location: index.php?url=profile");
        }
    }
    public function addSong(){
        header('Content-Type: application/json');
        $playlist_id = $_POST['playlist_id'];
        $song_id = $_POST['song_id'];
        $model = new PlaylistModel();
        $result = $model->addSong(
            $playlist_id,
            $song_id
        );
        echo json_encode([
            "success" => $result
        ]);
    }
    public function updatePlaylist(){

        $id = $_POST['playlist_id'];
        $name = $_POST['name'];
    
        $image = null;
    
        // upload ảnh mới
        if(!empty($_FILES['image']['name'])){
    
            $image = time() . '_' . $_FILES['image']['name'];
    
            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                'ok/images/' . $image
            );
        }
    
        $model = new PlaylistModel();
    
        $model->updatePlaylist($id, $name, $image);
    
        header("Location: " . BASE_URL . "profile");
    }
    public function deletePlaylist(){

        $id = $_GET['id'];
    
        $model = new PlaylistModel();
    
        $model->deletePlaylist($id);
    
        header("Location: " . BASE_URL . "index.php?url=profile");
    }
}