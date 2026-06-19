<div class="chat-toggle" id="chatToggle" onclick="toggleChat()">
<i class="fa-solid fa-robot"></i>
</div>

<div class="chat-container" id="chatContainer">

    <div class="chat-header">
        Chatbot Music
    </div>

    <div class="chat-body" id="chatBody">
        <div class="message-wrapper ai">
            <div class="message ai">
                Xin chào 👋 <br>
                Tôi có thể giúp gì cho bạn?
            </div>
        </div>
    </div>

    <div class="chat-footer">
        <input
            type="text"
            id="chatInput"
            placeholder="Nhập tin nhắn..."
            onkeypress="handleEnter(event)"
        >
        <button class="btnsend" onclick="sendMessage()">
            ➤
        </button>
    </div>

</div>