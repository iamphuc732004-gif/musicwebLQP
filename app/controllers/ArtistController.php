<?php
require_once __DIR__ . '/../models/MusicModel.php';
class ArtistController {
    private $musicModel;
    public function __construct() {
        $this->musicModel = new MusicModel();
    }
    public function index() {
        $artists = $this->musicModel->getArtists();
        $view = __DIR__ . '/../views/artist.php';
        include __DIR__ . '/../views/layout.php';
    }
    public function detail() {
        $artist = $_GET['name'] ?? '';
        $songs = $this->musicModel->getSongsByArtist($artist);
        $view = __DIR__ . '/../views/artist_detail.php';
        include __DIR__ . '/../views/layout.php';
    }
}