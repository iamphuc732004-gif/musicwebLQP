<div class="content-wrapper">
    <div class="home-content">
    <div class="artist-page">
    <h1 class="title">🎤 Artist</h1>
    <div class="artist-grid">
        <?php foreach($artists as $item): ?>
            <div class="artist-card"
                onclick="loadPage('artist_detail&name=<?= urlencode($item['artist']) ?>')">
                <div class="artist-avatar">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="artist-name">
                    <?= $item['artist'] ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
    </div>
</div>