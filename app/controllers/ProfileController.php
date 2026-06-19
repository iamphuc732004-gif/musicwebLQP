<?php
require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../models/MusicModel.php";
require_once __DIR__ . "/../models/PlaylistModel.php";
class ProfileController {
    public function index() {

        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "index.php?url=login");
            exit();
        }
        $userModel = new UserModel();
        $musicModel = new MusicModel();
        $playlistModel = new PlaylistModel();

        if (isset($_GET['id'])) {
            $user_id = $_GET['id'];
            $user = $userModel->getUserById($user_id);
        } else {

            $user_id = $_SESSION['user']['id'];
            $user = $_SESSION['user'];
        }
        if (!$user) {
            echo "Không tìm thấy người dùng!";
            return;
        }

        $songs = $musicModel->getSongsByUser($user_id);

        $playlists = $playlistModel->getByUser($user_id);

        $liked_songs = $musicModel->getLikedSongs($user_id);

        $liked_ids = array_column($liked_songs, 'id');

        $view = "../app/views/profile.php";
        include "../app/views/layout.php";
    }
    public function removePlaylistSong(){
    $playlistId = $_POST['playlist_id'];
    $songId = $_POST['song_id'];

    $playlistModel = new PlaylistModel();

    $success = $playlistModel->removeSong($playlistId, $songId);

    echo json_encode([
        'success' => $success
    ]);
}
}