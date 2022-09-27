# Развертывание проекта

1. `composer install`
2. При разработке использовался Laravel Sail. Команда запуска `./vendor/bin/sail up`
3. Запуск миграций `php artisan migrate`

## Используемые библиотеки

1 Для анализа тональности: https://github.com/RubixML/Sentiment
2 Для работы с API "Rick and Morty": https://github.com/nickbeen/rick-and-morty-api-php

### Работа по анализу тональности

Тренировка, проверка, а так же сам анализ полностью реализованы по документации библиотеки RubixML. Для запуска обучения
необходимо выполнить команду `php artisan training-system`
Выполнение скрипта занимает длительное время (https://prnt.sc/WonztwVJK1Jm), поэтому обученная модель добавлена в
репозиторий `storage/sentiment.rbx`

### Доступные роуты

Для более быстрой проверки добавлена коллекция по работе с API `storage/elfsight-task.postman_collection.json`

1. Эпизоды 
- `api/v1/episodes` [GET] - коллекция эпизодов. Для перехода по страницам в параметрах передать `page[number]`
- `api/v1/episodes/{id}` [GET] - ресурс по id
2. Отзывы 
- `api/v1/reviews` [GET] - коллекция отзывов. Доступные параметры:
  - для перехода по страницам `page[number]`
  - фильтр по id эпизода `filter[episode]`
  - сортировка по 'id', 'episode', 'rating'
- `api/v1/reviews` [POST] - создание отзыва на эпизод
- `api/v1/reviews/{id}` [GET] - ресурс по id