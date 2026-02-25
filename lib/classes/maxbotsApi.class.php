<?php
/**
 * Created by PhpStorm.
 * User: snark | itfrogs.ru
 * Date: 4/2/18
 * Time: 8:53 PM
 */

/*
 * Подключаем вендора https://packagist.org/packages/bushlanov-dev/max-bot-api-client-php#1.5.0
 */

if (PHP_VERSION_ID >= 80200) {
    require_once wa()->getAppPath('', 'maxbots') . '/lib/vendors/max-bot-api-client/autoload.php';
} else {
    throw new waException('PHP 8.2 or higher is required.');
}

use BushlanovDev\MaxMessengerBot\Api;
use BushlanovDev\MaxMessengerBot\Enums\MessageFormat;
use BushlanovDev\MaxMessengerBot\Enums\SenderAction;
use BushlanovDev\MaxMessengerBot\Enums\UploadType;
use BushlanovDev\MaxMessengerBot\Models\MessageLink;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\CallbackButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Inline\LinkButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Buttons\Reply\SendMessageButton;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Requests\InlineKeyboardAttachmentRequest;
use BushlanovDev\MaxMessengerBot\Models\Attachments\Requests\ReplyKeyboardAttachmentRequest;

/**
 * Wraps a normalized Telegram-style message array.
 * Provides ->toArray() for backward-compatible $message->toArray() calls in plugin controllers.
 */
class maxbotsMessageWrapper
{
    public function __construct(private array $messageArray) {}

    public function toArray(): array
    {
        return $this->messageArray;
    }
}

/**
 * Normalizes a raw MAX Messenger webhook update (JSON body) to a Telegram-compatible format.
 *
 * The plugin controller expects:
 *   $result->toArray()    — top-level array, e.g. ['callback_query' => [...]]
 *   $result->getMessage() — maxbotsMessageWrapper|null for text/command messages
 *
 * MAX update_type → Telegram equivalent mapping:
 *   message_created  → $message (with from.id, text, message_id, chat.id)
 *   bot_started      → $message (text='/start [payload]', message_id=null → callback path)
 *   message_callback → toArray()['callback_query'] (with from.id, data)
 */
class maxbotsUpdateWrapper
{
    /** @var array Telegram-normalized top-level data (e.g. ['callback_query' => ...]) */
    private array $normalizedArray = [];

    /** @var maxbotsMessageWrapper|null */
    private ?maxbotsMessageWrapper $message = null;

    public function __construct(array $rawUpdate)
    {
        $this->normalize($rawUpdate);
    }

