<?php
require_once "../app/models/MusicModel.php";
class SearchController{
    public function index(){
        $keyword = $_GET['keyword'] ?? '';
        $musicModel = new MusicModel();
        $songs = $musicModel->searchSongs($keyword);
        header('Content-Type: application/json');
        echo json_encode($songs);
    }
}