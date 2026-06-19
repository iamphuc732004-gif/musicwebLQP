<div class="content-wrapper">
<span class="close-btn" onclick="location.href='<?= BASE_URL ?>index.php?url=home'">&times;</span>
<div class="edit-song-page">
    <h2>Edit Song</h2>
    <form method="POST"
        enctype="multipart/form-data">
        <input type="text" name="track" value="<?= $song['track'] ?>" placeholder="Tên bài hát">
        <input type="text" name="artist" value="<?= $song['artist'] ?>" placeholder="Tên tác giả">
        <div class="image_container"><img src="<?= BASE_URL ?>ok/images/<?= $song['image'] ?>" class="preview-img"></div>
        <input type="file" name="image">
        <button type="submit"> Save Changes </button>
    </form>
</div>
</div>