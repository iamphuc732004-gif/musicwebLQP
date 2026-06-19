<?php
class HistoryModel {
    private $conn;
    public function __construct() {
        $this->conn = new mysqli(
            "localhost",
            "root",
            "",
            "musicweb"
        );
    }

    public function add($user_id, $song_id){
        $sql = "
            INSERT INTO history(
                user_id,
                song_id
            )
            VALUES(?, ?)
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "ii",
            $user_id,
            $song_id
        );
        return $stmt->execute();
    }

    public function getByUser($user_id){
        $sql = "
            SELECT
                m.*,
                MAX(h.played_at) as played_at
            FROM history h
            JOIN music m
            ON h.song_id = m.id
            WHERE h.user_id = ?
            GROUP BY h.song_id
            ORDER BY played_at DESC
            LIMIT 20
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "i",
            $user_id
        );
        $stmt->execute();
        return $stmt
            ->get_result()
            ->fetch_all(MYSQLI_ASSOC);
    }
}