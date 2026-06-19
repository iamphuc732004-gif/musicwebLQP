<div id="updateProfileModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Tài khoản & bảo mật</h2>
            <span class="close"
                onclick="closeUpdateProfile()">
                &times;
            </span>
        </div>

        <form
            id="updateProfileForm"
            action="<?= BASE_URL ?>index.php?url=updateProfile"
            method="POST">

            <div class="form-group">
                <label>Tên người dùng</label>
                <input type="text" name="username" required value="<?= $_SESSION['user']['username'] ?? '' ?>">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required value="<?= $_SESSION['user']['email'] ?? '' ?>">
            </div>
            <hr>

            <div class="form-group">
                <label>Mật khẩu cũ</label>
                <input type="password"name="old_password">
                <small class="error-text" id="oldPasswordError"></small>
            </div>

            <div class="form-group">
                <label>Mật khẩu mới</label>
                <input type="password" name="new_password">
            </div>

            <div class="form-group">
                <label>Nhập lại mật khẩu mới</label>
                <input type="password"name="confirm_password">
                <small class="error-text"id="confirmPasswordError"></small>
            </div>

            <button type="submit" class="save-btn">Lưu thay đổi</button>

        </form>
    </div>

<div id="successOverlay" class="success-overlay">
    <div class="success-popup">
        <i class="fa-solid fa-circle-check"></i>
        <h3>Cập nhật thành công</h3>
        <p>Thông tin tài khoản đã được cập nhật</p>
    </div>
</div>
</div>