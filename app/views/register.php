<?php
$errors = $_SESSION['register_errors'] ?? [];

$oldUsername = $_SESSION['old_username'] ?? '';
$oldEmail = $_SESSION['old_email'] ?? '';

unset($_SESSION['register_errors']);
unset($_SESSION['old_username']);
unset($_SESSION['old_email']);
?>

<div class="auth-box">
    <span class="close-btn"
        onclick="location.href='<?= BASE_URL ?>index.php?url=home'">
        &times;
    </span>

    <h2>Sign up</h2>

    <form action="<?= BASE_URL ?>index.php?url=doRegister" method="POST">

        <div class="input-group">
            <label>Username</label>

            <input
                type="text"
                name="username"
                value="<?= htmlspecialchars($oldUsername) ?>"
                required>

            <?php if(isset($errors['username'])): ?>
                <div class="error-message">
                    <?= $errors['username'] ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="input-group">
            <label>Email</label>

            <input
                type="email"
                name="email"
                value="<?= htmlspecialchars($oldEmail) ?>"
                required>

            <?php if(isset($errors['email'])): ?>
                <div class="error-message">
                    <?= $errors['email'] ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="input-group">
            <label>Password</label>

            <input
                type="password"
                name="password"
                id="password"
                required>

            <?php if(isset($errors['password'])): ?>
                <div class="error-message">
                    <?= $errors['password'] ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="input-group">
            <label>Confirm Password</label>

            <input
                type="password"
                id="confirm_password"
                required>

            <div id="confirm-error"
                 class="error-message"
                 style="display:none;">
                Mật khẩu không khớp!
            </div>
        </div>

        <button type="submit" class="btn-auth">
            Create Account
        </button>

    </form>

    <p class="auth-switch">
        Đã có tài khoản?
        <a href="<?= BASE_URL ?>index.php?url=login">
            Sign in
        </a>
    </p>
</div>

<script>
document.querySelector("form").addEventListener("submit", function(e) {

    const pass = document.getElementById("password").value;
    const confirm = document.getElementById("confirm_password").value;
    const error = document.getElementById("confirm-error");

    if (pass !== confirm) {
        error.style.display = "block";
        e.preventDefault();
    } else {
        error.style.display = "none";
    }
});
</script>