<?php
require_once "../app/models/MusicModel.php";
require_once "../app/models/PlaylistModel.php";
require_once "../app/models/ChatbotHistoryModel.php";
class ChatbotController {
    private $musicModel;
    private $historyModel;
    public function __construct(){
        $this->musicModel = new MusicModel();
        $this->historyModel = new ChatbotHistoryModel();
    }
    public function send(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        header("Content-Type: application/json; charset=utf-8");
        $input = json_decode(file_get_contents("php://input"), true);
        $message = trim($input['message'] ?? '');
        if(empty($message)){
            echo json_encode([
                "success" => false,
                "reply" => "Bạn chưa nhập tin nhắn 😅"
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
        try {
            $allSongs = $this->musicModel->getAllSongs(); 
            if (!is_array($allSongs) || empty($allSongs)) {
                echo json_encode([
                    "success" => true,
                    "reply"   => "⚠️ Lỗi: Không lấy được nhạc từ Database! Hãy kiểm tra lại kết nối CSDL hoặc hàm getAllSongs().",
                    "songs"   => []
                ], JSON_UNESCAPED_UNICODE);
                return;
            }
            $allSongs = array_slice($allSongs, 0, 25); 
            $songListForAI = "";
            foreach ($allSongs as $s) {
                $songListForAI .= "ID: " . $s['id'] . " - Tên: " . $s['track'] . " - Ca sĩ: " . $s['artist'] . " - Thể loại: " . ($s['type'] ?? 'nonstop') . "\n";
            }
            $aiData = $this->askGeminiAdvanced($message, $songListForAI);
            $reply = $aiData['reply'];
            $userId = $_SESSION['user']['id'] ?? 0;
            $result = $this->historyModel->save(
                $userId,
                $message,
                $reply
            );
            $songIds = $aiData['song_ids'] ?? [];
            $recommendedSongs = [];
            if (!empty($songIds) && is_array($songIds)) {
                foreach ($songIds as $songId) {
                    $cleanId = intval($songId);
                    if ($cleanId > 0) {
                        $songInfo = $this->musicModel->getSongById($cleanId);
                        if ($songInfo) {
                            $songInfo['file'] = "../public/ok/music/" . $songInfo['file'];
                            $songInfo['image'] = "../public/ok/images/" . $songInfo['image'];
                            $recommendedSongs[] = $songInfo;
                        }
                    }
                }
            }
            echo json_encode([
                "success"       => true,
                "reply"         => $reply,
                "songs"         => $recommendedSongs,
                "song_ids"      => $songIds,
                "action"        => $aiData['action'] ?? 'none',
                "playlist_name" => $aiData['playlist_name'] ?? '',
                "playlist"      => $aiData['playlist'] ?? null
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode([
                "success"       => true,
                "reply"         => "⚠️ Hệ thống xảy ra lỗi PHP Exception: " . $e->getMessage(),
                "songs"         => [],
                "song_ids"      => [],
                "action"        => "none",
                "playlist_name" => ""
            ], JSON_UNESCAPED_UNICODE);
        }
    }
    private function askGeminiAdvanced($message, $songListForAI){
        $apiKey = "";
        if (!isset($_SESSION['chat_history'])) {
            $_SESSION['chat_history'] = [];
        }
        $_SESSION['chat_history'][] = [
            "role" => "user",
            "parts" => [["text" => $message]]
        ];
        if (count($_SESSION['chat_history']) > 10) {
            $_SESSION['chat_history'] = array_slice($_SESSION['chat_history'], -10);
        }
        $systemInstruction = "Bạn là Music AI - một người bạn tri kỷ, am hiểu âm nhạc và cực kỳ tâm lý. Nhiệm vụ của bạn là trò chuyện với người dùng một cách hoàn toàn tự nhiên, chân thật, tinh tế, có chiều sâu cảm xúc (Sử dụng icon phù hợp). "
                           . "Dựa vào nội dung cuộc trò chuyện và danh sách kho nhạc được cung cấp dưới đây, hãy lựa chọn ra từ 1 đến 5 bài hát phù hợp nhất với tâm trạng hoặc yêu cầu của họ. "
                           . "Nếu họ muốn nghe nhạc, buồn, vui, hoặc yêu cầu bài hát, bạn hãy chọn ra các ID phù hợp từ danh sách bên dưới và đưa vào mảng 'song_ids'.\n\n"
                           . "Nếu người dùng muốn nghe nhạc, hãy chọn từ 1 đến 5 bài hát phù hợp và đưa ID vào song_ids."
                           . "Nếu người dùng yêu cầu tạo playlist, danh sách phát hoặc tuyển tập nhạc theo chủ đề:
                           + Chọn từ 5 đến 20 bài hát phù hợp.
                           + Đưa ID các bài hát vào song_ids.
                           + Đặt action = 'create_playlist'.
                           + Tạo playlist_name ngắn gọn, phù hợp với chủ đề."
                           ."\nNếu người dùng yêu cầu phát nhạc:
                           + action = 'play'"
                           ."\nNếu người dùng yêu cầu tạm dừng:
                           + action = 'pause'"
                           ."\nNếu người dùng yêu cầu chuyển bài:
                           + action = 'next'"
                           ."\nNếu người dùng yêu cầu quay lại bài trước:
                           + action = 'previous'"
                           . "\nNếu chỉ trò chuyện bình thường:
                           + action = 'none'"

                           . "DANH SÁCH KHO NHẠC HIỆN CÓ CỦA WEBSITE:\n" . $songListForAI;
        $data = [
            "systemInstruction" => [
                "parts" => [["text" => $systemInstruction]]
            ],
            "contents" => $_SESSION['chat_history'],
            "generationConfig" => [
                "responseMimeType" => "application/json",
                "responseSchema" => [
                    "type" => "object",
                    "properties" => [
                        "reply" => [
                            "type" => "string",
                            "description" => "Câu trả lời trò chuyện tự nhiên, đồng cảm, mượt mà."
                        ],
                        "song_ids" => [
                            "type" => "array",
                            "items" => ["type" => "integer"],
                            "description" => "Mảng chứa các ID bài hát phù hợp. Để rỗng [] nếu chỉ trò chuyện suông."
                        ],
                        "action" => [
                            "type" => "string",
                            "enum" => [
                                "none",
                                "play",
                                "pause",
                                "next",
                                "previous",
                                "create_playlist"
                            ],
                            "description" => "Lệnh điều khiển trình phát hoặc tạo playlist."
                        ],
                        "playlist_name" => [
                            "type" => "string",
                            "description" => "Tên playlist nếu action=create_playlist, ngược lại để chuỗi rỗng."
                        ]
                    ],
                    "required" => ["reply", "song_ids", "action","playlist_name"]
                ]
            ]
        ];
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key="
        . $apiKey;
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 30
        ]);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            return [
                "reply" => "⚠️ Lỗi cURL (Không kết nối được Google): " . $err,
                "song_ids" => []
            ];
        }
        curl_close($ch);
        $result = json_decode($response, true);
        if (isset($result['error'])) {
            return [
                "reply" => "⚠️ Lỗi Google API: " . ($result['error']['message'] ?? 'Không xác định') . " (Mã: " . ($result['error']['code'] ?? 'Unknown') . ")",
                "song_ids" => []
            ];
        }
        $rawText = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
        if ($rawText) {
            $rawText = trim($rawText);
            $rawText = str_replace("```json", "", $rawText);
            $rawText = str_replace("```", "", $rawText);
            $rawText = trim($rawText);
            $aiResponseData = json_decode($rawText, true);
            if (isset($aiResponseData['reply'])) {

                $playlistData = null;
            
                $_SESSION['chat_history'][] = [
                    "role" => "model",
                    "parts" => [["text" => $rawText]]
                ];
            
                if (
                    isset($aiResponseData['action']) &&
                    $aiResponseData['action'] === 'create_playlist'
                ) {
            
                    $playlistModel = new PlaylistModel();
            
                    $playlistId = $playlistModel->create(
                        $_SESSION['user']['id'],
                        $aiResponseData['playlist_name'] ?? 'AI Playlist',
                        'default_playlist.jpg'
                    );
            
                    if ($playlistId) {
            
                        foreach (($aiResponseData['song_ids'] ?? []) as $songId) {
            
                            $playlistModel->addSong(
                                $playlistId,
                                $songId
                            );
                        }
            
                        $playlistData = [
                            "id" => $playlistId,
                            "name" => $aiResponseData['playlist_name'] ?? 'AI Playlist',
                            "image" => BASE_URL . "ok/images/default_playlist.jpg"
                        ];
                    }
                }
            
                return [
                    "reply" => $aiResponseData['reply'],
                    "song_ids" => $aiResponseData['song_ids'] ?? [],
                    "action" => $aiResponseData['action'] ?? 'none',
                    "playlist_name" => $aiResponseData['playlist_name'] ?? '',
                    "playlist" => $playlistData
                ];
            
            } else {
            
                return [
                    "reply" => "⚠️ Lỗi giải mã JSON từ AI: " . htmlspecialchars($rawText),
                    "song_ids" => [],
                    "action" => "none",
                    "playlist_name" => "",
                    "playlist" => null
                ];
            }
        }
        return [
            "reply" => "⚠️ Lỗi: Google phản hồi rỗng. Raw response: " . htmlspecialchars($response),
            "song_ids" => []
        ];
    }
    private function getFallbackSongs() {
        $songs = $this->musicModel->getRecommendSongs("nonstop");
        return array_slice($songs, 0, 3);
    }
}