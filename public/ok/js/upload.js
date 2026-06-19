const fileInput = document.getElementById("file");
const imageInput = document.getElementById("imageInput");
const uploadText = document.getElementById("uploadText");
const coverText = document.getElementById("coverText");
const imagePreview = document.getElementById("imagePreview");
const coverPlaceholder = document.getElementById("coverPlaceholder");

fileInput.addEventListener("change", function () {
    if (this.files.length > 0) {
        uploadText.innerText = "Selected: " + this.files[0].name;
    }
});

imageInput.addEventListener("change", function (event) {
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = "block";
            coverPlaceholder.style.display = "none";
        };

        reader.readAsDataURL(file);
    }
});

const uploadBox = document.querySelector(".upload-box");

uploadBox.addEventListener("dragover", function (e) {
    e.preventDefault();
    uploadBox.style.border = "2px solid #ff4d00";
});

uploadBox.addEventListener("dragleave", function () {
    uploadBox.style.border = "2px dashed #555";
});

uploadBox.addEventListener("drop", function (e) {
    e.preventDefault();

    fileInput.files = e.dataTransfer.files;

    if (fileInput.files.length > 0) {
        uploadText.innerText = "Selected: " + fileInput.files[0].name;
    }

    uploadBox.style.border = "2px dashed #555";
});