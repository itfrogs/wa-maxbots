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
     * @return array
     */
    public function getWebhookUpdate(): array
    {
        $body = file_get_contents('php://input');
        return json_decode($body, true) ?? [];
    }

    // -----------------------------------------------------------------------
    // Информация о боте (Telegram-compatible)
    // -----------------------------------------------------------------------

    /**
     * Получить информацию о боте.
     *
     * Telegram: getMe()
     *
     * @return \BushlanovDev\MaxMessengerBot\Models\BotInfo
     */
    public function getMe()
    {
        return $this->getBotInfo();
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
