#  Easy Chat — Web Chat System


---

##  Содержание

- [ Файлы и назначение](#файлы-и-назначение)
- [ Шаблоны сообщений](#шаблоны-сообщений)
- [ Шаблон карточки пользователя](#шаблон-карточки-пользователя)
- [ CSS Файлы](#css-файлы)
- [ Внешние ссылки](#внешние-ссылки)

---

##  Файлы и назначение

###  PHP:

| Файл | Назначение |
|------|------------|
| `index.php` | Стартовая страница |
| `signUp.php` | Регистрация пользователя |
| `logIn.php` | Авторизация пользователя |
| `chat.php` | Основной интерфейс чата |
| `guestRoom.php` | Просмотр сообщений для незарегистрированных |
| `getSessionUserData.php` | Возвращает ID и роль текущего пользователя |
| `connect.php` | Подключение к базе данных |
| `chatMailRequest.php` | Отдаёт список сообщений в формате JSON |
| `sendMessage.php` | Обрабатывает отправку нового сообщения |
| `updateMessage.php` | Обновляет сообщение по ID |
| `deleteMessage.php` | Удаляет сообщение по ID |
| `userEdit.php` | Админ может изменить данные пользователя |
| `userDelete.php` | Админ может удалить пользователя |

---

## Шаблоны сообщений

### Чужое сообщение:
```html
<div class="chat-message other" data-id="1">
  <div class="msg-header">
    <span class="sender">Alice</span>
    <span class="msg-actions">
      <button>edit</button>
      <button>del</button>
    </span>
  </div>
  <p class="msg-text">Hello, how are you?</p>
  <span class="timestamp">14:35</span>
</div>
```

### Твоё сообщение:
```html
<div class="chat-message self" data-id="2">
  <div class="msg-header">
    <span class="sender">You</span>
    <span class="msg-actions">
      <button>edit</button>
      <button>del</button>
    </span>
  </div>
  <p class="msg-text">Fine, thanks!</p>
  <span class="timestamp">14:36</span>
</div>
```

---

## Шаблон карточки пользователя (в `guestRoom.php`)

```html
<div class="user-card">
  <div class="card-header">
    <h3>👤 Имя пользователя</h3>
    <span class="badge">роль</span>
  </div>
  <p>email@mail.com</p>
</div>
```

---

## CSS Файлы

| Файл | Назначение |
|------|------------|
| `body.css` | Общие стили страницы и блоков |
| `form-style.css` | Стили для форм регистрации/входа |
| `header.css` | Стили для шапки сайта |
| `headerPhone.css` | Адаптивность шапки для мобильных |
| `footer.css` | Оформление футера |
| `chatBody.css` | Отображение чата и его элементов |
| `customeSearch.css` | Поисковый и фильтрующий блок |

---

## Внешние ссылки

**Box-shadow генератор**:  
  [https://active-vision.ru/icon/box-shadow/](https://active-vision.ru/icon/box-shadow/)  
  Используется для генерации теней карточек и блоков интерфейса.
