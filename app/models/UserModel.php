<?php
require_once __DIR__ . "/../../config/database.php";
class UserModel {
    private $conn;
    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function register($username, $email, $password) {
        $role = "user";
        $stmt = $this->conn->prepare("
            INSERT INTO user_music (username, email, password, role)
            VALUES (?, ?, ?, ?)
        ");
        if (!$stmt) {
            die("Lỗi prepare: " . $this->conn->error);
        }
        $stmt->bind_param("ssss", $username, $email, $password, $role);
        return $stmt->execute();
    }

    public function login($username) {
        $stmt = $this->conn->prepare("
            SELECT * FROM user_music 
            WHERE username = ? OR email = ?
            LIMIT 1
        ");
        if (!$stmt) {
            die("Lỗi prepare: " . $this->conn->error);
        }

        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function checkUserExists($username, $email) {
        $stmt = $this->conn->prepare("
            SELECT id FROM user_music 
            WHERE username = ? OR email = ?
        ");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }

    public function getUserById($id) {
        $sql = "
            SELECT *
            FROM user_music
            WHERE id = ?
        ";
        $stmt = $this->conn->prepare($sql);
    
        if (!$stmt) {
            die("Lỗi prepare: " . $this->conn->error);
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt
            ->get_result()
            ->fetch_assoc();
    }
    public function getAllUsers() {
        $sql = "SELECT id, username, email, role FROM user_music";
        $result = $this->conn->query($sql);
    
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function deleteUser($id) {
        $stmt = $this->conn->prepare("
            DELETE FROM user_music WHERE id = ?
        ");
        if (!$stmt) {
            die("Lỗi prepare: " . $this->conn->error);
        }
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    public function getUserSongs($user_id) {
        $sql = "SELECT id, file, track, artist, type, image, user_id
                FROM music
                WHERE user_id = ?";
    
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("SQL ERROR: " . $this->conn->error);
        }
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}