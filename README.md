# Ростест — WordPress (Docker)

## Требования
- Docker + Docker Compose v2 (`docker compose`)

## Запуск
```bash
docker compose up -d
```

Открыть: `http://localhost:8080` (порт можно поменять через `WP_PORT`).

## Первый запуск (важно)
- В админке WP: `Настройки → Постоянные ссылки` → выбрать “Название записи” → **Сохранить** (нужно для `/doctors/...`).
- Активировать тему: `Внешний вид → Темы → Rostest`.
- Активировать плагин: `Плагины → Rostest Doctors`.

## ACF поля (импорт)
Если используешь ACF, импортируй группу полей из `acf/doctor-fields.json`:
- WP Admin → `Custom Fields → Tools → Import Field Groups`

## Остановка
```bash
docker compose down
```

## Сброс БД (удалит данные)
```bash
docker compose down -v
```

## База данных с демо-данными (дамп)
В репозиторий добавлен дамп текущей БД: `db/init/wordpress.sql`.

Важно: MySQL импортирует файлы из `db/init/` **только при первом создании volume**. Чтобы гарантированно подняться с дампом:
```bash
docker compose down -v
docker compose up -d
```

## Переменные окружения (опционально)
Скопировать `.env.example` в `.env` и поменять значения при необходимости.

## Что уже сделано
- CPT `doctors` + таксономии `specialization` (hierarchical) и `city` (non-hierarchical) — в плагине `wp-content/plugins/rostest-doctors/`
- Шаблоны: `wp-content/themes/rostest/single-doctors.php`, `wp-content/themes/rostest/archive-doctors.php`
- Архив: 9 на страницу + пагинация, фильтры через GET (`specialization`/`city`/`sort`)

## Где что лежит
- Плагин (данные + логика запросов): `wp-content/plugins/rostest-doctors/`
  - CPT: `wp-content/plugins/rostest-doctors/includes/post-types.php`
  - Таксономии: `wp-content/plugins/rostest-doctors/includes/taxonomies.php`
  - Архивный query (9/страница + фильтры + сортировка): `wp-content/plugins/rostest-doctors/includes/query.php`
- Тема (вывод/вёрстка): `wp-content/themes/rostest/`
  - Главная: `wp-content/themes/rostest/front-page.php` (и `home.php` как fallback)
  - Архив врачей: `wp-content/themes/rostest/archive-doctors.php`
  - Single врача: `wp-content/themes/rostest/single-doctors.php`

## URL для проверки
- Главная: `http://localhost:8080/`
- Архив врачей: `http://localhost:8080/doctors/`
- Single врача: `http://localhost:8080/doctors/<slug>/`

## Вход в админку
- URL: `http://localhost:8080/wp-admin/`
- Логин: `rostest`
- Пароль: `rostest`

## Фильтры/сортировка (GET)
Примеры:
- `http://localhost:8080/doctors/?city=moskva`
- `http://localhost:8080/doctors/?specialization=kardiologiya&sort=rating`
- `http://localhost:8080/doctors/?sort=price`

Параметры:
- `specialization` — slug термина таксономии `specialization`
- `city` — slug термина таксономии `city`
- `sort` — `rating` | `price` | `experience`

## Почему `pre_get_posts`
Фильтры/сортировка/пагинация сделаны через `pre_get_posts` для **main query** архива CPT, чтобы:
- не ломать админку и чужие `WP_Query`
- не дублировать логику в шаблоне
