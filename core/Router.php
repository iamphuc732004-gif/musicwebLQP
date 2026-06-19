<?php

require_once __DIR__ . "/../app/controllers/HomeController.php";
require_once __DIR__ . "/../app/controllers/AuthController.php";
require_once "../app/controllers/ProfileController.php";
require_once __DIR__ . "/../app/controllers/UploadController.php";
require_once __DIR__ . "/../app/controllers/LibraryController.php";
require_once __DIR__ . "/../app/controllers/PlaylistController.php";
require_once __DIR__ . "/../app/controllers/LikeController.php";
require_once __DIR__ . "/../app/controllers/SearchController.php";
require_once __DIR__ . '/../app/controllers/ArtistController.php';
require_once __DIR__ . "/../app/controllers/HistoryController.php";
require_once __DIR__ . "/../app/controllers/UpdateProfileController.php";
require_once __DIR__ . "/../app/controllers/ChatbotController.php";
require_once __DIR__ . "/../app/controllers/ManagerController.php";

$url = $_GET['url'] ?? 'home';

switch ($url) {

    case 'home':
        (new HomeController())->index();
        break;

    case 'library':
        (new LibraryController())->index();
        break;

    case 'login':
        (new AuthController())->login();
        break;

    case 'doLogin':
        (new AuthController())->doLogin();
        break;

    case 'register':
        (new AuthController())->register();
        break;

    case 'doRegister':
        (new AuthController())->doRegister();
        break;

    case 'upload':
        $controller = new UploadController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->upload();
        } else {
            $controller->index();
        }
        break;

    case 'logout':
        (new AuthController())->logout();
        break;
    case 'profile':
        $controller = new ProfileController();
        $controller->index();
        break;
    case 'playlist':
        $controller = new PlaylistController();
        $controller->index();
        break;
    case 'playlist_detail':
        $controller = new PlaylistController();
        $controller->detail();
        break;
    case 'playlist_songs':
        (new PlaylistController())->getSongsAjax();
        break;
    case "create_playlist":;
        $controller = new PlaylistController();
        $controller->create();
        break;
    case 'update_playlist':
        $controller = new PlaylistController();
        $controller->updatePlaylist();
        break;
    case 'delete_playlist':
        $controller = new PlaylistController();
        $controller->deletePlaylist();
        break;
    case "add_song_playlist":
            $controller = new PlaylistController();
            $controller->addSong();
        break;
    case 'remove_playlist_song':
        $controller = new ProfileController();
        $controller->removePlaylistSong();
        break;
    case 'like_toggle':
        (new LikeController())->toggle();
        break;
    case 'search':
        (new SearchController())->index();
        break;
    case "artist":
        $controller = new ArtistController();
        $controller->index();
        break;
    case "artist_detail": 
        $controller = new ArtistController();
        $controller->detail();
        break;
    case "delete_song":
        $controller = new HomeController();
        $controller->deletesong();
        break;
    case "edit_song":
        $controller =new HomeController();
        $controller->editSong();
        break;
    case "add_history":
        $controller = new HistoryController();
        $controller->add();
        break;
    case "updateProfile":
        $controller = new UpdateProfileController();
        $controller->updateProfile();
        break;
    case 'chatbot/send':
        require_once __DIR__ . "/../app/controllers/ChatbotController.php";
        $controller = new ChatbotController();
        $controller->send();
        break;
    case "manager":
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo "Bạn không có quyền truy cập!";
        exit;
        }
        $controller = new ManagerController();
        $controller->index();
        break;
    case "manager_delete":
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo json_encode(["status" => "no_permission"]);
        exit;
        }
        $controller = new ManagerController();
        $controller->delete($_GET['id']);
        break;
    case "manager_show":
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo "No permission";
        exit;
        }
        $controller = new ManagerController();
        $controller->show($_GET['id']);
        break;
    default:
        echo "404";
}