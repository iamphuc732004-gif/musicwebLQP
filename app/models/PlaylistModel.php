<?php
class PlaylistModel {
    private $conn;
    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "musicweb");
    }

    public function getByUser($user_id) {
        $sql = "SELECT * FROM playlist WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function create($user_id, $name, $image)
    {
        $sql = "INSERT INTO playlist
                (user_id, name, playlist_img, created_at)
                VALUES (?, ?, ?, NOW())";
    
        $stmt = $this->conn->prepare($sql);
    
        $stmt->bind_param(
            "iss",
            $user_id,
            $name,
            $image
        );
    
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
    
        return false;
    }
    public function createAIPlaylist($user_id, $name, $image = 'default_playlist.jpg')
{
    $sql = "INSERT INTO playlist
            (user_id, name, playlist_img, created_at)
            VALUES (?, ?, ?, NOW())";

    $stmt = $this->conn->prepare($sql);

    $stmt->bind_param(
        "iss",
        $user_id,
        $name,
        $image
    );

    if ($stmt->execute()) {
        return $this->conn->insert_id;
    }

    return false;
}
    public function getSongs($playlist_id) {
        $sql = "SELECT m.* FROM playlist_songs ps
                JOIN music m ON ps.song_id = m.id
                WHERE ps.playlist_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $playlist_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addSong($playlist_id, $song_id) {
        $sql = "INSERT INTO playlist_songs (playlist_id, song_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $playlist_id, $song_id);
        return $stmt->execute();
    }
    public function updatePlaylist($id, $name, $image = null){

        if($image){
    
            $sql = "UPDATE playlist
                    SET name = ?, playlist_img = ?
                    WHERE id = ?";
    
            $stmt = $this->conn->prepare($sql);
    
            $stmt->bind_param("ssi", $name, $image, $id);
    
        }else{
    
            $sql = "UPDATE playlist
                    SET name = ?
                    WHERE id = ?";
    
            $stmt = $this->conn->prepare($sql);
    
            $stmt->bind_param("si", $name, $id);
        }
    
        return $stmt->execute();
    }
    public function deletePlaylist($id){

        $sql = "DELETE FROM playlist WHERE id=?";
    
        $stmt = $this->conn->prepare($sql);
    
        $stmt->bind_param("i", $id);
    
        return $stmt->execute();
    }
    public function removeSong($playlistId, $songId)
{
    $sql = "DELETE FROM playlist_songs
            WHERE playlist_id = ? AND song_id = ?";

    $stmt = $this->conn->prepare($sql);

    $stmt->bind_param("ii", $playlistId, $songId);

    return $stmt->execute();
}
}