<?php
$currentUrl = isset($_GET['url']) ? $_GET['url'] : 'home';
$hideNavbar = in_array($currentUrl, ['login', 'register']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Music Web</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>ok/css/layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>ok/css/login.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>ok/css/register.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>ok/css/upload.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>ok/css/artist.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>ok/css/updateprofile.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>ok/css/chatbot.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <?php if (!$hideNavbar): ?>
        <div class="navbar">
            <div class="logo"><i class="fa-brands fa-soundcloud"></i></div>
            <div class="home nav-link active" onclick="loadPage('home')">Home</div>
            <div class="library nav-link " onclick="loadPage('library')">Library</div>
            <form action="<?= BASE_URL ?>search" method="GET" class="search">
            <input id="Search" type="text" name="keyword" placeholder="Tìm bài hát..." autocomplete="off">
            <button type="button" id="voiceBtn"><i class="fa-solid fa-microphone"></i></button>
            <div id="suggestions"></div>
        </form>
        <div class="artist nav-link" onclick="loadPage('artist')">Artist</div>
        <div class="upload" onclick="location.href='<?= BASE_URL ?>upload'">Upload</div>
        <?php if (isset($_SESSION['user'])): ?>
            
            <div class="user-name">👋 <?= $_SESSION['user']['username'] ?></div>
            
            <?php else: ?>
                <div class="dn" onclick="location.href='<?= BASE_URL ?>login'">Sign in</div>
                <div class="dk" onclick="location.href='<?= BASE_URL ?>register'">Sign up</div>
        <?php endif; ?>
        <div class="mnprf nav-link">
            <div class="profile" onclick="toggleMenu1()"><i class="fa-solid fa-gear"></i></div>
            <div id="menuprf" class="menuprf">
                <div class="item" onclick="location.href='<?= BASE_URL ?>profile'"><i class="fa-solid fa-user"></i> Profile</div>
                <div class="item"><i class="fa-solid fa-heart"></i> Likes</div>
                <div class="item"><i class="fa-solid fa-broadcast-tower"></i> Stations</div>
                <div class="item"><i class="fa-solid fa-user-plus"></i> Who to follow</div>
                <div class="item"><i class="fa-solid fa-star"></i> Try Artist Pro</div>
                <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                    <a href="index.php?url=manager" class="item">
                        <i class="fa fa-users"></i> Manager
                    </a>
                <?php endif; ?>
                <div class="item" onclick="openUpdateProfile()"><i class="fa-solid fa-user-gear"></i>Mật khẩu và bảo mật</div>
                <div class="item" onclick="location.href='<?= BASE_URL ?>logout'"><i class="fa-solid fa-arrow-right-from-bracket"></i>Log out</div>
            </div>
        </div>
        <div class="tb"><i class="fa-regular fa-bell"></i></div>
        <div class="message"><i class="fa-regular fa-envelope"></i></div>
    </div>
    <?php endif; ?>
    
    <div class="main-layout <?= $hideNavbar ? 'login-container' : '' ?>">
        <div class="content"><?php include $view; ?></div>
    </div>
    
    <?php if (!$hideNavbar): ?>
        <div class="player">
            <img id="player-img" src="">
            <div class="info">
                <div id="player-title">Chưa phát bài nào</div>
                <div id="player-artist"></div>
            </div>
            <div class="progress-container">
                <span id="current-time">0:00</span>
                <div id="progress"><div id="progress-bar"></div></div>
                <span id="duration">0:00</span>
            </div>
            <div class="controls">
                <div class="prev" onclick="playPrev()"><i class="fa-solid fa-backward-fast"></i></div>
                <div class="play" onclick="togglePlay()"><i id="play-icon" class="fa-solid fa-play"></i></div>
                <div class="next" onclick="playNext()"><i class="fa-solid fa-forward-fast"></i></div>
            </div>
            <audio id="audio"></audio>
        </div>
    <?php endif; ?>
    <?php include "../app/views/AI/chatbot.php"; ?>
    
    <script>var BASE_URL = "<?= BASE_URL ?>";</script>
    <script src="<?= BASE_URL ?>ok/js/layout.js"></script>
    <script src="<?= BASE_URL ?>ok/js/chatbot.js"></script>
</body>
</html>