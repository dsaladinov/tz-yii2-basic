Тестовое задание по yii2-basic - новостной сайт
Потраченное время на выполнение: 10 дней.
Салайдин уулу Данияр
------------------
Установка :
yii migrate --migrationPath=@yii/rbac/migrations/
yii rbac/init
yii migrate
```
Доступ к сайту
-------------------

username: admin
pass: 123456

username: moderator
pass: 123456

username: user
pass: 123456
```
Описание проекта

Интерфейс пользователя:
 - Список статей
 - Профиль пользователя 
 - Список уведомлений для юзера

Административная часть (CRUD-управление модулями приложения):

- Справочник событий. Есть три поля: Код, название и поодерживаемые параметры вставки
tz.kg/admin/event

- Справочник типов уведомлений. Есть одно поле: название
tz.kg/admin/notification-type

- Шаблоны уведомлений. Есть 4 поля: События (из таблицы событий), заголовок, текст, и флаг (для админа - служебное уведомление)
tz.kg/admin/notification-template

- Уведомления. Есть 5 полей: Заголовок, текст, кому(user_id), тип уведомления (из справочника), флаг (прочитано)
tz.kg/admin/notification

- Юзеры. У них 4 поля: username, email, pass, хеш активации
tz.kg/admin/user

- Статьи. tz.kg/admin/article

- Настройка кол-ва стр. в пагинации публичной части (в статьях)
tz.kg/admin/page-size

Решенные задачи тестового задания
------------

Используя basic шаблон фреймворка Yii2 нужно написать простейший
новостной сайт с авторизацией и оповещением пользователей о событиях.
Junior Developer:

- Регистрация и авторизация пользователей:

```php
controllers\RegistrationController.php - контроллер регистрации
models\Registration.php - модель регистрации
models\User.php - модель юзеров
models\NotificationType.php - модель типов уведомлений
views\registration\* - виды регистрации

controllers\LoginController.php - контроллер авторизации
models\Login.php - модель авторизации
views\login\* - виды авторзации
```

- При добавлении новости на сайт, оповещать зарегистрированных
пользователей по e-mail и всплывающим окном в браузере:

```php
modules\admin\controllers\ArticleController.php - контроллер статей
models\Article.php - модель статей
models\Event.php - модель событий
models\NotificationTemplate.php - модель шаблонов уведомлений
modules\admin\views\article\* - виды

controllers\NotificationController.php - контроллер уведомлений
views\notification\* - виды уведомлений
```

- Постраничный вывод превью новостей на главной странице с
дальнейшим полным просмотром. Количество превью на странице
должно быть изменяемым. Анонимный пользователь может просматривать только превью,
пользователь может просматривать полные новости:

```php
controllers\SiteController.php - главная страница
controllers\ArticleController.php - контроллер статей
views\article\* - виды статей

modules\admin\controllers\PageSizeController.php - контроллер пагинации
modules\admin\views\page-size\* - виды пагинации
```

- CRUD управление новостями и пользователями с разграничением прав.
Модератор может добавлять новости, а администратор еще и управлять пользователями:

```php
modules\admin\controllers\ArticleController.php - контроллер статей
models\Article.php - модель статей
modules\admin\views\article\* - виды статей

modules\admin\controllers\UserController.php - контроллер юзеров
models\User.php - модель юзеров
modules\admin\views\user\* - виды юзеров
```

- Сделать в настройках профиля настройку уведомлений (получать
уведомления о новых новостях только на e-mail, в браузер или и то и
другое):

```php
controllers\ProfileController.php - контроллер настроек профиля
views\profile\* - виды настроек профиля
```

- Оповещать пользователя по e-mail при изменении пароля или создания
нового пользователя администратором (выслать новому пользователю
на e-mail ссылку для активации профиля и ввода нового пароля для
дальнейшей авторизации) и оповещать администратора при
регистрации нового пользователя:

```php
modules\admin\controllers\UserController.php - контроллер юзеров
models\User.php - модель юзеров
controllers\RegistrationController.php - контроллер регистрации
modules\admin\views\user\* - виды юзеров
views\registration\* - виды регистрации
```

- Автоматическая авторизация на сайте при активации профиля:

```php
controllers\RegistrationController.php - контроллер регистрации
views\registration\* - виды регистрации
```

Middle Developer:

Реализовать все пункты из Junior Developer и добавить к ним управление
уведомлениями на основе системы событий Yii2 с следующими
требованиями:

- Возможность добавления событий к любым моделям (тригерим
события), отслеживание событий (слушаем события модели):

```php
models\Article.php - модель статей
models\User.php - модель юзеров
```

- Возможность управления уведомлениями к событиям из веб-
интерфейса. С указанием в качестве адресата группу/роль
пользователей и выбором типа уведомления (e-mail и/или браузер).
Реализовать возможность управления шаблонами текстов уведомлений
с авто подстановкой туда информации из уведомления. Например,
подстановка имени пользователя или ссылки на появившуюся новость
в тексте и заголовке уведомления.

```php
modules\admin\controllers\EventController.php - контроллер событий
models\Event.php - модель событий
modules\admin\views\event\* - виды событий

modules\admin\controllers\NotificationController.php - контроллер уведомлений
models\Notification.php - модель уведомлений
modules\admin\views\notification\* - виды уведомлений

modules\admin\controllers\NotificationTemplateController.php - контроллер шаблонов событий
models\NotificationTemplate.php - модель шаблонов событий
modules\admin\views\notification-template\* - виды шаблонов событий

modules\admin\controllers\NotificationTypeController.php - контроллер типов уведомлений
models\NotificationType.php - модель типов уведомлений
modules\admin\views\notification-type\* - виды типов уведомлений
```

- Предусмотреть возможность легкого добавления новых типов
уведомлений. Например, в telegram или push (описать в readme как
добавлять новые типы):

```php
    Добавляем строку в таблицу "notification_type"
```

- Немедленная отправка уведомлений выбранным
пользователям/ролям/всем по требованию администратора без события
в модели:

```php
modules\admin\controllers\NotificationController.php - контроллер уведомлений
models\Notification.php - модель уведомлений
modules\admin\views\notification\* - виды уведомлений
```"# tz-yii2-basic" 
