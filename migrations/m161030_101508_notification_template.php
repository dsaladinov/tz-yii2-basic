<?php

use yii\db\Migration;

/**
 * Создание таблицы и вставка данных в таблицу шаблонов уведомлений
 * Class m161030_101508_notification_templates
 */
class m161030_101508_notification_template extends Migration
{
    /**
     * @var string Название таблицы
     */
    private $_tableName = 'notification_template';

    /**
     * Миграция
     */
    public function up()
    {
        $this->createTable($this->_tableName, [
            'id' => $this->primaryKey(3),
            'event_id' => $this->integer(3),
            'title' => $this->string(128),
            'text' => $this->text(),
            'duty' => $this->integer(1)
        ]);

        $values = [
            [
                'event_id' => 1,
                'title' => 'Добавлена новая статья на сайте {sitename}',
                'text' => '{username}, добавлена новая статья {title}. Чтобы прочитать полностью, перейдите по ссылке {link}',
            ],
            [
                'event_id' => 2,
                'title' => 'Ваш пароль изменен на сайте {sitename}',
                'text' => '{username}, Ваш пароль изменен администратором. Ваш новый пароль: {newPass}',
            ],
            [
                'event_id' => 3,
                'title' => 'Зарегистрировался новый пользователь на сайте {sitename}',
                'text' => 'Администратор, новый юзер {username}, прошел регистрацию. Посмотреть профиль - {linkToUser}',
                'duty' => 1
            ],
            [
                'event_id' => 3,
                'title' => 'Вы успешно прошли регистрацию на сайте {sitename}',
                'text' => '{username}, Вы зарегистрированы в системе, ваш пароль: {password}. Осталось подтвердить email – для этого перейдите по ссылке {linkActivation} ',
            ],
            [
                'event_id' => 4,
                'title' => 'Ваш аккаунт заблокирован на сайте {sitename}',
                'text' => '{username}, Ваш аккаунт заблокирован администратором',
            ],
        ];

        $this->addForeignKey(Yii::$app->security->generateRandomString(12), $this->_tableName, 'event_id', 'event', 'id', 'CASCADE', 'CASCADE');

        foreach($values as $value) {
            $this->insert($this->_tableName, [
                'event_id' => $value['event_id'],
                'title' => $value['title'],
                'text' => $value['text'],
                'duty' => $value['duty']
            ]);
        }
    }

    /**
     * Откат
     */
    public function down()
    {
        $this->dropTable($this->_tableName);
    }
}