    private function normalize(array $raw): void
    {
        $updateType = $raw['update_type'] ?? null;

        switch ($updateType) {

            case 'message_created':
                $msg       = $raw['message'] ?? [];
                $sender    = $msg['sender']  ?? [];
                $body      = $msg['body']    ?? [];
                $nameParts = explode(' ', $sender['name'] ?? '', 2);

                $this->message = new maxbotsMessageWrapper([
                    'message_id' => $body['mid'] ?? null,
                    'from' => [
                        'id'            => $sender['user_id'] ?? null,
                        'is_bot'        => $sender['is_bot'] ?? false,
                        'username'      => $sender['username'] ?? '',
                        'first_name'    => $nameParts[0] ?? '',
                        'last_name'     => $nameParts[1] ?? '',
                        'language_code' => $raw['user_locale'] ?? null,
                    ],
                    // Mirror Telegram behaviour: for private dialogs chat.id == from.id
                    'chat' => [
                        'id' => $sender['user_id'] ?? null,
                    ],
                    'text' => $body['text'] ?? '',
                ]);
                break;

            case 'bot_started':
                $user      = $raw['user'] ?? [];
                $nameParts = explode(' ', $user['name'] ?? '', 2);
                $payload   = $raw['payload'] ?? '';
                // Synthesize a /start command so the controller's command detection works
                $text = '/start' . ($payload !== '' ? ' ' . $payload : '');

                // message_id = null → controller uses callback-style dispatch (no group_id required)
                $this->message = new maxbotsMessageWrapper([
                    'message_id' => null,
                    'from' => [
                        'id'            => $user['user_id'] ?? null,
                        'is_bot'        => $user['is_bot'] ?? false,
                        'username'      => $user['username'] ?? '',
                        'first_name'    => $nameParts[0] ?? '',
                        'last_name'     => $nameParts[1] ?? '',
                        'language_code' => null,
                    ],
                    'chat' => [
                        'id' => $user['user_id'] ?? null,
                    ],
                    'text' => $text,
                ]);
                break;

            case 'message_callback':
                $callback  = $raw['callback'] ?? [];
                $user      = $callback['user'] ?? [];
                $nameParts = explode(' ', $user['name'] ?? '', 2);

                $this->normalizedArray = [
                    'callback_query' => [
                        'id'   => $callback['callback_id'] ?? null,
                        'from' => [
                            'id'            => $user['user_id'] ?? null,
                            'is_bot'        => $user['is_bot'] ?? false,
                            'username'      => $user['username'] ?? '',
                            'first_name'    => $nameParts[0] ?? '',
                            'last_name'     => $nameParts[1] ?? '',
                            'language_code' => null,
                        ],
                        'data' => $callback['payload'] ?? '',
                    ],
                ];
                break;

            case 'bot_stopped':
                // Пользователь заблокировал/удалил бота.
                // Передаём user_id чтобы контроллер мог пометить его как blocked.
                $user      = $raw['user'] ?? [];
                $nameParts = explode(' ', $user['name'] ?? '', 2);

                $this->normalizedArray = [
                    'bot_stopped' => [
                        'user_id'    => $user['user_id'] ?? null,
                        'username'   => $user['username'] ?? '',
                        'first_name' => $nameParts[0] ?? '',
                        'last_name'  => $nameParts[1] ?? '',
                    ],
                ];
                break;

            default:
                // Unknown or empty update — leave everything empty
                break;
        }
    }

    public function toArray(): array
    {
        return $this->normalizedArray;
    }

    public function getMessage(): ?maxbotsMessageWrapper
    {
        return $this->message;
    }
}

/**
 * Compatibility wrapper for SDK readonly model objects.
 *
 * Adds ->get('field_name') method used by legacy Telegram-style plugin code,
 * while keeping direct camelCase property access working too.
 *
 * Field name mapping (Telegram SDK snake_case → MAX SDK camelCase):
 *   get('username')   → ->username
 *   get('user_id')    → ->userId
 *   get('first_name') → ->firstName
 *   get('last_name')  → ->lastName
 *   get('is_bot')     → ->isBot
 *
 * @example
 *   $me = $api->getMe();
 *   $me->get('username');   // Telegram-style
 *   $me->username;          // MAX SDK-style — works too
 */
class maxbotsModelWrapper
{
    public function __construct(private readonly object $model) {}

    /**
     * Get property by snake_case or camelCase name.
     */
    public function get(string $key): mixed
    {
        // 1. Прямой доступ — camelCase ('username', 'userId', ...)
        if (property_exists($this->model, $key)) {
            return $this->model->$key;
        }

        // 2. snake_case → camelCase: 'first_name' → 'firstName', 'user_id' → 'userId'
        $camel = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
        if (property_exists($this->model, $camel)) {
            return $this->model->$camel;
        }

        return null;
    }

    /**
     * Прозрачный доступ к свойствам через $obj->field (camelCase или snake_case).
     */
    public function __get(string $name): mixed
    {
        return $this->get($name);
    }

    /**
     * Прокси вызовов методов на оригинальную модель ($me->toArray(), ...).
     */
    public function __call(string $name, array $args): mixed
    {
        return $this->model->$name(...$args);
    }

    public function __isset(string $name): bool
    {
        return $this->get($name) !== null;
    }
}

/**
 * Wrapper over Max Messenger Bot API with a Telegram-compatible interface.
 *
 * Allows plugins originally written for the Telegram app to work
 * with Max Messenger by mapping Telegram-style method calls to Max Bot API.
 *
 * @see https://dev.max.ru
 */
class maxbotsApi extends Api
{
    /**
     * Fallback version — overridden by child classes (e.g. plugin API).
     * Prevents fatal errors if VERSION is referenced on maxbotsApi directly.
     */
    const VERSION = 'unknown';

    /**
     * @param string $token  Bot access token from @MasterBot
     * @throws \InvalidArgumentException
     */
    public function __construct(string $token)
    {
        parent::__construct($token);
    }

