# Два тестовых задания выполненных на Symfony 5 

## Дано:
1. Разработать небольшой новостной сайт-визитку
    
    На главной странице выводятся 3 новости (отображаем заголовок, краткий текст) отсортированных по дате добавления, с пагинатором и возможностью сортировки по дате в прямом и обратном порядке. Выводятся только активные новости.
    В качестве меню реализовать список  категорий в которых есть новости. Вложенность категорий не ограничена.
    
    Ссылка на страницу новости должна быть вида /news/news_title.
    Страница /news/news_title должна отображать заголовок новости, текст новости, дату создания новости, а также форму с комментариями под новостью.
    
    Страница /admin должна проверять авторизацию пользователя. Логин login, пароль password
    
    Администратор может:
    1) Просматривать список новостей, добавлять/редактировать/удалять новость.
    2) Просматривать список категорий, добавлять/редактировать/удалять категорию.
    
    При добавлении категории нужно указать:
    1) Название
    2) Родительская категория
    
    При добавлении новости нужно указать:
    1) заголовок
    2) категорию
    3) анонс
    4) подробный текст

2. Отобразить 15 новостей с сайта rbc.ru
   Спарсить (программно) первые 15 новостей с rbk.ru (блок сkева) и вставить в базу данных (составить структуру самому) или в файл. Вывести все новости, сократив текст до 200 символов в качестве описания, со ссылкой на полную новость с кнопкой подробнее. На полной новости выводить картинку если есть в новости.
   Было бы плюсом: возможность расширения функционала парсинга для добавления дополнительных новостных ресурсов.

## Решение
Стек: doker && docker-compose, mysql, php-fpm, php-cli, supervisor, rabbitmq, nginx
Реализация далека от идеальной (реализованый MVP лучше, чем нереализованый идеальный продукт)
Разумеется не предназначено для использования в продакшене, исключительно с целью разработки

### Среда

```
git clone git@gitlab.com:pendalff/test-symfony5.git

cd test-symfony5

test-symfony5$ docker-compose build

test-symfony5$ docker-compose run php-cli /var/www/bin/console doctrine:migrations:migrate -n

test-symfony5$ docker-compose run php-cli /var/www/bin/console doctrine:fixtures:load -n

test-symfony5$ docker-compose up -d
```

### Новостной сайт-визитка

Сайт [http://localhost:8877/](http://localhost:8877/)

Админка [http://localhost:8877/admin](http://localhost:8877/admin) (login / password)

### 15 новостей с сайта rbc.ru

Точка входа `App\Command\ParseCommand`, большая часть кода в пространстве `App\Parser`

Запустить команду
```
test-symfony5$ docker-compose run php-cli /var/www/bin/console app:parse 
```
Секунд через 10 посмотреть [http://localhost:8877/category/1?enteriesLimit=15](http://localhost:8877/category/1?enteriesLimit=15)