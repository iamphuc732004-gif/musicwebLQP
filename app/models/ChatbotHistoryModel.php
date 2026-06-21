<?php
require_once "../config/database.php";

class ChatbotHistoryModel
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }
    public function save($userId, $question, $answer)
    {
        $sql = "INSERT INTO chatbot_history (user_id, question, answer)
                VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iss", $userId, $question, $answer);

        return $stmt->execute();
    }
}