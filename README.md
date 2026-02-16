# Боты Max для Webasyst

Платформа для создания ботов мессенджера **Max** внутри Webasyst. Само по себе приложение не имеет видимого интерфейса — функциональность добавляется через плагины, каждый из которых является отдельным ботом.

Приложение распространяется бесплатно: [github.com/itfrogs/wa-maxbots](https://github.com/itfrogs/wa-maxbots).

## Требования

- PHP **8.2** и выше
- Сайт должен быть доступен по **HTTPS** из глобальной сети

## Установка

1. Зарегистрируйте бота в Max через [@MasterBot](https://max.ru/MasterBot) (`/newbot`) и получите токен.
2. В разделе «Сайт» задайте скрытое поселение для приложения. Рекомендуем сложный URL, например `maxbots4e0214025588cde184591b300784b9d4`.
3. Установите нужный плагин и укажите в нём токен бота.

## Использование в плагине

```php
$max = new maxbotsApi(BOT_TOKEN);
```

После этого доступны все методы SDK и методы совместимости с Telegram-плагинами.
Документация: [dev.max.ru](https://dev.max.ru).

## Готовые плагины

- **Уведомления и чат** — уведомления о заказах, чат с клиентами
- **Авторизация** — вход на сайт через Max

## Справочник методов maxbotsApi

Класс `maxbotsApi` наследует `BushlanovDev\MaxMessengerBot\Api` v1.5.0. Требуется PHP 8.2+.

### Отправка сообщений

| Метод | Описание | Обязательные параметры |
|-------|----------|------------------------|
| `sendMessage(array $params)` | Отправить текстовое сообщение | chat_id или user_id, text |
| `sendPhoto(array $params)` | Отправить фото | chat_id, photo (путь к файлу) |
| `sendDocument(array $params)` | Отправить файл/документ | chat_id, document (путь к файлу) |
| `sendAudio(array $params)` | Отправить аудио | chat_id, audio (путь к файлу) |
| `sendVideo(array $params)` | Отправить видео | chat_id, video (путь к файлу) |
| `sendVoice(array $params)` | Отправить голосовое сообщение | chat_id, voice (путь к файлу) |
| `sendAnimation(array $params)` | Отправить GIF или видео без звука | chat_id, animation (путь к файлу) |
| `sendChatAction(array $params)` | Показать действие в чате | chat_id, action |

### Вебхук

| Метод | Описание |
|-------|----------|
| `setWebhook(array $params)` | Подписать бота на вебхук (url — обязательно, HTTPS) |
| `deleteWebhook(string $url)` | Отписаться от вебхука (передать текущий URL) |
| `getWebhookInfo()` | Получить список активных подписок |
| `getWebhookUpdate()` | Получить входящее обновление из тела запроса |

### Получение обновлений

| Метод | Описание |
|-------|----------|
| `getUpdates(?int $limit, ?int $timeout, ?int $marker)` | Получить обновления через long polling |
| `getMe()` | Проверить токен, получить информацию о боте |

### Редактирование и удаление сообщений

| Метод | Описание |
|-------|----------|
| `editMessageText(array $params)` | Изменить текст отправленного сообщения |
| `editMessageCaption(array $params)` | Изменить подпись к медиасообщению |
| `editMessage(string $messageId, ...)` | Нативный метод: изменить сообщение с вложениями |
| `deleteMessage(array $params)` | Удалить сообщение (message_id) |

### Клавиатуры и callback

| Метод | Описание |
|-------|----------|
| `replyKeyboardMarkup(array $params)` | JSON reply-клавиатуры (совместимость с Telegram-плагинами) |
| `replyKeyboardHide(array $params)` | Скрыть reply-клавиатуру (JSON) |
| `forceReply(array $params)` | Принудительный запрос ответа (JSON) |
| `answerCallbackQuery(array $params)` | Ответить на нажатие inline-кнопки |
| `answerOnCallback(string $callbackId, ...)` | Нативный метод ответа на callback с редактированием сообщения |

### Загрузка файлов

| Метод | Описание |
|-------|----------|
| `uploadAttachment(UploadType $type, string $filePath)` | Загрузить файл и получить объект вложения |
| `uploadFile(string $url, resource $handle, string $name)` | Загрузить файл (multipart или resumable > 10 МБ) |
| `getUploadUrl(UploadType $type)` | Получить URL для загрузки |

### Чаты и участники

| Метод | Описание |
|-------|----------|
| `getChat(int $chatId)` | Информация о чате |
| `getChats()` | Список всех чатов бота |
| `getMembers(int $chatId)` | Список участников чата |
| `getAdmins(int $chatId)` | Список администраторов чата |
| `leaveChat(int $chatId)` | Покинуть чат |
| `pinMessage(int $chatId, string $messageId)` | Закрепить сообщение |
| `unpinMessage(int $chatId)` | Открепить сообщение |

### Низкоуровневые методы

| Метод | Описание |
|-------|----------|
| `maxPost($endpoint, $body, $query)` | Прямой POST-запрос к Max Bot API |
| `maxGet($endpoint, $query)` | Прямой GET-запрос к Max Bot API |
| `request(string $method, string $uri, array $query, array $body)` | Базовый HTTP-запрос |

## Ссылки

- [dev.max.ru](https://dev.max.ru) — документация Max Bot API
- [packagist.org/packages/bushlanov-dev/max-bot-api-client-php](https://packagist.org/packages/bushlanov-dev/max-bot-api-client-php) — SDK
