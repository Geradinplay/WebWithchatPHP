const  UPDATE_INTERVAL=1000;

document.addEventListener("DOMContentLoaded", () => {
    const chatBox = document.getElementById("chat-box");

    // Сначала получаем ID пользователя
    fetch('./getSessionUserId.php')
        .then(res => res.json())
        .then(userData => {
            if (userData.error) {
                console.error("Error: " + userData.error);
                return;
            }

            const userId = userData.id;

            function loadMessage() {
                // Проверка: находимся ли мы почти внизу (±10px)
                const shouldScroll =
                    Math.abs(chatBox.scrollTop + chatBox.clientHeight - chatBox.scrollHeight) < 10;
            fetch('./chatMailRequest.php')
                .then(res => res.json())
                .then(data => {
                    chatBox.innerHTML = "";

                    data.forEach(msg => {
                        const messageDiv = document.createElement("div");

                        const isMyMessage = msg.user_id == userId;
                        messageDiv.className = "chat-message " + (isMyMessage ? "self" : "other");
                        const senderClass = msg.role_id == 1 ? "adminName" : "";

                        messageDiv.innerHTML = `
              <div class="msg-header">
                <span class="sender ${senderClass}">${isMyMessage ? "You" : msg.user_name}</span>
                <span class="msg-actions">
                  <button>edit</button>
                  <button>del</button>
                </span>
              </div>
              <p class="msg-text">${msg.message}</p>
              <span class="timestamp">${new Date(msg.sent_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</span>
            `;

                        chatBox.appendChild(messageDiv);
                    });

                    if (shouldScroll) {
                        chatBox.scrollTop = chatBox.scrollHeight;
                    }
                })
                .catch(err => console.error("Error loading messages:", err));
            }
            loadMessage();
            setInterval(loadMessage, UPDATE_INTERVAL);//жизненный цикл
        })
        .catch(err => console.error("Error getting user ID:", err));






    const form = document.getElementById("message-form");
    const input = document.getElementById("message");

    form.addEventListener("submit", function (e) {
        e.preventDefault(); //отключает стандартное поведение(выключает перезагрузку страницы)
        const text = input.value.trim();
        if (text === "") return;

        fetch('./sendMessage.php', {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"//Форма в виде таблицы а не json
            },
            body: "message=" + encodeURIComponent(text)//Фикс для браузера, что бы нормально распознавал несколько слов
        })
            .then(res => res.text())//Ответ от сервака в текст
            .then(response => {
                if (response === "OK") {
                    input.value = "";//очистка инпута
                } else {
                    console.error("Send error:", response);
                }
            })
            .catch(err => console.error("Error:", err));
    });


});