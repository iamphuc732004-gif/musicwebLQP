<div class="artist-detail">
<div class="close-btn" onclick="loadPage('artist')">
        ✕
    </div>
    <h1 class="title3">
        🎵 <?= $artist ?>
    </h1>
    <div class="music-grid">
        <?php foreach($songs as $index => $song): ?>
            <div class="card"
                onclick="playSong(
                    '<?= $song['file'] ?>',
                    '<?= addslashes($song['track']) ?>',
                    '<?= addslashes($song['artist']) ?>',
                    '<?= BASE_URL ?>ok/images/<?= $song['image'] ?>',
                    <?= $index ?>,
                    <?= $song['id'] ?>
                )">
                <img src="<?= BASE_URL ?>ok/images/<?= $song['image'] ?>">
                <div class="card-title">
                    <?= $song['track'] ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>