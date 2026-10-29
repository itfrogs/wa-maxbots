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

---

## Изменение 5 — psr/log: убран тип $message в NullLogger и LoggerTrait

**Причина:** На сервере может быть загружена более старая копия `Psr\Log\LoggerInterface`
(из Webasyst-ядра или другого приложения) с сигнатурой `log($level, $message, ...)` без union-типа.
PHP загружает первое найденное определение интерфейса, и тогда наш `NullLogger` с `string|\Stringable $message`
не совместим с ним → Fatal error.

**Файлы:**
- `psr/log/src/NullLogger.php:22`
- `psr/log/src/LoggerTrait.php:97`

```diff
- public function log($level, string|\Stringable $message, array $context = []): void
+ public function log($level, $message, array $context = []): void
```

---

## Изменение 6 — миграция на новый домен API: platform-api2.max.ru

**Причина:** Max меняет базовый URL API (дедлайн 19 июля), старый `platform-api.max.ru`
выводится из эксплуатации. См. план `.work/max-new-api.md`.

**Файл:** `src/Api.php:55`

```diff
- private const API_BASE_URL = 'https://platform-api.max.ru';
+ private const API_BASE_URL = 'https://platform-api2.max.ru';
```

`src/Laravel/MaxBotServiceProvider.php:79` (дефолт `base_url` для Laravel-интеграции) не тронут —
maxbots не использует Laravel-обвязку вендора, только `Api` напрямую через `maxbotsApi extends Api`.

---

## ⚠️ Уточнение по изменениям 3 и 4 (важно, проверено 2026-07-23)

Сверка с реальным закоммиченным кодом показала, что **патчи 3 и 4, как они были описаны выше,
никогда не были применены к вендорному `Api.php`** — сигнатуры там всегда были обычные
(`sendMessage(?int $userId, ...)`, `deleteMessage(string $messageId)`). Telegram-совместимость для
`sendMessage` реализована **только на уровне `maxbotsApi::sendMessage()`** (в `lib/classes/maxbotsApi.class.php`) —
он сам разбирает Telegram-массив и вызывает `parent::sendMessage()` уже с обычным `int $userId`. Поскольку
PHP всегда резолвит вызов метода по реальному классу объекта, а не по объявленному типу переменной,
патч на уровне вендора для `sendMessage` был бы просто мёртвым кодом — его убрали при обновлении на 1.6.5.

`deleteMessage` вообще не переопределён в `maxbotsApi.class.php` — Telegram-style
`deleteMessage(['message_id' => ...])` **не поддерживается фактически**, несмотря на то что
README.md/CLAUDE.md это заявляют. Не патчено при обновлении на 1.6.5 (сохранено поведение как было —
задача не в том, чтобы "починить", а в том, чтобы не сломать то, что реально работает).

---

## Изменение 7 — обновление вендора 1.5.0 → 1.6.5

**Причина:** У автора вышло 6 релизов после 1.5.0, включая официальный фикс миграции на
`platform-api2.max.ru` (1.6.4) и расширение допустимых версий `psr/log` (1.6.2). Плюс новые
enum-значения и типы кнопок.

**Что изменилось у автора (безопасно, аддитивно):**
- `platform-api2.max.ru` теперь прошито в самой библиотеке (наш патч №6 дублирует то же самое —
  оставлен как есть, безвредно).
- `psr/log` в `require` расширен на `^1.1||^2.0||^3.0` (было только `^3.0`) — у нас vendored-копия
  psr/log не пересобиралась, патч №5 (`NullLogger`/`LoggerTrait`) оставлен как есть, актуален.
- Новый тип кнопки `ClipboardButton` (`InlineButtonType::Clipboard`).
- Новые права `ChatAdminPermission`: `CanCall`, `PostEditDeleteMessage`, `EditMessage`, `DeleteMessage`.
- `OpenAppButton` — новый необязательный параметр `$payload` (initData мини-приложения).

