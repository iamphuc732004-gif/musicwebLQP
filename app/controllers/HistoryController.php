<?php
require_once __DIR__ . "/../models/HistoryModel.php";
class HistoryController {
    public function add(){
        header("Content-Type: application/json");
        if(!isset($_SESSION['user'])){
            echo json_encode([
                "success" => false
            ]);
            return;
        }
        $user_id =
            $_SESSION['user']['id'];
        $song_id =
            $_POST['song_id'] ?? 0;
        $model = new HistoryModel();
        $model->add(
            $user_id,
            $song_id
        );
        echo json_encode([
            "success" => true
        ]);
    }
}