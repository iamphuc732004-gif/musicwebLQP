<?php
class LikeController {
    public function toggle() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        header('Content-Type: application/json');
        if (!isset($_SESSION['user']['id'])) {
            echo json_encode([
                "status" => "error",
                "message" => "Chưa đăng nhập"
            ]);
            return;
        }
        $user_id = $_SESSION['user']['id'];
        $song_id = $_POST['song_id'] ?? null;
        if (!$song_id) {
            echo json_encode([
                "status" => "error",
                "message" => "Thiếu ID bài hát"
            ]);
            return;
        }
        require_once __DIR__ . "/../models/LikeModel.php";
        $model = new LikeModel();
        try {
            $result = $model->toggle($user_id, $song_id);
            echo json_encode($result);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }
}