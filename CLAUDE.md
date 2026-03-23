# Agent notes — wa-apps/maxbots

## Структура проекта
- Приложение: `/wa-apps/maxbots/`
- Рабочие материалы: `.work/` (не в git)
- Локализация: `locale/ru_RU/LC_MESSAGES/maxbots.po` + `.mo`
- Главный шаблон: `templates/actions/backend/BackendDefault.html`
- Основной класс API: `lib/classes/maxbotsApi.class.php`
- SDK: `lib/vendors/max-bot-api-client/bushlanov-dev/max-bot-api-client-php/` v1.5.0

## Мессенджер Max
- Регистрация бота: @MasterBot в приложении Max → `/newbot`
- Официальная документация API: https://dev.max.ru
- SDK: bushlanov-dev/max-bot-api-client-php (PHP 8.2+)
- Базовый URL API: https://platform-api.max.ru

## Класс maxbotsApi
- Наследует `BushlanovDev\MaxMessengerBot\Api`
- Конструктор: `new maxbotsApi(BOT_TOKEN)` — аналогично telegramApi
- Методы совместимости с Telegram-плагинами:
  - `sendMessage(['chat_id'=>..., 'text'=>...])` — array-вызов поддержан в вендоре
  - `deleteMessage(['message_id'=>...])` — array-вызов поддержан в вендоре
  - `sendPhoto/sendDocument/sendAudio/sendVideo/sendVoice/sendAnimation` — array params
  - `sendChatAction`, `editMessageText`, `editMessageCaption` — array params
  - `setWebhook`, `deleteWebhook`, `getWebhookInfo`, `getWebhookUpdate`
  - `getMe`, `answerCallbackQuery`
  - `replyKeyboardMarkup`, `replyKeyboardHide`, `forceReply` — возвращают JSON-строку
  - `maxPost($endpoint, $body)`, `maxGet($endpoint, $query)` — низкоуровневые запросы

## Изменения в вендоре
Документация: `.work/vendor-changes.md`
Вкратце:
1. Убраны типы у всех констант (`const string` → `const`) — совместимость с PHP 8.2
2. `sendMessage()` — первый параметр `int|array|null`, поддержка array-вызова
3. `deleteMessage()` — параметр `string|array`, поддержка array-вызова
4. `psr/log` — убран тип `string|\Stringable` у `$message` в `NullLogger::log` и `LoggerTrait::log` — совместимость с более старыми версиями `Psr\Log\LoggerInterface`, которые могут быть загружены из Webasyst-ядра

## Локализация
- Шаблоны используют `{_wp('maxbots', 'строка')}` — с явным указанием домена
- После правки `.po` обязательно перекомпилировать: `msgfmt maxbots.po -o maxbots.mo`
- Кеш переводов сбрасывается только на сервере (локальная очистка бесполезна)

## Сервер
- Демо: https://covoxx.ru/webasyst/ (admin / demoglobin)
- Приложение: https://covoxx.ru/webasyst/maxbots/

## Webasyst UI
- Приложение использует UI 2.0 (`'ui' => '2.0'` в app.php)
- Классы: `wa-box`, `wa-box-padding`, `flexbox`, `space-4`, `align-center`, `a-page-header`, `hint`, `field-group`

## Поселение для вебхука
- Задаётся в разделе «Сайт» → скрытое поселение
- Рекомендуемый формат URL: `maxbots` + случайный хеш, например `maxbots4e0214025588cde184591b300784b9d4`

## MCP Webasyst

MCP подключён к локальной установке Webasyst (`/Volumes/Data/repos/telegram.covoxx.ru/`).

### Чтение структуры проекта
```
mcp__webasyst__list_webasyst_apps()               # список всех приложений
mcp__webasyst__get_app_info(app_id)               # инфо о приложении (maxbots, shop, site...)
mcp__webasyst__list_app_plugins(app_id)           # плагины приложения
mcp__webasyst__get_plugin_info(app_id, plugin_id) # инфо о плагине
mcp__webasyst__list_app_themes(app_id)            # темы приложения
mcp__webasyst__list_app_widgets(app_id)           # виджеты приложения
mcp__webasyst__get_routing_config(app_id)         # маршрутизация
mcp__webasyst__get_system_config()                # системная конфигурация
```

### Генерация структуры и кода
```
mcp__webasyst__create_app_structure(app_id, app_name)            # создать новое приложение
mcp__webasyst__create_plugin_structure(app_id, plugin_id, name)  # создать плагин
mcp__webasyst__create_action(app_id, module, action_names)       # создать action/controller
mcp__webasyst__create_model(app_id, table_name)                  # создать модель БД
mcp__webasyst__create_widget(app_id, widget_id, widget_name)     # создать виджет дашборда
mcp__webasyst__create_theme(app_id, theme_id, theme_name)        # создать тему
```

### UI и локализация
```
mcp__webasyst__create_ui_component(component_type, component_name, target_path)
# Типы: table, form, modal, drawer, chips, tabs, alert, paging, dropdown, card, ...

mcp__webasyst__generate_color_scheme(app_id, primary_color, ...)  # CSS-переменные цветов
mcp__webasyst__create_responsive_layout(app_id)                   # Desktop+Mobile layout
mcp__webasyst__validate_ui_usage(project_path)                    # проверить UI 2.0
mcp__webasyst__enable_webasyst_ui(project_type, target_path)      # подключить UI 2.0

mcp__webasyst__generate_po_template(app_id)    # создать/обновить .po шаблон
mcp__webasyst__compile_mo(app_id, locale)      # скомпилировать .mo из .po
```

### Проверка и публикация
```
mcp__webasyst__analyze_project(project_path)          # анализ проекта
mcp__webasyst__check_project_compliance(project_path) # проверка UI/локализации
mcp__webasyst__prepare_release_bundle(project_path)   # собрать архив для публикации
mcp__webasyst__run_webasyst_cli(command, args)         # CLI-команды Webasyst
```

### Важно
- MCP работает с **локальными файлами**, не с удалённым сервером
- `compile_mo` через MCP компилирует локально — на сервер нужно деплоить отдельно
- Для создания UI-компонентов указывать `target_path` = путь к приложению/плагину

## Полезные ссылки
- Max Bot API: https://dev.max.ru
- SDK на Packagist: https://packagist.org/packages/bushlanov-dev/max-bot-api-client-php
- Webasyst блог (Shop-Script 12, Headless API): https://www.webasyst.ru/blog/
