<?php
class LikeModel {
    private $conn;
    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "musicweb");
    }
    public function getLikedSongs($user_id) {
        $sql = "SELECT m.* 
                FROM likes l
                JOIN music m ON l.song_id = m.id
                WHERE l.user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function toggle($user_id, $song_id) {

        $sql = "SELECT id FROM likes WHERE user_id=? AND song_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $song_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {

            $sql = "DELETE FROM likes WHERE user_id=? AND song_id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $song_id);
            $stmt->execute();
            return [
                "status" => "unliked",
                "song_id" => $song_id
            ];
        } else {
            $sql = "INSERT INTO likes(user_id, song_id) VALUES (?,?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $song_id);
            $stmt->execute();

            $sql = "SELECT * FROM music WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $song_id);
            $stmt->execute();
            $song = $stmt->get_result()->fetch_assoc();
            return [
                "status" => "liked",
                "song" => $song
            ];
        }
    }
}