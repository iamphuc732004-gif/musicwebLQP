<div class="upload-container">
    <span class="close-btn" onclick="location.href='<?= BASE_URL ?>index.php?url=home'">&times;</span>
    <h2>Upload your track</h2>
    <form action="<?= BASE_URL ?>index.php?url=upload" method="POST" enctype="multipart/form-data">
        <div class="upload-box" onclick="document.getElementById('file').click()">
            <p id="uploadText">Drag & Drop your audio here</p>
            <span>or click to browse</span>
            <input type="file" id="file" name="music" hidden required>
        </div>

        <input type="text" name="track" placeholder="Track title" class="input" required>
        <input type="text" name="artist" placeholder="Artist name" class="input" required>
        <input type="text" name="type" placeholder="Type" class="input">

        <div class="upload-box cover-box" onclick="document.getElementById('imageInput').click()">
            <div id="coverPlaceholder">
                <p id="coverText">Drag & Drop cover here</p>
                <span>or click to select image</span>
            </div>
            <input type="file" id="imageInput" name="image" accept="image/*" hidden onchange="previewImage(event)" required>
            <img id="imagePreview" src="#" alt="Preview" style="display:none;">
        </div>

        <button type="submit" class="btn-upload">Upload</button>

    </form>
</div>
<script src="<?= BASE_URL ?>ok/js/upload.js"></script>