    // -----------------------------------------------------------------------
    // Отправка сообщений (Telegram-compatible)
    // -----------------------------------------------------------------------

    /**
     * Отправить фото.
     *
     * Telegram: sendPhoto(['chat_id' => ..., 'photo' => '/path/to/file.jpg'])
     *
     * @param array $params  chat_id (int), photo (string — путь к файлу), caption (string)
     * @return \BushlanovDev\MaxMessengerBot\Models\Message
     */
    public function sendPhoto(array $params)
    {
        $attachment = $this->uploadAttachment(UploadType::Image, $params['photo']);
        return parent::sendMessage(
            userId: isset($params['user_id']) ? (int)$params['user_id'] : null,
            chatId: isset($params['chat_id']) ? (int)$params['chat_id'] : null,
            text: $params['caption'] ?? null,
            attachments: [$attachment],
        );
    }

    /**
     * Отправить документ/файл.
     *
     * Telegram: sendDocument(['chat_id' => ..., 'document' => '/path/to/file'])
     *
     * @param array $params  chat_id (int), document (string — путь к файлу), caption (string)
     * @return \BushlanovDev\MaxMessengerBot\Models\Message
     */
    public function sendDocument(array $params)
    {
        $attachment = $this->uploadAttachment(UploadType::File, $params['document']);
        return parent::sendMessage(
            userId: isset($params['user_id']) ? (int)$params['user_id'] : null,
            chatId: isset($params['chat_id']) ? (int)$params['chat_id'] : null,
            text: $params['caption'] ?? null,
            attachments: [$attachment],
        );
    }

    /**
     * Отправить аудио.
     *
     * Telegram: sendAudio(['chat_id' => ..., 'audio' => '/path/to/audio.mp3'])
     *
     * @param array $params  chat_id (int), audio (string — путь к файлу), caption (string)
     * @return \BushlanovDev\MaxMessengerBot\Models\Message
     */
    public function sendAudio(array $params)
    {
        $attachment = $this->uploadAttachment(UploadType::Audio, $params['audio']);
        return parent::sendMessage(
            userId: isset($params['user_id']) ? (int)$params['user_id'] : null,
            chatId: isset($params['chat_id']) ? (int)$params['chat_id'] : null,
            text: $params['caption'] ?? null,
            attachments: [$attachment],
        );
    }

    /**
     * Отправить видео.
     *
     * Telegram: sendVideo(['chat_id' => ..., 'video' => '/path/to/video.mp4'])
     *
     * @param array $params  chat_id (int), video (string — путь к файлу), caption (string)
     * @return \BushlanovDev\MaxMessengerBot\Models\Message
     */
    public function sendVideo(array $params)
    {
        $attachment = $this->uploadAttachment(UploadType::Video, $params['video']);
        return parent::sendMessage(
            userId: isset($params['user_id']) ? (int)$params['user_id'] : null,
            chatId: isset($params['chat_id']) ? (int)$params['chat_id'] : null,
            text: $params['caption'] ?? null,
            attachments: [$attachment],
        );
    }

    /**
     * Отправить голосовое сообщение.
     *
     * Telegram: sendVoice(['chat_id' => ..., 'voice' => '/path/to/voice.ogg'])
     *
     * @param array $params  chat_id (int), voice (string — путь к файлу)
     * @return \BushlanovDev\MaxMessengerBot\Models\Message
     */
    public function sendVoice(array $params)
    {
        $attachment = $this->uploadAttachment(UploadType::Audio, $params['voice']);
        return parent::sendMessage(
            userId: isset($params['user_id']) ? (int)$params['user_id'] : null,
            chatId: isset($params['chat_id']) ? (int)$params['chat_id'] : null,
            attachments: [$attachment],
        );
    }

    /**
     * Отправить анимацию/GIF (загружается как видео в Max).
     *
     * Telegram: sendAnimation(['chat_id' => ..., 'animation' => '/path/to/anim.gif'])
     *
     * @param array $params  chat_id (int), animation (string — путь к файлу), caption (string)
     * @return \BushlanovDev\MaxMessengerBot\Models\Message
     */
    public function sendAnimation(array $params)
    {
        $attachment = $this->uploadAttachment(UploadType::Video, $params['animation']);
        return parent::sendMessage(
            userId: isset($params['user_id']) ? (int)$params['user_id'] : null,
            chatId: isset($params['chat_id']) ? (int)$params['chat_id'] : null,
            text: $params['caption'] ?? null,
            attachments: [$attachment],
        );
    }

