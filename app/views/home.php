<div class="content-wrapper">
    <div class="home-content">
    <div class="container">
        <h1 class="title">🎵 Bài hát</h1>
        <div class="music-wrapper">
            <button class="scroll-btn left" onclick="scrollMusic('homeMusic', -1)">❮</button>
            <button class="scroll-btn right" onclick="scrollMusic('homeMusic', 1)">❯</button>
            <div class="music-grid" id="homeMusic">
                <?php foreach ($songs as $index => $song): ?>
                    <div class="card js-play"
                        data-file="<?= htmlspecialchars($song['file'] ?? '') ?>"
                        data-track="<?= htmlspecialchars($song['track'] ?? '') ?>"
                        data-artist="<?= htmlspecialchars($song['artist'] ?? '') ?>"
                        data-image="<?= htmlspecialchars(BASE_URL . 'ok/images/' . (!empty($song['image']) ? $song['image'] : 'default.jpg')) ?>"
                        data-index="<?= $index ?>"
                        data-id="<?= $song['id'] ?>"> 
                        <?php if(isset($_SESSION['user'])): ?>
                            <div class="card-menu">
                                <div class="menu-btn" onclick="event.stopPropagation(); toggleCardMenu(this)">
                                <i class="fa-solid fa-ellipsis"></i>
                            </div>
                            <div class="menu-dropdown">
                                <?php if($_SESSION['user']['role'] === 'admin'): ?>
                                    <div class="menu-item" onclick="event.stopPropagation(); editSong(<?= $song['id'] ?>)"><i class="fa-solid fa-pen"></i> Edit</div>
                                    <div class="menu-item" onclick="event.stopPropagation(); deleteSong(<?= $song['id'] ?>)"><i class="fa-solid fa-trash"></i> Delete</div>
                                <?php endif; ?>
                                <div class="menu-item" onclick="event.stopPropagation(); addToPlaylist(<?= $song['id'] ?>)"><i class="fa-solid fa-plus"></i> Add to playlist</div>
                                <div class="menu-item" onclick="event.stopPropagation(); downloadSong('<?= BASE_URL ?>ok/music/<?= $song['file'] ?>')"><i class="fa-solid fa-download"></i> Download</div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <img src="<?= BASE_URL ?>ok/images/<?= !empty($song['image']) ? $song['image'] : 'default.jpg' ?>">
                        <div class="card-title"><?= htmlspecialchars($song['track']) ?> - <?= htmlspecialchars($song['artist']) ?></div>
                        <div class="like" onclick="event.stopPropagation(); toggleLike(<?= $song['id'] ?>, this)">
                            <i class="<?= in_array($song['id'], $liked_ids) ? 'fa-solid' : 'fa-regular' ?> fa-heart"></i>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="container">
        <h1 class="title1">🎵 My Library</h1>
        <div class="music-wrapper">
            <button class="scroll-btn left" onclick="scrollMusic('list-library', -1)">❮</button>
            <button class="scroll-btn right" onclick="scrollMusic('list-library', 1)">❯</button>
            <div class="music-grid" id="list-library">
                <?php if (empty($library_songs)): ?>
                    <p style="padding:20px;color:#aaa;">Chưa có bài hát nào trong thư viện</p>
                <?php else: ?>
                    <?php foreach ($library_songs as $index => $song): ?>
                        <div class="card js-play" 
                        data-file="<?= htmlspecialchars($song['file']) ?>" 
                        data-track="<?= htmlspecialchars($song['track']) ?>" 
                        data-artist="<?= htmlspecialchars($song['artist']) ?>" 
                        data-image="<?= htmlspecialchars(BASE_URL . 'ok/images/' . (!empty($song['image']) ? 
                        $song['image'] : 'default.jpg')) ?>" 
                        data-index="<?= $index ?>" 
                        data-id="<?= $song['id'] ?>">
                        <div class="card-menu">
                            <div class="menu-btn" onclick="event.stopPropagation(); toggleCardMenu(this)"><i class="fa-solid fa-ellipsis"></i></div>
                            <div class="menu-dropdown">
                                <div class="menu-item" onclick="event.stopPropagation(); editSong(<?= $song['id'] ?>)"><i class="fa-solid fa-pen"></i> Edit</div>
                                <div class="menu-item" onclick="event.stopPropagation(); deleteSong(<?= $song['id'] ?>)"><i class="fa-solid fa-trash"></i> Delete</div>
                                <div class="menu-item" onclick="event.stopPropagation(); addToPlaylist(<?= $song['id'] ?>)"><i class="fa-solid fa-plus"></i> Add to playlist</div>
                                <div class="menu-item" onclick="event.stopPropagation(); downloadSong('<?= BASE_URL ?>ok/music/<?= $song['file'] ?>')"><i class="fa-solid fa-download"></i> Download</div>
                            </div>
                        </div>
                            <img src="<?= BASE_URL ?>ok/images/<?= !empty($song['image']) ? $song['image'] : 'default.jpg' ?>">
                            <div class="card-title"><?= htmlspecialchars($song['track']) ?> - <?= htmlspecialchars($song['artist']) ?></div>
                            <div class="like" onclick="event.stopPropagation(); toggleLike(<?= $song['id'] ?>, this)">
                            <i class="<?= in_array($song['id'], $liked_ids) ? 'fa-solid' : 'fa-regular' ?> fa-heart"></i>
                        </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="container">
        <h1 class="title2">❤️ Likes</h1>
        <div class="music-wrapper">
            <button class="scroll-btn left" onclick="scrollMusic('liked-list', -1)">❮</button>
            <button class="scroll-btn right" onclick="scrollMusic('liked-list', 1)">❯</button>
            <div class="music-grid" id="liked-list">
                <?php foreach ($liked_songs as $index => $song): ?>
    <div class="card js-play"
        data-file="<?= htmlspecialchars($song['file'] ?? '') ?>"
        data-track="<?= htmlspecialchars($song['track'] ?? '') ?>"
        data-artist="<?= htmlspecialchars($song['artist'] ?? '') ?>"
        data-image="<?= htmlspecialchars(BASE_URL . 'ok/images/' . (!empty($song['image']) ? $song['image'] : 'default.jpg')) ?>"
        data-index="<?= $index ?>"
        data-id="<?= $song['id'] ?>">

        <img src="<?= BASE_URL ?>ok/images/<?= !empty($song['image']) ? $song['image'] : 'default.jpg' ?>">

        <div class="card-title">
            <?= htmlspecialchars($song['track']) ?> - <?= htmlspecialchars($song['artist']) ?>
        </div>

        <div class="like" onclick="event.stopPropagation(); toggleLike(<?= $song['id'] ?>, this)">
            <i class="fa-solid fa-heart"></i>
        </div>
    </div>
