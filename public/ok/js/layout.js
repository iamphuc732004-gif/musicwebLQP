function login() {
    window.location.href = BASE_URL + "login";
}
function register() {
    window.location.href = BASE_URL + "register";
}
function upload() {
    window.location.href = BASE_URL + "upload";
}
function goManager(){
    window.location.href = "index.php?url=manager";
}
function toggleMenu1() {
    const menu = document.getElementById("menuprf");
    if (menu) {
        menu.style.display = (menu.style.display === "block") ? "none" : "block";
    }
}

let audio = null;
let playIcon = null;
let progressBar = null;
let progressContainer = null;
let currentTimeEl = null;
let durationEl = null;
let playlist = [];
let currentIndex = 0;

document.addEventListener("DOMContentLoaded", () => {
    audio = document.getElementById("audio");
    if (!audio) return; 
    playIcon = document.getElementById("play-icon");
    progressBar = document.getElementById("progress-bar");
    progressContainer = document.getElementById("progress");
    currentTimeEl = document.getElementById("current-time");
    durationEl = document.getElementById("duration");

    audio.addEventListener("timeupdate", () => {
    const { currentTime, duration } = audio;
    if (!duration) return;

    if (!isDragging) {
        const percent = (currentTime / duration) * 100;
        if (progressBar) progressBar.style.width = percent + "%";
    }
    if (currentTimeEl) currentTimeEl.innerText = formatTime(currentTime);
    if (durationEl) durationEl.innerText = formatTime(duration);
});

let isDragging = false;
if (progressContainer) {

    progressContainer.addEventListener("click", (e) => {
        seek(e);
    });
    progressContainer.addEventListener("mousedown", (e) => {
        isDragging = true;
        seek(e);
    });
    
    document.addEventListener("mousemove", (e) => {
        if (isDragging) {
            seek(e);
        }
    });
  
    document.addEventListener("mouseup", () => {
        isDragging = false;
    });
}

function seek(e) {
    const rect = progressContainer.getBoundingClientRect();
    const offsetX = e.clientX - rect.left;
    const width = rect.width;
    let percent = offsetX / width;

    percent = Math.max(0, Math.min(1, percent));
    audio.currentTime = percent * audio.duration;

    if (progressBar) {
        progressBar.style.width = (percent * 100) + "%";
    }
}
if (audio) {
    audio.addEventListener("ended", () => {
        playNext();
    });
}
});
function togglePlay() {
    if (!audio) return;
    if (audio.paused) {
        audio.play();
        if (playIcon) playIcon.className = "fa-solid fa-pause";
    } else {
        audio.pause();
        if (playIcon) playIcon.className = "fa-solid fa-play";
    }
}

function playSong(file, title, artist, image, index, id) {
    if (!audio) return;
    currentSong = { id, file, title, artist, image };
    currentIndex = Number(index) || 0;
    const src = file.startsWith("http")
        ? file
        : BASE_URL + "ok/music/" + file;
    audio.src = src;
    audio.load();
    document.querySelector(".player").style.display = "flex";

    document.getElementById("player-title").innerText = title;
    document.getElementById("player-artist").innerText = artist;

    const imgSrc = image.startsWith("http") || image.startsWith(BASE_URL)
        ? image
        : BASE_URL + "ok/images/" + image;
    document.getElementById("player-img").src = imgSrc;
    if (playIcon) playIcon.className = "fa-solid fa-pause";
    audio.play().catch(console.log);
    addToHistoryUI({
        id,
        file,
        track: title,
        artist,
        image: image.replace(BASE_URL + "ok/images/", "")
    });
    fetch(BASE_URL + "index.php?url=add_history", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "song_id=" + id
    });
    updatePlayerLikeIcon(id);
}
function updatePlayerLikeIcon(songId) {
    let icon = document.getElementById("like-icon");
    if (!icon) return;
    let liked = document.querySelector(
        `.card[data-id='${songId}'] .fa-solid`
    );
    icon.classList.toggle("fa-solid", !!liked);
    icon.classList.toggle("fa-regular", !liked);
}

