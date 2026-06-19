<div class="content-wrapper">
    <div class="home-content">
        <div class="profile-page">
            <div class="cover">
                <img src="<?= BASE_URL ?>ok/images/cover.jpg" class="cover-img">
                <button class="edit-cover">📷 Cập nhật ảnh bìa</button>
            </div>
            <div class="profile-info">
                <img src="<?= BASE_URL ?>ok/images/<?= $user['avatar'] ?? 'default-avatar.png' ?>" class="avatar">
                <h2><?= $user['username'] ?></h2>
                <div class="tabs">
                    <span class="tab active" onclick="switchTab(event, 'tracks')">Tracks</span>
                    <span class="tab" onclick="switchTab(event, 'playlists')">Playlists</span>
                    <span class="tab" onclick="switchTab(event, 'likes')">Likes</span>
                </div>
            </div>
            <div id="tracks" class="tab-content active">
                <div class="container">
                    <div class="music-wrapper">
                        <button class="scroll-btn left" onclick="scrollMusic('list-library', -1)">❮</button>
                        <button class="scroll-btn right" onclick="scrollMusic('list-library', 1)">❯</button>
                        <div class="music-grid" id="list-library">
                            <?php if (empty($songs)): ?>
                                <p style="padding: 20px; color: #aaa;">Chưa có bài hát nào</p>
                                <?php else: ?>
                                    <?php foreach ($songs as $index => $song): ?>
                                        <div class="card js-play"
                                        data-id="<?= $song['id'] ?>"
                                        data-file="<?= htmlspecialchars($song['file'], ENT_QUOTES) ?>"
                                        data-track="<?= htmlspecialchars($song['track'], ENT_QUOTES) ?>"
                                        data-artist="<?= htmlspecialchars($song['artist'], ENT_QUOTES) ?>"
                                        data-image="<?= BASE_URL ?>ok/images/<?= htmlspecialchars($song['image'], ENT_QUOTES) ?>">
                                        <img src="<?= BASE_URL ?>ok/images/<?= htmlspecialchars($song['image'], ENT_QUOTES) ?>">
                                        <div class="card-title">
                                            <?= $song['track'] ?> - <?= $song['artist'] ?>
                                        </div>
                                        </div>
                                    <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="playlists" class="tab-content">
                <div class="container">
                    <?php if (empty($playlists)): ?>
                        <p style="color:#aaa;">Chưa có playlist</p>
                        <?php else: ?>
                            <div class="music-wrapper">
                                <button class="scroll-btn left" onclick="scrollMusic('playlist-list', -1)">❮</button>
                                <button class="scroll-btn right" onclick="scrollMusic('playlist-list', 1)">❯</button>
                                <div class="music-grid" id="playlist-list">
                                    <?php foreach ($playlists as $pl): ?>
                                        <div class="card"
                                        onclick="loadPlaylistSongs(<?= $pl['id'] ?>, '<?= addslashes($pl['name']) ?>')">
                                        <div class="card-menu">
                                            <div class="menu-btn"
                                            onclick="event.stopPropagation(); toggleCardMenu(this)">
                                            <i class="fa-solid fa-ellipsis"></i>
                                            </div>
                                            <div class="menu-dropdown">
                                                <div class="menu-item"
                                                onclick="event.stopPropagation();
                                                openEditPlaylist(
                                                    <?= $pl['id'] ?>,
                                                    '<?= addslashes($pl['name']) ?>',
                                                    '<?= BASE_URL ?>ok/images/<?= $pl['playlist_img'] ?? 'default-playlist.jpg' ?>'
                                                    )"><i class="fa-solid fa-pen"></i>Edit
                                                </div>
                                                <div class="menu-item"
                                                onclick="event.stopPropagation();
                                                deletePlaylist(<?= $pl['id'] ?>)">
                                                <i class="fa-solid fa-trash"></i>Delete
                                                </div>
                                            </div>
                                        </div>
                                        <img src="<?= BASE_URL ?>ok/images/<?= $pl['playlist_img'] ?? 'default-playlist.jpg' ?>">
                                        <div class="card-title">
                                            <?= htmlspecialchars($pl['name']) ?>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                                <hr style="border: 0.5px solid #333; margin: 40px 0;">
                                <div id="playlist-detail" style="margin-top:20px;">
                                <h3 id="playlist-name" style="margin-bottom:20px;color:#fff;"></h3>
                                <div class="music-wrapper">
                                    <button class="scroll-btn left" onclick="scrollMusic('playlist-songs', -1)">❮</button>
                                    <button class="scroll-btn right" onclick="scrollMusic('playlist-songs', 1)">❯</button>
                                    <div class="music-grid" id="playlist-songs"></div>
                                </div>
                                </div>
                        <?php endif; ?>
                </div>
            </div>
            <div id="likes" class="tab-content">
                <div class="container">
                    <div class="music-wrapper">
                        <button class="scroll-btn left" onclick="scrollMusic('liked-list-profile', -1)">❮</button>
                        <button class="scroll-btn right" onclick="scrollMusic('liked-list-profile', 1)">❯</button>
                        <div class="music-grid" id="liked-list-profile">
                            <?php if (empty($liked_songs)): ?>
                                <p style="padding:20px;color:#aaa;">Chưa có bài hát nào được thích</p>
                                <?php else: ?>
                                    <?php foreach ($liked_songs as $index => $song): ?>
                                        <div class="card js-play"
                                        data-id="<?= $song['id'] ?>"
                                        data-file="<?= htmlspecialchars($song['file'], ENT_QUOTES) ?>"
                                        data-track="<?= htmlspecialchars($song['track'], ENT_QUOTES) ?>"
                                        data-artist="<?= htmlspecialchars($song['artist'], ENT_QUOTES) ?>"
                                        data-image="<?= BASE_URL ?>ok/images/<?= htmlspecialchars($song['image'], ENT_QUOTES) ?>">
                                        <img src="<?= BASE_URL ?>ok/images/<?= htmlspecialchars($song['image'], ENT_QUOTES) ?>">
                                        <div class="card-title">
                                            <?= $song['track'] ?> - <?= $song['artist'] ?>
                                        </div>
                                        <div class="like" onclick="event.stopPropagation(); toggleLike(<?= $song['id'] ?>, this)"><i class="fa-solid fa-heart"></i></div>
                                        </div>
                                    <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <button id="createPlaylistBtn" class="create-playlist-btn" onclick="openCreatePlaylist()">+ Create Playlist</button>
        <div id="playlistModal" class="playlist-modal">
            <div class="playlist-modal-content">
                <span class="close-modal" onclick="closePlaylistModal()">&times;</span>
                <h2>Create Playlist</h2>

                <form action="<?= BASE_URL ?>index.php?url=create_playlist" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Tên Playlist</label>
                    <input type="text" name="name" placeholder="Nhập tên playlist..."required>
                </div>
                
                <div class="form-group">
                    <label>Ảnh Playlist</label>
                    <div class="upload-wrapper">
                        <div id="previewContainer" class="preview-container">
                            <img id="imagePreview" src="#" alt="Preview">
                            <span id="placeholderText">Chưa chọn ảnh</span>
                        </div>
                        
                        <label for="file-upload" class="choose-file-btn">Choose File</label>
                        <input id="file-upload" type="file" name="image" accept="image/*" onchange="previewImage(event)">
                    </div>
                </div>
                <button type="submit" class="create-playlist-submit">Create Playlist</button>
                </form>
            </div>
        </div>
        <div id="editPlaylistModal" class="playlist-modal">
            <div class="playlist-modal-content">
                <span class="close-modal" onclick="closeEditPlaylistModal()">&times;</span>
                <h2>Edit Playlist</h2>
                <form action="<?= BASE_URL ?>index.php?url=update_playlist"
                method="POST"
                enctype="multipart/form-data">
                <input type="hidden" name="playlist_id" id="editPlaylistId">
              
              <div class="form-group">
                <label>Tên Playlist</label>
                <input type="text"
                       name="name"
                       id="editPlaylistName"
                       required>
                </div>

                <div class="form-group">
                    <label>Ảnh Playlist</label>
                    <div class="upload-wrapper">
                        <div class="preview-container">
                            <img id="editImagePreview" style="display:block;">
                        </div>
                        <label for="edit-file-upload" class="choose-file-btn"> Choose File</label>
                        <input id="edit-file-upload" type="file" name="image" accept="image/*" onchange="previewEditImage(event)">
                    </div>
                </div>
                <button type="submit" class="create-playlist-submit"> Save Changes</button>

                 </form>
            </div>
        </div>
    </div>
</div>
<div id="confirmModal" class="confirm-modal">
    <div class="confirm-box">
        <h3>Xác nhận xóa</h3>

        <p>Bạn có chắc muốn xóa?</p>

        <div class="confirm-actions">
            <button class="btn-cancel"
                    onclick="closeConfirm()">
                Hủy
            </button>

            <button class="btn-delete"
                    id="confirmDeleteBtn">
                Xóa
            </button>
        </div>
    </div>
</div>
<script>
const playlistData = <?= json_encode($songs) ?>;
playlist = playlistData;
function previewImage(event) {
const input = event.target;
const preview = document.getElementById("imagePreview");
const placeholder = document.getElementById("placeholderText");
if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
        preview.src = e.target.result;
        preview.style.display = "block";
        placeholder.style.display = "none";
    };
    reader.readAsDataURL(input.files[0]);
}
}
function closePlaylistModal() {
    document.getElementById('playlistModal').style.display = 'none';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('imagePreview').src = '#';
    }
</script>