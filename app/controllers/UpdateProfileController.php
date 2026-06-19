<?php
require_once "../app/models/UpdateProfileModel.php";
class UpdateProfileController{
    public function updateProfile(){
  
        header('Content-Type: application/json');

        if(!isset($_SESSION['user'])){
            echo json_encode([
                "error" => "Bạn chưa đăng nhập"
            ]);
            exit;
        }
        $model = new UpdateProfileModel();
        $id = $_SESSION['user']['id'];
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');

        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $user = $model->getUserById($id);
        if(empty($oldPassword) &&(!empty($newPassword) ||!empty($confirmPassword))){
            echo json_encode(["old_password_error" =>"Vui lòng nhập mật khẩu cũ"]);
            exit;
        }

        if(
            !empty($oldPassword) &&
            !empty($newPassword) &&
            !empty($confirmPassword)
        ){
            if(!password_verify($oldPassword,$user['password'])){
                echo json_encode(["old_password_error" =>"Mật khẩu cũ không đúng"]);
                exit;
            }
            if($newPassword != $confirmPassword){
                echo json_encode(["confirm_password_error" =>"Mật khẩu mới không khớp"]);
                exit;
            }

            $hashedPassword = password_hash(
                $newPassword,
                PASSWORD_DEFAULT
            );

            $result = $model->updateUserWithPassword($id,$username,$email,$hashedPassword);
        }else{

            $result = $model->updateUserWithoutPassword($id,$username,$email);
    }

        $_SESSION['user']['username'] = $username;
        $_SESSION['user']['email'] = $email;

        if($result){
            echo json_encode(["success" =>"Cập nhật tài khoản thành công"]);
        }else{
            echo json_encode(["error" =>"Cập nhật thất bại"]);
        }
        exit;
    }
}