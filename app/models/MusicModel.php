<?php
require_once "../config/database.php";
class MusicModel {
    private $conn;
    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAllSongs() {
        $sql = "SELECT id, file, track, artist, type, image FROM music ORDER BY id DESC";
        $result = $this->conn->query($sql);
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getSongById($id){
        $sql = "SELECT * FROM music WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getRecommendSongs($genre = ""){
        $genre = trim(strtolower($genre));

        if(!empty($genre)){
            $sql = "SELECT * FROM music WHERE LOWER(type) LIKE ? ORDER BY RAND() LIMIT 10";
            $stmt = $this->conn->prepare($sql);
            $search = "%" . $genre . "%";
            $stmt->bind_param("s", $search);
        } else {
            $sql = "SELECT * FROM music ORDER BY RAND() LIMIT 10";
            $stmt = $this->conn->prepare($sql);
        }
        $stmt->execute();
        $songs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        foreach($songs as &$song){
            $song['file'] = "../public/ok/music/" . $song['file'];
            $song['image'] = "../public/ok/images/" . $song['image'];
        }
        unset($song); 

        return $songs;
    }

    public function insert($user_id, $file, $track, $artist, $type, $image) {
        $sql = "INSERT INTO music (user_id, file, track, artist, type, image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isssss", $user_id, $file, $track, $artist, $type, $image);
        return $stmt->execute();
    }

    public function getSongsByUser($user_id) {
        $sql = "SELECT * FROM music WHERE user_id = ? ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function searchSongs($keyword){
        $sql = "SELECT * FROM music WHERE track LIKE ? OR artist LIKE ?";
        $stmt = $this->conn->prepare($sql);
        $search = "%" . $keyword . "%";
        $stmt->bind_param("ss", $search, $search);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getArtists() {
        $sql = "SELECT DISTINCT artist FROM music";
        $result = $this->conn->query($sql);
        $artists = [];
        while($row = $result->fetch_assoc()) {
            $artists[] = $row;
        }
        return $artists;
    }

    public function getSongsByArtist($artist) {
        $sql = "SELECT * FROM music WHERE artist = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $artist);
        $stmt->execute();
        $result = $stmt->get_result();
        $songs = [];
        while($row = $result->fetch_assoc()) {
            $songs[] = $row;
        }
        return $songs;
    }

    public function deleteSong($id){
        $sql = "DELETE FROM music WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getLikedSongs($user_id){
        $sql = "SELECT m.* FROM likes l JOIN music m ON l.song_id = m.id WHERE l.user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function updateSong($id, $track, $artist, $image){
        if($image){
            $sql = "UPDATE music SET track=?, artist=?, image=? WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssi", $track, $artist, $image, $id);
        }else{
            $sql = "UPDATE music SET track=?, artist=? WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssi", $track, $artist, $id);
        }
        return $stmt->execute();
    }
}