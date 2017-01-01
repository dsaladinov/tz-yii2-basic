<?php

use yii\db\Migration;

/**
 * Создание и вставка данных для справочника событий
 * Class m161030_065852_events
 */
class m161030_065852_event extends Migration
{
    /**
     * @var string Название таблицы
     */
    private $_tableName = 'event';

    /**
     * Миграция
     */
    public function up()
    {
        $this->createTable($this->_tableName, [
            'id' => $this->primaryKey(3),
            'code' => $this->string(128),
            'name' => $this->string(128),
            'params' => $this->string(128)
        ]);

        $this->createIndex(Yii::$app->security->generateRandomString(12), $this->_tableName, 'code', true);

        $values = [
            ['code' => 'after_insert_article', 'name' => 'После добавления статьи', 'params' => '{sitename}, {username}, {title}, {link}'],
            ['code' => 'after_change_pass_user', 'name' => 'После изменения пароля юзера', 'params' => '{sitename}, {username}, {newPass}'],
            ['code' => 'after_add_user', 'name' => 'После Регистрации/добавления юзера', 'params' => '{sitename}, {username}, {password}, {linkActivation}, {linkToUser}'],
            ['code' => 'after_block_user', 'name' => 'После блокирования юзера', 'params' => '{sitename}, {username}'],
        ];

        foreach($values as $value) {
            $this->insert($this->_tableName, [
                'code' => $value['code'],
                'name' => $value['name'],
                'params' => $value['params']
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