    /**
     * Показать действие в чате (набор текста, загрузка и т.д.).
     *
     * Telegram: sendChatAction(['chat_id' => ..., 'action' => 'typing'])
     *
     * Маппинг действий:
     *   typing          → SenderAction::Typing
     *   upload_photo    → SenderAction::SendingPhoto
     *   upload_video    → SenderAction::SendingVideo
     *   upload_audio    → SenderAction::SendingAudio
     *   upload_document → SenderAction::SendingFile
     *
     * @param array $params  chat_id (int), action (string)
     * @return \BushlanovDev\MaxMessengerBot\Models\Result
     */
    public function sendChatAction(array $params)
    {
        $actionMap = [
            'typing'          => SenderAction::Typing,
            'upload_photo'    => SenderAction::SendingPhoto,
            'upload_video'    => SenderAction::SendingVideo,
            'upload_audio'    => SenderAction::SendingAudio,
            'upload_document' => SenderAction::SendingFile,
        ];

        $action = $actionMap[$params['action'] ?? 'typing'] ?? SenderAction::Typing;
        return $this->sendAction((int)$params['chat_id'], $action);
    }

    /**
     * Редактировать текст сообщения.
     *
     * Telegram: editMessageText(['message_id' => '...', 'text' => '...'])
     *
     * @param array $params  message_id (string), text (string)
     * @return \BushlanovDev\MaxMessengerBot\Models\Result
     */
    public function editMessageText(array $params)
    {
        return $this->editMessage(
            messageId: (string)$params['message_id'],
            text: $params['text'] ?? null,
        );
    }

    /**
     * Редактировать подпись к медиасообщению.
     *
     * Telegram: editMessageCaption(['message_id' => '...', 'caption' => '...'])
     *
     * @param array $params  message_id (string), caption (string)
     * @return \BushlanovDev\MaxMessengerBot\Models\Result
     */
    public function editMessageCaption(array $params)
    {
        return $this->editMessage(
            messageId: (string)$params['message_id'],
            text: $params['caption'] ?? null,
        );
    }

    // -----------------------------------------------------------------------
    // Вебхук (Telegram-compatible)
    // -----------------------------------------------------------------------

    /**
     * Установить вебхук.
     *
     * Telegram: setWebhook(['url' => 'https://...'])
     * Max:      subscribe(url, secret, updateTypes)
     *
     * @param array $params  url (string), secret (string)
     * @return \BushlanovDev\MaxMessengerBot\Models\Result
     */
    public function setWebhook(array $params)
    {
        return $this->subscribe(
            url: $params['url'],
            secret: $params['secret'] ?? null,
        );
    }

    /**
     * Удалить вебхук.
     *
     * Telegram: deleteWebhook()
     * Max: unsubscribe(url) — нужно передать URL текущего вебхука
     *
     * @param string $url  URL текущего вебхука
     * @return \BushlanovDev\MaxMessengerBot\Models\Result
     */
    public function deleteWebhook(string $url = '')
    {
        return $this->unsubscribe($url);
    }

    /**
     * Получить информацию о текущем вебхуке.
     *
     * Telegram: getWebhookInfo()
     *
     * @return array
     */
    public function getWebhookInfo(): array
    {
        $subscriptions = $this->getSubscriptions();
        return !empty($subscriptions) ? (array)$subscriptions[0] : [];
    }

    /**
     * Получить входящее обновление из тела HTTP-запроса (вебхук).
     *
     * Telegram: getWebhookUpdate()
     *
     * Возвращает maxbotsUpdateWrapper, который нормализует MAX-формат обновления
     * в Telegram-совместимый формат для контроллера плагина.
     *
     * @return maxbotsUpdateWrapper
     */
    public function getWebhookUpdate(): maxbotsUpdateWrapper
    {
        $body = file_get_contents('php://input');
        if (empty($body)) {
            return new maxbotsUpdateWrapper([]);
        }
        $data = json_decode($body, true) ?? [];
        return new maxbotsUpdateWrapper($data);
    }

