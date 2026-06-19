<div class="auth-box">
    <span class="close-btn" onclick="location.href='<?= BASE_URL ?>index.php?url=home'">&times;</span>
    <h2>Sign up</h2>
    <form action="<?= BASE_URL ?>index.php?url=doRegister" method="POST">
        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>
        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="input-group">
            <label>Confirm Password</label>
            <input type="password" id="confirm_password" required>
        </div>
        <button type="submit" class="btn-auth">Create Account</button>
    </form>
    <p class="auth-switch">
        Đã có tài khoản?
        <a href="<?= BASE_URL ?>index.php?url=login">Sign in</a>
    </p>
</div>
<script>
    document.querySelector("form").addEventListener("submit", function(e) {
        const pass = document.getElementById("password").value;
        const confirm = document.getElementById("confirm_password").value;
        if (pass !== confirm) {
            alert("Mật khẩu không khớp!");
            e.preventDefault();
        }
    });
</script>