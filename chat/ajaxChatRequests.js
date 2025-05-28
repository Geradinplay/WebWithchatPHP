//Интервал обновления чата
const UPDATE_INTERVAL = 1000;

document.addEventListener("DOMContentLoaded", () => {
    // При загрузке страницы скрываем всплывающее окно редактирования
    document.getElementById("edit-popup").style.display = "none";

    const chatBox = document.getElementById("chat-box");

    // Сначала получаем ID и роль пользователя из сессии
    loadChatBox();
    function loadChatBox() {
    fetch('./getSessionUserData.php')
        .then(res => res.json())
        .then(userData => {
            if (userData.error) {
                console.error("Error: " + userData.error);
                return;
            }

            const userId = userData.id;
            const role = userData.role;

            // Загрузка сообщений
            function loadMessage() {
                // Проверка: находимся ли мы почти внизу (±10px)
                const shouldScroll =
                    Math.abs(chatBox.scrollTop + chatBox.clientHeight - chatBox.scrollHeight) < 10;

                fetch('./chatMailRequest.php')
                    .then(res => res.json())
                    .then(data => {
                        chatBox.innerHTML = ""; // очищаем старые сообщения

                        data.forEach(msg => {
                            const messageDiv = document.createElement("div");
                            const isAdminMail = msg.role_id == 1;
                            const isMyMessage = msg.user_id == userId;
                            messageDiv.className = "chat-message " + (isMyMessage ? "self" : "other");
                            const senderClass = isAdminMail ? "adminName" : "";
                            const showActions = isMyMessage || isAdminMail;

                            // Проверка ID сообщений в консоли
                            //console.log("Message ID:", msg.message_id, "Message:", msg.message);//НЕ ТРОГАТЬ логирование на случай дебага

                            // Кнопки редактирования и удаления (доступны админу и владельцу сообщения)
                            let action = (role === "admin" || msg.user_id == userId) ? `
                                <span class="msg-actions">
                                    <button class="edit-btn" data-id="${msg.message_id}">edit</button>
                                    <button class="message-del-btn" data-id="${msg.message_id}">del</button>
                                </span>` : "";

                            messageDiv.innerHTML = `
                                <div class="msg-header">
                                    <span class="sender ${senderClass}">${isMyMessage ? "You" : msg.user_name}</span>
                                    ${action}
                                </div>
                                <p class="msg-text">${msg.message}</p>
                                <span class="timestamp">${new Date(msg.sent_at).toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit'
                            })}</span>`;
                            chatBox.appendChild(messageDiv);
                        });

                        // Скролл вниз если нужно
                        if (shouldScroll) {
                            chatBox.scrollTop = chatBox.scrollHeight;
                        }
                    })
                    .catch(err => console.error("Error loading messages:", err));
            }

            loadMessage();
            setInterval(loadMessage, UPDATE_INTERVAL); // Жизненный цикл загрузки сообщений


            // --------------------------------------------------------------------------------- Всплывающее окно редактирования
            const editPopup = document.getElementById("edit-popup");
            const editInput = document.getElementById("edit-popup-input");
            const editBackBtn = document.getElementById("edit-popup-back");
            const editSendBtn = document.getElementById("edit-popup-send");

            let currentEditMessageId = null;

            // Обработчик кликов по кнопке edit
            chatBox.addEventListener("click", function (e) {
                if (e.target.classList.contains("edit-btn")) {
                    const messageDiv = e.target.closest(".chat-message");
                    const messageText = messageDiv.querySelector(".msg-text").textContent;
                    const messageId = e.target.dataset.id; // ← именно так правильно достаём ID

                    currentEditMessageId = messageId;
                    editInput.value = messageText;
                    editPopup.style.display = "flex";
                }
            });

            // Кнопка Back — скрываем окно и сбрасываем id
            editBackBtn.addEventListener("click", () => {
                editPopup.style.display = "none";
                currentEditMessageId = null;
            });

            // Кнопка Send — отправка нового текста сообщения
            editSendBtn.addEventListener("click", () => {
                const newText = editInput.value.trim();
                if (newText === "" || currentEditMessageId === null) return;

                fetch('./updateMessage.php', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `id=${encodeURIComponent(currentEditMessageId)}&message=${encodeURIComponent(newText)}`
                })
                    .then(res => res.text())
                    .then(response => {
                        if (response === "OK") {
                            currentEditMessageId = null;
                            loadMessage(); // обновление чата
                            editPopup.style.display = "none";
                        } else {
                            alert("Edit error: " + response);
                        }
                    })
                    .catch(err => console.error("Error editing message:", err));
            });
            // ---------------------------------------------------------------------------------
        })
        .catch(err => console.error("Error getting user ID:", err));
}

    // --------------------------------------------------------------------------------- Отправка нового сообщения
    const form = document.getElementById("message-form");
    const input = document.getElementById("message");

    form.addEventListener("submit", function (e) {
        e.preventDefault(); // отключает стандартное поведение (перезагрузку страницы)
        const text = input.value.trim();
        if (text === "") return;

        fetch('./sendMessage.php', {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded" // форма в виде таблицы, а не JSON
            },
            body: "message=" + encodeURIComponent(text) // экранируем текст
        })
            .then(res => res.text()) // ответ от сервера как текст
            .then(response => {
                if (response === "OK") {
                    input.value = ""; // очистка поля ввода
                    loadChatBox();
                } else {
                    console.error("Send error:", response);
                }
            })
            .catch(err => console.error("Error:", err));
    });
    // ---------------------------------------------------------------------------------
    // ---------------------------------------------------------------------------------Обработка удаления
    chatBox.addEventListener("click", function (e) {
        if (e.target.classList.contains("message-del-btn")) {
            const messageId = e.target.dataset.id;

            if (!confirm("Are you sure you want to delete this message?")) return;

            fetch('./deleteMessage.php', {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `id=${encodeURIComponent(messageId)}`
            })
                .then(res => res.text())
                .then(response => {
                    if (response === "OK") {
                        loadChatBox(); // перезагружаем чат
                    } else {
                        alert("Delete error: " + response);
                    }
                })
                .catch(err => console.error("Error deleting message:", err));
        }
    });
});