<?php endforeach; ?>
            </div>
        </div>
    </div>
    </div>

    <div class="history-box">
    <div class="history-title">Listening History</div>

    <div class="history-list" id="history-list">

        <?php foreach($historySongs as $index => $song): ?>

            <div class="history-item js-play"

                data-file="<?= htmlspecialchars($song['file'] ?? '') ?>"
                data-track="<?= htmlspecialchars($song['track'] ?? '') ?>"
                data-artist="<?= htmlspecialchars($song['artist'] ?? '') ?>"
                data-image="<?= htmlspecialchars(BASE_URL . 'ok/images/' . (!empty($song['image']) ? $song['image'] : 'default.jpg')) ?>"
                data-index="<?= $index ?>"
                data-id="<?= $song['id'] ?>">

                <img src="<?= BASE_URL ?>ok/images/<?= !empty($song['image']) ? $song['image'] : 'default.jpg' ?>">

                <div class="history-info">
                    <div class="history-song">
                        <?= htmlspecialchars($song['track']) ?>
                    </div>

                    <small>
                        <?= htmlspecialchars($song['artist']) ?>
                    </small>
                </div>

            </div>

        <?php endforeach; ?>

    </div>
</div>
</div>

<div id="playlistPopup" class="playlist-popup" style="display:none;">
    <div class="playlist-popup-content">
        <div class="playlist-popup-title">Add to playlist</div>
        <div id="playlist-list">
            <?php foreach($playlists as $pl): ?>
                <div class="playlist-item" onclick="saveToPlaylist(<?= $pl['id'] ?>, currentAddSongId)">
                    <img src="<?= BASE_URL ?>ok/images/<?= $pl['playlist_img'] ?>">
                    <span><?= htmlspecialchars($pl['name']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<div id="confirmModal" class="confirm-modal">
    <div class="confirm-box">
        <h3>Xóa bài hát</h3>
        <p>Bạn có chắc muốn xóa bài hát này?</p>

        <div class="confirm-actions">
            <button class="btn-cancel" onclick="closeConfirm()">
                Hủy
            </button>

            <button class="btn-delete" id="confirmDeleteBtn">
                Xóa
            </button>
        </div>
    </div>
</div>
<div id="toast"></div>

<?php require_once __DIR__ . "/updateProfile.php"; ?>

<script>
function playHandler(el) {


playSong(
    el.dataset.file || "",
    el.dataset.track || "",
    el.dataset.artist || "",
    el.dataset.image || "",
    parseInt(el.dataset.index || 0),
    parseInt(el.dataset.id || 0)
);
}
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".js-play").forEach(el => {
        el.addEventListener("click", () => playHandler(el));
    });
});
</script>