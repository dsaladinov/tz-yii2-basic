<?php

use yii\db\Migration;

/**
 * Создание и вставка данных в таблицу
 * Class m161030_143425_notification
 */
class m161030_143425_notification extends Migration
{
    /**
     * @var string Название таблицы
     */
    private $_tableName = 'notification';

    /**
     * Миграция
     */
    public function up()
    {
        $this->createTable($this->_tableName, [
            'id' => $this->primaryKey(6),
            'title' => $this->string(128),
            'text' => $this->text(),
            'user_id' => $this->integer(6),
            'notification_type_id' => $this->integer(3),
            'read' => $this->integer(1),
        ]);

        $this->addForeignKey(Yii::$app->security->generateRandomString(12), $this->_tableName, 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey(Yii::$app->security->generateRandomString(12), $this->_tableName, 'notification_type_id', 'notification_type', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * Откат
     */
    public function down()
    {
        $this->dropTable($this->_tableName);
    }
}
