<div class="container">
    <div class="title3">🎵 Thư viện của tôi</div>
    <div class="music-wrapper">
        <button class="scroll-btn left" onclick="scrollMusic('homeMusic', -1)">❮</button>
        <button class="scroll-btn right" onclick="scrollMusic('homeMusic', 1)">❯</button>
        <div class="music-grid" id="homeMusic">
            <?php if (empty($songs)): ?>
                <p>Chưa có bài hát nào</p>
            <?php else: ?>
                <?php foreach ($songs as $index => $song): ?>
                    <div class="card js-play"
                        data-file="<?= htmlspecialchars($song['file']) ?>"
                        data-track="<?= htmlspecialchars($song['track']) ?>"
                        data-artist="<?= htmlspecialchars($song['artist']) ?>"
                        data-image="<?= htmlspecialchars(BASE_URL . 'ok/images/' . $song['image']) ?>"
                        data-index="<?= $index ?>"
                        data-id="<?= $song['id'] ?>">
                        <img src="<?= BASE_URL ?>ok/images/<?= $song['image'] ?>" class="thumb">
                        <div class="info">
                            <div class="name"><?= htmlspecialchars($song['track']) ?></div>
                            <div class="artist"><?= htmlspecialchars($song['artist']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>