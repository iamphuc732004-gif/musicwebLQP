<?php
require_once __DIR__ . "/../../config/database.php";
class UpdateProfileModel{
    private $conn;
    public function __construct(){
        $database = new Database();
        $this->conn = $database->connect();
    }
    public function getUserById($id){
        $sql = "SELECT * FROM user_music WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt
            ->get_result()
            ->fetch_assoc();
    }
    public function updateUserWithoutPassword(
        $id,
        $username,
        $email
    ){
        $sql = "UPDATE user_music
                SET username = ?,
                    email = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "ssi",
            $username,
            $email,
            $id
        );
        return $stmt->execute();
    }
    public function updateUserWithPassword(
        $id,
        $username,
        $email,
        $password
    ){
        $sql = "UPDATE user_music
                SET username = ?,
                    email = ?,
                    password = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sssi",
            $username,
            $email,
            $password,
            $id
        );
        return($stmt->execute());
    }
}