function playNext() {
    if (!playlist || playlist.length === 0) {
        console.log("Playlist empty");
        return;
    }
    currentIndex = Number(currentIndex) + 1;
    if (currentIndex >= playlist.length) {
        currentIndex = 0;
    }
    const song = playlist[currentIndex];
    if (!song) return;
    playSong(
        song.file,
        song.track,
        song.artist,
        song.image,
        currentIndex,
        song.id
    );
}
function playPrev() {
    if (!playlist || playlist.length === 0) {
        console.log("Playlist empty");
        return;
    }
    currentIndex = Number(currentIndex) - 1;
    if (currentIndex < 0) {
        currentIndex = playlist.length - 1;
    }
    const song = playlist[currentIndex];
    if (!song) return;
    playSong(
        song.file,
        song.track,
        song.artist,
        song.image,
        currentIndex,
        song.id
    );
}
function formatTime(time) {
    const hours = Math.floor(time / 3600);
    const minutes = Math.floor((time % 3600) / 60);
    const seconds = Math.floor(time % 60);
    if (hours > 0) {
        return `${hours}:${minutes < 10 ? '0' : ''}${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    } else {
        return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    }
}
function loadPage(page) {
    fetch(BASE_URL + page)
    .then(res => res.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, "text/html");
        const newContent =
            doc.querySelector(".content").innerHTML;
        document.querySelector(".content").innerHTML =
            newContent;

        history.pushState({}, "", BASE_URL + page);
    });
}
function scrollMusic(id, direction) {
    const container = document.getElementById(id);
    if (!container) return;
    const card = container.querySelector('.card');
    if (!card) return;

    const cardWidth = card.offsetWidth; 
    const gap = parseInt(window.getComputedStyle(container).gap) || 0;

    const scrollAmount = (cardWidth + gap) * 4;
    container.scrollBy({
        left: direction * scrollAmount,
        behavior: "smooth"
    });
}
function switchTab(e, tabId) {
    document.querySelectorAll('.tab-content').forEach(el => {
        el.classList.remove('active');
    });

    document.querySelectorAll('.tab').forEach(el => {
        el.classList.remove('active');
    });

    const tab = document.getElementById(tabId);
    if (tab) tab.classList.add('active');
    e.target.classList.add('active');
    const createBtn = document.getElementById("createPlaylistBtn");
    if (createBtn) {
        if (tabId === "playlists") {
            createBtn.style.display = "block";
        } else {
            createBtn.style.display = "none";
        }
    }
}

function openCreatePlaylist(){
    document.getElementById("playlistModal").style.display = "flex";
}
function closePlaylistModal(){
    document.getElementById("playlistModal").style.display = "none";
}

function togglePlaylistMenu(event, element){
    event.stopPropagation();
    document.querySelectorAll(".menu-dropdown")
        .forEach(menu => {
            if(menu !== element.querySelector(".menu-dropdown")){
                menu.classList.remove("show");
            }
        });
    element.querySelector(".menu-dropdown")
        .classList.toggle("show");
}
document.addEventListener("click", () => {
    document.querySelectorAll(".menu-dropdown")
        .forEach(menu => {
            menu.classList.remove("show");
        });
});

function openEditPlaylist(id, name, image){
    document.getElementById("editPlaylistModal")
        .style.display = "flex";
    document.getElementById("editPlaylistId")
        .value = id;
    document.getElementById("editPlaylistName")
        .value = name;
    document.getElementById("editImagePreview")
        .src = image;
}

function closeEditPlaylistModal(){
    document.getElementById("editPlaylistModal")
        .style.display = "none";
}
function previewEditImage(event){
    const reader = new FileReader();
    reader.onload = function(e){
        document.getElementById("editImagePreview")
            .src = e.target.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

function deletePlaylist(id){

    document.getElementById("confirmModal").style.display = "flex";

    document.querySelector("#confirmModal h3").textContent =
        "Xóa playlist";

    document.querySelector("#confirmModal p").textContent =
        "Bạn có chắc muốn xóa playlist này?";

    document.getElementById("confirmDeleteBtn").onclick = function(){

        window.location.href =
            "index.php?url=delete_playlist&id=" + id;
    };
}
function closeConfirm(){
    document.getElementById("confirmModal").style.display = "none";
}
document.querySelectorAll("#menuprf .item").forEach(item => {
    item.addEventListener("click", function () {
        document.getElementById("menuprf").style.display = "none";
    });
});
function toggleMenu1() {
    const menu = document.getElementById("menuprf");
    menu.style.display = (menu.style.display === "block") ? "none" : "block";
}
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll("#menuprf .item").forEach(item => {
        item.addEventListener("click", function () {
            document.getElementById("menuprf").style.display = "none";
        });
    });

    document.addEventListener("click", function (e) {
        const menu = document.getElementById("menuprf");
        const button = document.querySelector(".profile");
        if (!menu.contains(e.target) && !button.contains(e.target)) {
            menu.style.display = "none";
        }
    });
});
let currentSong = null;
function toggleLike(songId, el) {
    fetch(BASE_URL + "index.php?url=like_toggle", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "song_id=" + songId
    })
    .then(res => res.json())
    .then(data => {
        console.log("SERVER:", data);
        if (data.status === "liked") {
            addToLiked(data.song);
        } else {
            removeFromLiked(songId);
        }
        document.querySelectorAll(`.card[data-id='${songId}'] .like i`)
            .forEach(icon => {
                icon.classList.toggle("fa-solid", data.status === "liked");
                icon.classList.toggle("fa-regular", data.status !== "liked");
            });
        let playerIcon = document.getElementById("like-icon");
        if (playerIcon) {
            playerIcon.classList.toggle("fa-solid", data.status === "liked");
            playerIcon.classList.toggle("fa-regular", data.status !== "liked");
        }
    });
}
function syncHeartButtons(songId, status) {
    const allCards = document.querySelectorAll(`.card[data-id='${songId}'] .like i`);
    allCards.forEach(icon => {
        if (status === "like") {
            icon.classList.replace("fa-regular", "fa-solid");
        } else {
            icon.classList.replace("fa-solid", "fa-regular");
        }
    });
    const playerHeart = document.querySelector(".player-like-icon");
    if (currentSong && currentSong.id == songId && playerHeart) {
        if (status === "like") {
            playerHeart.classList.replace("fa-regular", "fa-solid");
        } else {
            playerHeart.classList.replace("fa-solid", "fa-regular");
        }
    }
}
function addToLiked(song) {
    let likedList = document.getElementById("liked-list");
    if (!likedList) return;
    if (!song || !song.id) return;
    if (likedList.querySelector(`[data-id='${song.id}']`)) return;
    const emptyMsg = likedList.querySelector('p');
    if (emptyMsg) emptyMsg.remove();
    let html = `
        <div class="card" data-id="${song.id}"
            onclick="playSong(
                '${song.file}',
                '${song.track}',
                '${song.artist}',
                '${BASE_URL}ok/images/${song.image}',
                0,
                ${song.id}
            )">
            <img src="${BASE_URL}ok/images/${song.image}">
            <div class="card-title">
                ${song.track} - ${song.artist}
            </div>
            <div class="like" onclick="event.stopPropagation(); toggleLike(${song.id}, this)">
                <i class="fa-solid fa-heart"></i>
            </div>
        </div>
    `;
    likedList.insertAdjacentHTML("afterbegin", html);
}
function removeFromLiked(songId) {
    let card = document.querySelector(`#liked-list [data-id='${songId}']`);
    if (card) card.remove();
}