**⚠️ Важное расхождение — пришлось вручную восстановить:** в 1.6.0 автор удалил классы
`ReplyKeyboardAttachment`, `ReplyKeyboardAttachmentRequest`, `ReplyKeyboardAttachmentRequestPayload`,
`DataAttachment` и убрал `AttachmentType::ReplyKeyboard` / `AttachmentType::Data` — а наш
`maxbotsApi::convertReplyMarkup()` использует `ReplyKeyboardAttachmentRequest` для Telegram-style
`reply_markup.keyboard` (обычная, не inline, клавиатура). Без этого обновление сломало бы обычные
reply-клавиатуры фатальной ошибкой "Class not found".

**Восстановлено вручную (файлы скопированы из версии 1.5.0, без изменений):**
- `src/Models/Attachments/DataAttachment.php`
- `src/Models/Attachments/ReplyKeyboardAttachment.php`
- `src/Models/Attachments/Requests/ReplyKeyboardAttachmentRequest.php`
- `src/Models/Attachments/Payloads/ReplyKeyboardAttachmentRequestPayload.php`

**Патч добавлен вручную (в новый код 1.6.5, тех же двух мест уже нет у автора):**
- `src/Enums/AttachmentType.php` — возвращены `case ReplyKeyboard = 'reply_keyboard';` и `case Data = 'data';`
- `src/ModelFactory.php` — возвращена обработка `reply_keyboard`-кнопок и match-ветки
  `AttachmentType::ReplyKeyboard => ReplyKeyboardAttachment::fromArray($data)` /
  `AttachmentType::Data => DataAttachment::fromArray($data)`

**Проверено:** синтаксис (`php -l`) всех тронутых файлов + сквозной smoke-тест (инстанцирование `Api`,
`ModelFactory::createAttachment()` для `reply_keyboard`/`data`, построение `ReplyKeyboardAttachmentRequest`,
и `maxbotsApi::convertReplyMarkup()` целиком — все ветки кнопок дают ожидаемые классы).

**Если вендор обновится снова:** проверить заново, не появились ли `ReplyKeyboardAttachment*`/`DataAttachment`
у автора (тогда наши файлы можно будет убрать), и повторить это же ручное восстановление, если всё ещё нет.

## Изменение 8 — обновление вендора 1.6.5 → 1.8.0 (2026-10-30)

**Причина:** с июля 2026 MAX принимает команды бота только через `PATCH /me/commands`; в 1.7.0 автор
добавил `Api::editBotCommands()`, `editBotInfo()` объявлен устаревшим. Плюс события комментариев,
`bot_admin_permissions_changed`, проверка `initData` мини-приложения, пропуск неизвестных апдейтов.

**Как обновляли:** `composer` напрямую не ставит 1.8.0 (требует PHP >= 8.3, на сервере 8.2), поэтому
скачаны чистые 1.6.5 и 1.8.0 с `--ignore-platform-req=php`, разница «чистая 1.6.5 -> наш вендор»
(`diff -ruN`) накатана на `src/` версии 1.8.0, заменён только `src/` пакета (зависимости не менялись).

**Что снова применено:** патч №1-2 (убраны типы у констант: `Api.php`, новый `WebApp/InitDataValidator.php`),
восстановлены `ReplyKeyboardAttachment*`, `DataAttachment` и `case ReplyKeyboard`/`Data` в `AttachmentType`/`ModelFactory`
(автор их не вернул).

**Проверено:** `php -l` всех файлов `src/`, нет синтаксиса 8.3 (`const string`, `::{`, `json_validate`,
`#[Override]`), `maxbotsApi` загружается и наследует `editBotCommands`, reply/inline-клавиатуры собираются.

**Если вендор обновится снова:** повторить ту же процедуру. Метаданные `composer/installed.json` не обновлялись
(показывают 1.6.5), на работу не влияют.
