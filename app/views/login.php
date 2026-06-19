<div class="auth-box">
    <span class="close-btn" onclick="location.href='?url=home'">&times;</span>
    <h2>Sign in</h2>
    <form method="POST" action="?url=doLogin">
        <div class="input-group">
            <label>Username / Email</label>
            <input type="text" name="username" required placeholder="Username or Email">
        </div>
        <div class="input-group">
            <label>Mật khẩu</label>
            <input type="password" name="password" required placeholder="Password">
        </div>
        <?php if(isset($_SESSION['login_error'])): ?>
            <div class="error-message">
                <?= $_SESSION['login_error']; ?>
            </div>
            <?php unset($_SESSION['login_error']); ?>
        <?php endif; ?>
        <button type="submit" class="btn-auth">Sign in</button>
    </form>
    <p class="auth-switch">
        Chưa có tài khoản? 
        <a href="?url=register">Sign up</a>
    </p>
</div>