const chatContainer = document.getElementById("chatContainer");
const chatToggle = document.getElementById("chatToggle");
const chatBody = document.getElementById("chatBody");
const chatInput = document.getElementById("chatInput");

window.toggleChat = function () {
    if(!chatContainer) return;

    chatContainer.classList.toggle("active");

    if(chatContainer.classList.contains("active")){

        const toggleRect = chatToggle.getBoundingClientRect();
        const gap = 10;

        chatContainer.style.left = toggleRect.left + "px";
        chatContainer.style.top = (toggleRect.top - chatContainer.offsetHeight - gap) + "px";

        if (parseInt(chatContainer.style.top) < 10) {
            chatContainer.style.top = (toggleRect.bottom + gap) + "px";
        }

        setTimeout(() => {
            chatInput.focus();
        }, 200);
    }
};


window.handleEnter = function(event){
    if(event.key === "Enter"){
        event.preventDefault();
        sendMessage();
    }
};


function addMessage(type, text){
    chatBody.innerHTML += `
        <div class="message-wrapper ${type}">
            <div class="message ${type}">
                ${text}
            </div>
        </div>
    `;
    scrollBottom();
}


function scrollBottom(){
    chatBody.scrollTo({
        top: chatBody.scrollHeight,
        behavior: "smooth"
    });
}

function addLoading(){
    const loadingWrapper = document.createElement("div");
    loadingWrapper.className = "message-wrapper ai";
    loadingWrapper.id = "loadingMessage";

    loadingWrapper.innerHTML = `
        <div class="message ai loading-message">
            <span class="typing">
                AI đang phân tích 🎧...
            </span>
        </div>
    `;

    chatBody.appendChild(loadingWrapper);
    scrollBottom();
}

function removeLoading(){
    const loading = document.getElementById("loadingMessage");
    if(loading){
        loading.remove();
    }
}

window.sendMessage = async function () {

    const message = chatInput.value.trim();

    if (message === "") return;

    addMessage("user", message);
    chatInput.value = "";

    addLoading();

    try {

        const response = await fetch(
            "index.php?url=chatbot/send",
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    message: message
                })
            }
        );

        const text = await response.text();

        console.log("SERVER RESPONSE:");
        console.log(text);

        let data;

        try {

            data = JSON.parse(text);

        } catch (e) {

            console.error("JSON Parse Error:", e);
            console.error(text);

            removeLoading();

            addMessage(
                "ai",
                "❌ Server trả về dữ liệu không hợp lệ."
            );

            return;
        }

        removeLoading();

        /* ==========================
           XỬ LÝ ACTION TỪ AI
        ========================== */

        if (
            data.action === "play" &&
            data.songs &&
            data.songs.length > 0
        ) {

            const song = data.songs[0];

            if (typeof playSong === "function") {

                playSong(
                    song.file.replace(
                        "../public/ok/music/",
                        ""
                    ),
                    song.track,
                    song.artist,
                    song.image.replace(
                        "../public/ok/images/",
                        ""
                    ),
                    0,
                    song.id
                );
            }
        }

        if (data.action === "pause") {

            if (
                typeof audio !== "undefined" &&
                audio
            ) {
                audio.pause();
            }
        }

        if (
            data.action === "next" &&
            typeof playNext === "function"
        ) {
            playNext();
        }

        if (
            data.action === "previous" &&
            typeof playPrev === "function"
        ) {
            playPrev();
        }

        if (data.action === "create_playlist") {

            setTimeout(() => {
                location.reload();
            }, 1500);
        }

        /* ==========================
           HIỂN THỊ TIN NHẮN AI
        ========================== */

        let html = `
            <div class="message-wrapper ai">
                <div class="message ai">
                    ${data.reply}
                </div>
            </div>
        `;

        if (
            data.songs &&
            Array.isArray(data.songs) &&
            data.songs.length > 0
        ) {

            data.songs.forEach(song => {

                html += `
                    <div class="message-wrapper ai">
                        <div
                            class="song-card"
                            onclick="playSongFromChat(
                                '${song.file}',
                                '${song.track.replace(/'/g, "\\'")}',
                                '${song.artist.replace(/'/g, "\\'")}',
                                '${song.image}',
                                ${song.id}
                            )"
                            style="cursor:pointer;"
                        >
                            <img
                                src="${song.image}"
                                alt="${song.track}"
                            >

                            <div class="song-info">

                                <h4>${song.track}</h4>

                                <p>${song.artist}</p>

                            </div>

                        </div>
                    </div>
                `;
            });
        }

        chatBody.innerHTML += html;

        scrollBottom();

    } catch (error) {

        console.error(
            "Lỗi chat API:",
            error
        );

        removeLoading();

        addMessage(
            "ai",
            "❌ Không thể kết nối AI chatbot."
        );
    }
};

window.playSongFromChat = function(file, track, artist, image, id = 0) {

    if (typeof playSong === "function") {

        playSong(
            file.replace("../public/ok/music/", ""),
            track,
            artist,
            image.replace("../public/ok/images/", ""),
            0,
            id
        );

    } else {
        console.log("Không tìm thấy hàm playSong()");
    }
};

let isDragging = false;
let offsetX = 0;
let offsetY = 0;

chatToggle.addEventListener("mousedown", (e) => {
    isDragging = true;

    const rect = chatToggle.getBoundingClientRect();

    offsetX = e.clientX - rect.left;
    offsetY = e.clientY - rect.top;

    chatToggle.style.cursor = "grabbing";
});

document.addEventListener("mousemove", (e) => {
    if (!isDragging) return;

    let x = e.clientX - offsetX;
    let y = e.clientY - offsetY;

    chatToggle.style.left = x + "px";
    chatToggle.style.top = y + "px";
    chatToggle.style.right = "auto";
    chatToggle.style.bottom = "auto";

    const gap = 15;

    chatContainer.style.left = x + "px";
    chatContainer.style.top = (y - chatContainer.offsetHeight - gap) + "px";
    chatContainer.style.right = "auto";
    chatContainer.style.bottom = "auto";
});

document.addEventListener("mouseup", () => {
    isDragging = false;
    chatToggle.style.cursor = "grab";
});

document.addEventListener("click", function(event) {

    if (!chatContainer.classList.contains("active")) return;

    const isClickInsideChat = chatContainer.contains(event.target);
    const isClickInsideToggle = chatToggle.contains(event.target);

    if (!isClickInsideChat && !isClickInsideToggle) {
        chatContainer.classList.remove("active");
    }
});