    // -----------------------------------------------------------------------
    // Отправка сообщений с поддержкой Telegram-стиля (array $params)
    // -----------------------------------------------------------------------

    /**
     * Отправить текстовое сообщение.
     *
     * Принимает как Telegram-стиль (массив параметров), так и MAX SDK (именованные аргументы).
     *
     * Telegram: sendMessage(['chat_id' => ..., 'text' => ..., 'parse_mode' => 'HTML', 'reply_markup' => ...])
     *
     * Поддерживаемые ключи массива:
     *   chat_id                 (int)    — ID пользователя / чата
     *   text                    (string) — текст сообщения
     *   parse_mode              (string) — 'HTML' | 'Markdown'
     *   reply_markup            (string|array) — JSON или массив с inline_keyboard / keyboard
     *   disable_web_page_preview (bool)  — отключить превью ссылок
     *
     * @param array|int|null $userId  Массив Telegram-параметров ИЛИ userId (MAX SDK стиль)
     */
    public function sendMessage(
        array|int|null $userId = null,
        ?int $chatId = null,
        ?string $text = null,
        ?array $attachments = null,
        ?MessageFormat $format = null,
        ?MessageLink $link = null,
        bool $notify = true,
        bool $disableLinkPreview = false,
    ): \BushlanovDev\MaxMessengerBot\Models\Message {

        // Telegram-style array call
        if (is_array($userId)) {
            $params        = $userId;
            $targetUserId  = isset($params['chat_id']) ? (int)$params['chat_id'] : null;
            $msgText       = $params['text'] ?? null;
            $noLinkPreview = !empty($params['disable_web_page_preview']);

            // Map Telegram parse_mode → MAX MessageFormat
            $msgFormat = null;
            if (!empty($params['parse_mode'])) {
                $msgFormat = strtoupper($params['parse_mode']) === 'HTML'
                    ? MessageFormat::Html
                    : MessageFormat::Markdown;
            }

            // Convert Telegram reply_markup → MAX button attachments
            $msgAttachments = $this->convertReplyMarkup($params['reply_markup'] ?? null);

            return parent::sendMessage(
                userId: $targetUserId,
                chatId: null,
                text: $msgText,
                attachments: $msgAttachments,
                format: $msgFormat,
                disableLinkPreview: $noLinkPreview,
            );
        }

        // MAX SDK named-param style — pass through unchanged
        return parent::sendMessage(
            $userId, $chatId, $text, $attachments, $format, $link, $notify, $disableLinkPreview
        );
    }

    /**
     * Конвертирует Telegram reply_markup (JSON или массив) в массив MAX-вложений с кнопками.
     *
     * Поддерживает:
     *   inline_keyboard  → InlineKeyboardAttachmentRequest (CallbackButton / LinkButton)
     *   keyboard         → ReplyKeyboardAttachmentRequest  (SendMessageButton)
     *   hide_keyboard    → null (кнопки убраны)
     *
     * @return \BushlanovDev\MaxMessengerBot\Models\Attachments\Requests\AbstractAttachmentRequest[]|null
     */
    private function convertReplyMarkup(mixed $replyMarkup): ?array
    {
        if (empty($replyMarkup)) {
            return null;
        }

        $markup = is_string($replyMarkup)
            ? (json_decode($replyMarkup, true) ?? [])
            : (array)$replyMarkup;

        if (empty($markup)) {
            return null;
        }

        // --- Inline keyboard (полностью поддерживается в MAX) ---
        if (!empty($markup['inline_keyboard'])) {
            $rows = [];
            foreach ($markup['inline_keyboard'] as $row) {
                $maxRow = [];
                foreach ($row as $button) {
                    $btnText = $button['text'] ?? '';
                    if (!empty($button['web_app']['url'])) {
                        $maxRow[] = new LinkButton($btnText, $button['web_app']['url']);
                    } elseif (!empty($button['url'])) {
                        $maxRow[] = new LinkButton($btnText, $button['url']);
                    } else {
                        $maxRow[] = new CallbackButton($btnText, $button['callback_data'] ?? '');
                    }
                }
                if (!empty($maxRow)) {
                    $rows[] = $maxRow;
                }
            }
            return !empty($rows) ? [new InlineKeyboardAttachmentRequest($rows)] : null;
        }

        // --- Reply keyboard (поддерживается в MAX как ReplyKeyboard) ---
        if (!empty($markup['keyboard'])) {
            $rows = [];
            foreach ($markup['keyboard'] as $row) {
                $maxRow = [];
                foreach ($row as $button) {
                    // Кнопки могут быть stdClass (из replyKeyboardMarkup()), массивом или строкой
                    $btnText = is_object($button)
                        ? ($button->text ?? '')
                        : (is_array($button) ? ($button['text'] ?? '') : (string)$button);
                    if ($btnText !== '') {
                        $maxRow[] = new SendMessageButton((string)$btnText);
                    }
                }
                if (!empty($maxRow)) {
                    $rows[] = $maxRow;
                }
            }
            return !empty($rows) ? [new ReplyKeyboardAttachmentRequest($rows)] : null;
        }

        // hide_keyboard / force_reply / неизвестный формат → без кнопок
        return null;
    }

