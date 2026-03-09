# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Проект

**kogda-igra.ru** — календарь ролевых игр. PHP-приложение на Apache, развёртывается в Kubernetes через Docker.

## Сборка и деплой

```bash
# Запустить локально (порт 8080, с PostgreSQL в соседнем контейнере)
docker compose up --build
```

`public_html/` монтируется как bind mount, поэтому изменения в PHP-файлах видны сразу без пересборки. Переменные окружения берутся из `.env` (не в репо) и жёстко заданных значений в `docker-compose.yml`.

CI/CD: GitHub Actions собирает образ и пушит в `ghcr.io`. Версионирование — SemVer через PowerShell-скрипт. Деплой в Kubernetes через `kubectl apply` + kustomize. Деплой на prod — ручной (`workflow_dispatch`), на dev — автоматический при пуше в master.

Нет тестов, нет линтера.

## Архитектура

### Стек
- PHP + Apache (базовый образ `ghcr.io/joinrpg/join-php-image:0.1.0`)
- PostgreSQL (несмотря на название директории `mysql-db-structure` и файла `mysql.php` — используется PostgreSQL: `ILIKE`, `date_part`, etc.)
- Конфигурация Apache: `etc/apache2/sites/kogda.conf`, документ-рут — `/var/www/html` (= `public_html/`)

### Структура `public_html/`

- **`appcode/logic/`** — слой данных (функции для работы с БД):
  - `gamebase.php` — центральная функция `_get_games()` с большим JOIN-запросом, используется повсеместно
  - `edit.php` — запись/обновление игр, слияние, удаление, управление датами
  - `gamelist.php`, `gameinfo.php` — выборки списков и деталей игр
  - `search.php`, `dictionary.php`, `updates.php`, `review.php`, `photo.php`, `polygons.php` и др.
- **`appcode/`** — рендеринг страниц, шаблоны, вспомогательные функции:
  - `sqlbase.php` — коннект к БД через `connect()` (синглтон), подключает `mysql.php` и `config.php`
  - `base_funcs.php`, `uri_funcs.php`, `forms.php`, `media.php`, `email.php`, `vk.php`
- **Маршрутизация** — каждая страница — отдельная директория с `index.php` (Apache MultiViews/htaccess)
- **`api/`** — JSON API: добавление игры (`api/game/add.php`), лайки, поиск
- **`edit/`** — административные страницы редактирования

### Паттерны кода

- Доступ к БД только через `connect()` из `sqlbase.php` — возвращает синглтон класса `Sql`
- Входные данные всегда санируются через `intval()` или `$sql->QuoteAndClean()` перед подстановкой в SQL
- Транзакции: `$sql->Run('START TRANSACTION')` / `$sql->Run('COMMIT')`
- Поле `deleted_flag`: `0` = активна, `1` = удалена, `-1` = ожидает модерации (добавлена пользователем)
- `ki_years_cache` — кэш-таблица лет, перестраивается функцией `internal_do_update_year_index()` при каждом изменении дат

### Схема БД

Основные таблицы (SQL-файлы в `mysql-db-structure/`):
- `ki_games` — игры (основная таблица)
- `ki_game_date` — даты игры (одна игра может иметь несколько дат, `order=0` — основная)
- `ki_game_types`, `ki_regions`, `ki_sub_regions`, `ki_polygons`, `ki_status` — справочники
- `ki_updates` — лог обновлений
- `ki_review`, `ki_photo` — отзывы и фото
- `ki_zayavka_allrpg` — интеграция с allrpg.info
- `ki_add_uri` — очередь игр, добавленных пользователями (ожидают модерации)

### Миграции БД

Миграции хранятся в `db-migrations/` как SQL-файлы. Выполняются вручную на базе. Именование: `add_<description>.sql`. Чеклист по добавлению новых полей в игру: `docs/adding-new-game-fields.md`.

### Секреты

Переменные окружения задаются через `kogda-igra.secret.env` (в репо — пустой шаблон). При деплои заполняются из GitHub Secrets с префиксом `KUBESECRET_`.
