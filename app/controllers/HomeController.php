<?php
require_once __DIR__ . "/../models/MusicModel.php";
require_once __DIR__ . "/../models/LikeModel.php";
require_once __DIR__ . "/../models/HistoryModel.php";
require_once __DIR__ . "/../models/PlaylistModel.php";
class HomeController {
    public function index() {
        $musicModel = new MusicModel();
        $likeModel = new LikeModel();
        $user_id = $_SESSION['user']['id'] ?? null;

        $songs = $musicModel->getAllSongs();

        $library_songs = [];
        $liked_songs = [];
        $liked_ids = []; 
        if ($user_id) {
            $library_songs = $musicModel->getSongsByUser($user_id);

            $liked_songs = $likeModel->getLikedSongs($user_id); 

            $liked_ids = array_column($liked_songs, 'id');
        }
        $playlistModel = new PlaylistModel();
        $playlists = [];
        if($user_id){
            $playlists = $playlistModel->getByUser($user_id);
        }
        $historySongs = [];
        if ($user_id) {
            $historyModel = new HistoryModel();
            $historySongs = $historyModel->getByUser($user_id);
        }
        $view = "../app/views/home.php";
        include "../app/views/layout.php";
    }
    public function library() {
        $model = new MusicModel();
        $songs = $model->getAllSongs();
        $view = "../app/views/library.php";
        include "../app/views/layout.php";
    }
    public function deleteSong(){
        $model = new MusicModel();
        $id = $_POST['id'];
        $result = $model->deleteSong($id);
        echo json_encode([
            "success" => $result
        ]);
    }
    public function editSong(){
        $model = new MusicModel();
        $id = $_GET['id'];
        if($_SERVER['REQUEST_METHOD']== 'POST'){
            $track = $_POST['track'];
            $artist = $_POST['artist'];
            $image = null;
            if(!empty($_FILES['image']['name'])){
                $image =
                    time() . "_" .
                    $_FILES['image']['name'];
                move_uploaded_file(
                    $_FILES['image']['tmp_name'],"../public/ok/images/" .$image
                );
            }
            $model->updateSong(
                $id,
                $track,
                $artist,
                $image
            );
            header("Location: " .BASE_URL);
            exit();
        }
        $song =$model->getSongById($id);
        $view ="../app/views/edit_song.php";
        include "../app/views/layout.php";
    }
}