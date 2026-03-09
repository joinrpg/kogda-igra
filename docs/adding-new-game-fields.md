# Как добавить новое поле в сущность игры

Чеклист файлов для изменения при добавлении нового поля в `ki_games`.

## 1. Миграция БД

Создать файл `db-migrations/add_<description>.sql`:
```sql
ALTER TABLE ki_games ADD COLUMN <field_name> varchar(40) DEFAULT NULL;
```
Выполнить вручную на базе.

## 2. `public_html/appcode/logic/gamebase.php`

- В `get_game_for_edit()`: добавить `kg."<field_name>"` к SELECT
- В `_get_games()`: добавить `kg."<field_name>"` к SELECT

## 3. `public_html/appcode/logic/edit.php`

- Сигнатура `do_game_update()`: добавить параметр `$<field_name>`
- Санитизация (после блока с `$fb_comm`): добавить обработку значения
- SET-список: добавить `"<field_name>" = $<field_name>`
- INSERT: добавить поле в список колонок и значений
- Условие аудита (строка с `internal_log_game(2, $id)`): добавить проверку изменения

## 4. `public_html/edit/game/index.php`

- В `show_form()`: добавить вызов `show_tb()` для нового поля
- В оба вызова `do_game_update()` внутри `do_save()`: добавить `get_post_field('<field_name>')`

## 5. `public_html/appcode/calendar.php`

Зависит от типа поля:
- **Иконка-ссылка** (как vk_club, telegram_channel): добавить в `write_game_icons()`
- **Контакт в колонке МГ** (как email, telegram_contact): добавить метод `get_<field>_link()` и использовать в `write_entry()`

## 6. `public_html/appcode/email.php`

В `get_game_info_text()`: добавить переменную с текстом поля и включить её в строку возврата.

## 7. `public_html/appcode/api.php`

Добавить `'<field_name>'` в массив `$whitelisted_fields`.

## Пример

Поля `telegram_channel` и `telegram_contact` добавлены по этому паттерну в коммите, добавившем данный файл.
