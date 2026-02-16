# Изменения в вендоре max-bot-api-client

Вендор: `bushlanov-dev/max-bot-api-client-php` v1.5.0
Файл: `lib/vendors/max-bot-api-client/bushlanov-dev/max-bot-api-client-php/src/Api.php`

Оригинальная версия требует PHP >= 8.3 и имеет несовместимый с Telegram-плагинами интерфейс.
Все изменения направлены на совместимость с PHP 8.2 и на Telegram-compatible API.

---

## Изменение 1 — PHP 8.2: убраны типы у публичных констант

**Причина:** Типизированные константы классов (`public const string NAME`) — это PHP 8.3.
В PHP 8.2 они не поддерживаются и вызывают `Parse error`.

```diff
- public const string LIBRARY_VERSION = '1.5.0';
- public const string API_VERSION = '1.2.5';
+ public const LIBRARY_VERSION = '1.5.0';
+ public const API_VERSION = '1.2.5';
```

---

## Изменение 2 — PHP 8.2: убраны типы у приватных констант

**Причина:** То же — `private const string/int NAME` — PHP 8.3.

```diff
- private const string API_BASE_URL = 'https://platform-api.max.ru';
- private const string METHOD_GET = 'GET';
- private const string METHOD_POST = 'POST';
- private const string METHOD_DELETE = 'DELETE';
- private const string METHOD_PATCH = 'PATCH';
- private const string METHOD_PUT = 'PUT';
- private const string ACTION_ME = '/me';
- private const string ACTION_SUBSCRIPTIONS = '/subscriptions';
- private const string ACTION_MESSAGES = '/messages';
- private const string ACTION_UPLOADS = '/uploads';
- private const string ACTION_CHATS = '/chats';
- private const string ACTION_CHATS_ACTIONS = '/chats/%d/actions';
- private const string ACTION_CHATS_PIN = '/chats/%d/pin';
- private const string ACTION_CHATS_MEMBERS_ME = '/chats/%d/members/me';
- private const string ACTION_CHATS_MEMBERS_ADMINS = '/chats/%d/members/admins';
- private const string ACTION_CHATS_MEMBERS_ADMINS_ID = '/chats/%d/members/admins/%d';
- private const string ACTION_CHATS_MEMBERS = '/chats/%d/members';
- private const string ACTION_UPDATES = '/updates';
- private const string ACTION_ANSWERS = '/answers';
- private const string ACTION_VIDEO_DETAILS = '/videos/%s';
- private const int RESUMABLE_UPLOAD_THRESHOLD_BYTES = 10 * 1024 * 1024;
+ private const API_BASE_URL = 'https://platform-api.max.ru';
+ private const METHOD_GET = 'GET';
+ ... (все константы без типов)
+ private const RESUMABLE_UPLOAD_THRESHOLD_BYTES = 10 * 1024 * 1024;
```

---

## Изменение 3 — Telegram-compatible: sendMessage принимает array

**Причина:** В плагинах Telegram вызов выглядит как:
```php
$bot->sendMessage(['chat_id' => 123, 'text' => 'Hello']);
```
Оригинальный Max API принимает именованные параметры: `sendMessage(?int $userId, ...)`.
PHP не позволяет расширять тип параметра в наследнике, поэтому изменение внесено в вендора.

```diff
- public function sendMessage(
-     ?int $userId = null,
+ public function sendMessage(
+     int|array|null $userId = null,
      ?int $chatId = null,
      ...
  ): Message {
+     // Telegram-compatible array call: sendMessage(['chat_id' => ..., 'text' => ...])
+     if (is_array($userId)) {
+         $params = $userId;
+         $userId = isset($params['user_id']) ? (int)$params['user_id'] : null;
+         $chatId = isset($params['chat_id']) ? (int)$params['chat_id'] : null;
+         $text = $params['text'] ?? null;
+         $notify = empty($params['disable_notification']);
+         $disableLinkPreview = !empty($params['disable_link_preview']);
+     }
      ...
  }
```

---

## Изменение 4 — Telegram-compatible: deleteMessage принимает array

**Причина:** В плагинах Telegram вызов выглядит как:
```php
$bot->deleteMessage(['chat_id' => 123, 'message_id' => 'mid.123']);
```
Оригинальный Max API принимает только строку.

```diff
- public function deleteMessage(string $messageId): Result
+ public function deleteMessage(string|array $messageId): Result
  {
+     // Telegram-compatible array call: deleteMessage(['chat_id' => ..., 'message_id' => '...'])
+     if (is_array($messageId)) {
+         $messageId = (string)($messageId['message_id'] ?? '');
+     }
      ...
  }
```