function loadPlaylistSongs(playlistId, playlistName) {
    document.getElementById("playlist-name").innerText = "🎧 " + playlistName;
    fetch(BASE_URL + "index.php?url=playlist_songs&id=" + playlistId)
        .then(res => res.json())
        .then(songs => {
            let container = document.getElementById("playlist-songs");
            container.innerHTML = "";

            if (songs.length === 0) {
                container.innerHTML = "<p style='color:#aaa'>Playlist chưa có bài</p>";
                playlist = [];
                return;
            }
            playlist = songs;
            songs.forEach((song, index) => {
                let html = `
                    <div class="card js-play"
                        data-id="${song.id}"
                        data-file="${song.file}"
                        data-track="${song.track}"
                        data-artist="${song.artist}"
                        data-image="${song.image}">
                        <div class="card-menu">
                        <button class="remove-btn"
                        onclick="event.stopPropagation(); removeFromPlaylist(${playlistId}, ${song.id})">
                        <i class="fa-solid fa-trash"></i>
                        </button>
                        </div>
                        <img src="${BASE_URL}ok/images/${song.image}">

                        <div class="card-title">
                            ${song.track} - ${song.artist}
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML("beforeend", html);
            });
        });
}
function removeFromPlaylist(playlistId, songId) {
    if (!confirm("Xóa bài hát khỏi playlist?")) return;

    fetch(BASE_URL + "index.php?url=remove_playlist_song", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `playlist_id=${playlistId}&song_id=${songId}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            loadPlaylistSongs(
                playlistId,
                document.getElementById("playlist-name")
                    .innerText.replace("🎧 ", "")
            );
        }
    });
}
const searchInput = document.getElementById("Search");
const suggestions = document.getElementById("suggestions");
searchInput.addEventListener("keyup", async function () {
    const keyword = this.value.trim();
    if (keyword === "") {
        suggestions.innerHTML = "";
        return;
    }
    try {
        const response = await fetch(
            BASE_URL + "search?keyword=" + encodeURIComponent(keyword)
        );
        const songs = await response.json();
        if (!Array.isArray(songs)) {
            console.log("Không phải array:", songs);
            return;
        }
        let html = "";
        songs.forEach(song => {
            html += `
                <div class="suggest-item"
                    onclick="
                        playSong(
                            '${song.file}',
                            '${song.track.replace(/'/g, "\\'")}',
                            '${song.artist.replace(/'/g, "\\'")}',
                            '${BASE_URL}ok/images/${song.image}',
                            0,
                            ${song.id}
                        );
                        suggestions.innerHTML = '';">
                    <img
                        src="${BASE_URL}ok/images/${song.image}"
                        alt=""
                    >
                    <div class="suggest-info">
                        <div class="song-name">${song.track}</div>
                        <small>${song.artist}</small>
                    </div>
                </div>
            `;
        });
        suggestions.innerHTML = html;
    } catch (error) {
        console.log("Search error:", error);
    }
});

document.addEventListener("click", function (e) {
    if (
        !searchInput.contains(e.target) &&
        !suggestions.contains(e.target)
    ) {
        suggestions.innerHTML = "";
    }
});

const voiceBtn = document.getElementById("voiceBtn");
const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
let recognition = null;
let isListening = false; 

if (SpeechRecognition && voiceBtn && searchInput) {
    recognition = new SpeechRecognition();
    recognition.lang = "vi-VN";
    recognition.continuous = false;   
    recognition.interimResults = true; 
    recognition.maxAlternatives = 1;
    voiceBtn.addEventListener("click", toggleVoiceSearch);
    recognition.onstart = function () {
        isListening = true;
        console.log("MIC START");
        voiceBtn.innerHTML = `<i class="fa-solid fa-wave-square text-danger"></i>`; 
    };

    recognition.onresult = function (event) {
        let isFinal = event.results[event.results.length - 1].isFinal;
        
        let transcript = "";
        for (let i = 0; i < event.results.length; i++) {
            transcript += event.results[i][0].transcript;
        }
        console.log("Đang nói:", transcript);

        searchInput.value = transcript;

        if (isFinal) {
            console.log("KẾT QUẢ CUỐI CÙNG:", transcript.trim());
            searchInput.value = transcript.trim();

            searchInput.focus();

            recognition.stop(); 

            searchInput.dispatchEvent(new Event("keyup"));
        }
    };

    recognition.onerror = function (event) {
        console.log("ERROR:", event.error);
        if (event.error === "not-allowed") {
            alert("Bạn chưa cấp quyền microphone");
        } else if (event.error === "no-speech") {
            console.log("Không nghe thấy giọng nói (Timeout)");
        } else if (event.error === "audio-capture") {
            alert("Không tìm thấy microphone");
        } else if (event.error === "aborted") {
            console.log("Mic bị hủy lệnh ngầm (aborted)");
        }
    };

    recognition.onend = function () {
        isListening = false;
        console.log("MIC END");
        
        voiceBtn.innerHTML = `<i class="fa-solid fa-microphone"></i>`;
    };
}


function toggleVoiceSearch() {
    if (!recognition) {
        alert("Trình duyệt không hỗ trợ voice search");
        return;
    }
    window.focus();
    if (isListening) {
        console.log("Yêu cầu chủ động dừng mic...");
        recognition.stop(); 
    } else {
        console.log("START LISTENING (Yêu cầu bật mic)");
        try {
            recognition.start(); 
        } catch (error) {
            console.log("Không thể start mic do xung đột trạng thái:", error);
        }
    }
}
function toggleCardMenu(btn){
    document.querySelectorAll(".menu-dropdown").forEach(menu => {
        if(menu !== btn.nextElementSibling){
            menu.style.display = "none";
        }
    });
    const menu = btn.nextElementSibling;
    menu.style.display =
        menu.style.display === "block"
        ? "none"
        : "block";
}
document.addEventListener("click", function(){

    document.querySelectorAll(".menu-dropdown").forEach(menu => {
        menu.style.display = "none";
    });

});
function editSong(id){
    loadPage("edit_song&id=" + id);
}

function deleteSong(id){

    document.getElementById("confirmModal").style.display = "flex";

    document.getElementById("confirmDeleteBtn").onclick = function(){

        fetch(BASE_URL + "delete_song", {
            method: "POST",
            headers:{
                "Content-Type":"application/x-www-form-urlencoded"
            },
            body:"id=" + id
        })
        .then(res => res.json())
        .then(data => {

            if(data.success){

                document
                    .querySelector(`.card[data-id='${id}']`)
                    ?.remove();

                closeConfirm();

                showToast("✓ Đã xóa bài hát");

            }else{

                closeConfirm();

                showToast("✗ Xóa thất bại", "error");
            }
        });
    };
}

function closeConfirm(){
    document.getElementById("confirmModal").style.display = "none";
}
let currentAddSongId = null;

function addToPlaylist(songId){

    currentAddSongId = songId;
    const popup = document.getElementById("playlistPopup");
    if (!popup) {
        console.error("playlistPopup chưa được render");
        return;
    }
    popup.style.display = "flex";
}

document.addEventListener("click", function(e){
    let popup =
        document.getElementById("playlistPopup");

    if(e.target === popup){
        popup.style.display = "none";
    }
});

function saveToPlaylist(playlistId, songId){
    fetch(
        BASE_URL + "index.php?url=add_song_playlist",
        {
            method:"POST",
            headers:{
                "Content-Type":
                "application/x-www-form-urlencoded"
            },
            body:
                "playlist_id=" + playlistId +
                "&song_id=" + songId
        }
    )
    .then(res => res.json())
    .then(data => {
        if(data.success){
            showToast("✓ Đã thêm vào playlist");
            document.getElementById("playlistPopup").style.display = "none";
        }else{

            showToast("X Thêm thất bại", "error");
        }
    });
}
function showToast(message, type = "success") {

    const toast = document.getElementById("toast");

    toast.textContent = message;

    toast.classList.remove("success", "error");

    toast.classList.add(type);
    toast.classList.add("show");

    setTimeout(() => {
        toast.classList.remove("show");
    }, 2500);
}
function addToHistoryUI(song){
    let historyList =
        document.getElementById("history-list");

    if(!historyList) return;
    let emptyText =
        historyList.querySelector("p");
    if(emptyText){
        emptyText.remove();
    }

    let oldItem =
        historyList.querySelector(
            `[data-id='${song.id}']`
        );
    if(oldItem){
        oldItem.remove();
    }

    let html = `
        <div class="history-item"
            data-id="${song.id}"
            onclick="playSong(
                '${song.file}',
                '${song.track.replace(/'/g, "\\'")}',
                '${song.artist.replace(/'/g, "\\'")}',
                '${BASE_URL}ok/images/${song.image}',
                0,
                ${song.id}
            )">
            <img src="${BASE_URL}ok/images/${song.image}">
            <div class="history-info">
                <div class="history-song">
                    ${song.track}
                </div>
                <small>${song.artist}</small>
            </div>
        </div>
    `;
    historyList.insertAdjacentHTML(
        "afterbegin",
        html
    );
}
document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll(".nav-link");
    links.forEach(item => {
        item.addEventListener("click", function () {
            const currentActive = document.querySelector(".nav-link.active");
            if (currentActive) {
                currentActive.classList.remove("active");
            }
            this.classList.add("active");
        });
    });
});
function openUpdateProfile(){
    let modal = document.getElementById("updateProfileModal");
    if(modal){
        modal.style.display = "flex";
    }else{
        console.log("Không tìm thấy modal");
    }
}
function closeUpdateProfile(){
    document.getElementById("updateProfileModal")
        .style.display = "none";
}
const updateForm =
document.getElementById("updateProfileForm");
if(updateForm){
    updateForm.addEventListener("submit", async(e)=>{
        e.preventDefault();

        document.getElementById(
            "oldPasswordError"
        ).innerText = "";
        document.getElementById(
            "confirmPasswordError"
        ).innerText = "";
        let formData =
            new FormData(updateForm);
        let response = await fetch(
            updateForm.action,
            {
                method:"POST",
                body:formData
            }
        );
        let data = await response.json();
        if(data.old_password_error){
            document.getElementById(
                "oldPasswordError"
            ).innerText =
                data.old_password_error;
        }
        if(data.confirm_password_error){

            document.getElementById(
                "confirmPasswordError"
            ).innerText =
                data.confirm_password_error;
        }

        if(data.success){
            let overlay =
            document.getElementById(
                "successOverlay"
            );
            overlay.style.display =
                "flex";
            setTimeout(()=>{
                location.reload();
            },2000);
        }
    });
}
document.addEventListener("click", function (e) {

    const card = e.target.closest(".js-play");
    if (!card) return;
    const container = card.closest(".music-grid") || card.closest(".history-list");
    if (!container) {
        playSong(
            card.dataset.file || "",
            card.dataset.track || "",
            card.dataset.artist || "",
            card.dataset.image || "",
            0,
            card.dataset.id || 0
        );

        return;
    }
    playlist = Array.from(
        container.querySelectorAll(".js-play")
    ).map(el => ({
        id: el.dataset.id,
        file: el.dataset.file,
        track: el.dataset.track,
        artist: el.dataset.artist,
        image: el.dataset.image
    }));
    currentIndex = playlist.findIndex(
        s => s.id == card.dataset.id
    );

    playSong(
        card.dataset.file || "",
        card.dataset.track || "",
        card.dataset.artist || "",
        card.dataset.image || "",
        currentIndex,
        card.dataset.id || 0
    );
});
function downloadSong(url) {
    const a = document.createElement("a");
    a.href = url;
    a.download = "";
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}
document.addEventListener("DOMContentLoaded", function () {

    const createForm = document.getElementById("createPlaylistForm");

    if (!createForm) return;

    createForm.addEventListener("submit", async function (e) {

        e.preventDefault();

        const formData = new FormData(this);

        try {

            const response = await fetch(
                "index.php?url=create_playlist",
                {
                    method: "POST",
                    body: formData
                }
            );

            const result = await response.json();

            if (result.success) {

                const playlistHTML = `
                    <div class="card"
                         data-id="${result.playlist.id}"
                         onclick="loadPlaylistSongs(${result.playlist.id}, '${result.playlist.name}')">

                        <div class="card-menu">
                            <div class="menu-btn"
                                 onclick="event.stopPropagation();toggleCardMenu(this)">
                                <i class="fa-solid fa-ellipsis"></i>
                            </div>

                            <div class="menu-dropdown">
                                <div class="menu-item"
                                     onclick="event.stopPropagation();
                                     openEditPlaylist(
                                        ${result.playlist.id},
                                        '${result.playlist.name}',
                                        '${result.playlist.image}'
                                     )">
                                    <i class="fa-solid fa-pen"></i>Edit
                                </div>

                                <div class="menu-item"
                                     onclick="event.stopPropagation();
                                     deletePlaylist(${result.playlist.id})">
                                    <i class="fa-solid fa-trash"></i>Delete
                                </div>
                            </div>
                        </div>

                        <img src="${result.playlist.image}">
                        <div class="card-title">
                            ${result.playlist.name}
                        </div>
                    </div>
                `;

                document
                    .getElementById("playlist-list")
                    .insertAdjacentHTML("afterbegin", playlistHTML);

                closePlaylistModal();

                createForm.reset();

            } else {

                alert(result.message || "Tạo playlist thất bại");

            }

        } catch (error) {

            console.error(error);
            alert("Có lỗi xảy ra");

        }

    });

});