    // -----------------------------------------------------------------------
    // Информация о боте (Telegram-compatible)
    // -----------------------------------------------------------------------

    /**
     * Получить информацию о боте.
     *
     * Telegram: getMe()
     *
     * Возвращает maxbotsModelWrapper для совместимости с ->get('field') из
     * старого Telegram SDK, а также поддерживает прямой доступ к свойствам.
     *
     * @return maxbotsModelWrapper
     */
    public function getMe(): maxbotsModelWrapper
    {
        return new maxbotsModelWrapper($this->getBotInfo());
    }

    // -----------------------------------------------------------------------
    // Callback-запросы (Telegram-compatible)
    // -----------------------------------------------------------------------

    /**
     * Ответить на нажатие inline-кнопки.
     *
     * Telegram: answerCallbackQuery(['callback_query_id' => '...', 'text' => '...'])
     * Max:      answerOnCallback(callbackId, notification)
     *
     * @param array $params  callback_query_id (string), text (string)
     * @return \BushlanovDev\MaxMessengerBot\Models\Result
     */
    public function answerCallbackQuery(array $params)
    {
        return $this->answerOnCallback(
            callbackId: (string)$params['callback_query_id'],
            notification: $params['text'] ?? null,
        );
    }

    // -----------------------------------------------------------------------
    // Клавиатуры (Telegram-compatible)
    // -----------------------------------------------------------------------

    /**
     * Создать reply-клавиатуру.
     * В Max Messenger reply-клавиатуры не поддерживаются,
     * метод возвращает JSON-строку для обратной совместимости с плагинами.
     *
     * @param array $params  keyboard (array), resize_keyboard (bool), one_time_keyboard (bool)
     * @return string  JSON
     */
    public function replyKeyboardMarkup(array $params): string
    {
        return json_encode($params);
    }

    /**
     * Скрыть reply-клавиатуру.
     *
     * @param array $params
     * @return string  JSON
     */
    public static function replyKeyboardHide(array $params = []): string
    {
        return json_encode(array_merge(['hide_keyboard' => true, 'selective' => false], $params));
    }

    /**
     * Принудительный запрос ответа от пользователя.
     *
     * @param array $params
     * @return string  JSON
     */
    public static function forceReply(array $params = []): string
    {
        return json_encode(array_merge(['force_reply' => true, 'selective' => false], $params));
    }

    // -----------------------------------------------------------------------
    // Низкоуровневые методы (Telegram-compatible)
    // -----------------------------------------------------------------------

    /**
     * Прямой POST-запрос к Max Bot API.
     * Аналог telegramPost() из telegramApi.
     *
     * @param string $endpoint  Эндпоинт, например '/messages'
     * @param array  $body      Тело запроса
     * @param array  $query     Query-параметры
     * @return array
     */
    public function maxPost(string $endpoint, array $body = [], array $query = []): array
    {
        return $this->request('POST', $endpoint, $query, $body);
    }

    /**
     * Прямой GET-запрос к Max Bot API.
     * Аналог telegramGet() из telegramApi.
     *
     * @param string $endpoint  Эндпоинт, например '/me'
     * @param array  $query     Query-параметры
     * @return array
     */
    public function maxGet(string $endpoint, array $query = []): array
    {
        return $this->request('GET', $endpoint, $query);
    }
}
