# Разворачивание приложения

Установка зависимостей на локальной машине с докером:
```shell  
docker run --rm -u "$(id -u):$(id -g)" -v $(pwd):/opt -w /opt laravelsail/php83-composer:latest composer install --ignore-platform-reqs  
```  

Создать `.env` на основе `.env.example`.

Запуск докера с помощью sail:
```shell  
./vendor/bin/sail up -d
```  

Запуск миграций с наполнением тестовыми данными:
```shell  
./vendor/bin/sail artisan migrate:fresh --seed
```  

Симлинк на storage:
```shell  
./vendor/bin/sail artisan storage:link
```

Установка пакетов:
```shell  
./vendor/bin/sail npm install
```

Сборка фронта:
```shell  
./vendor/bin/sail npm run build
```

По умолчанию приложение запускается на порту 8002. Можно поменять в .env файле (APP_PORT).

# Доступы

Тестовые аккаунты:  
Админ:  
admin@mail.com

Простые пользователи:  
first@mail.com  
second@mail.com

Везде пароль 123123123
