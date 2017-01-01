<?php

use yii\db\Migration;

/**
 * Создание и вставка данных в таблицу подписок юзера
 * Class m161030_134027_user_notification_type
 */
class m161030_134027_user_notification_type extends Migration
{
    /**
     * @var string Название таблицы
     */
    private $_tableName = 'user_notification_type';

    /**
     * Миграция
     */
    public function up()
    {
        $this->createTable($this->_tableName, [
            'user_id' => $this->integer(6),
            'notification_type_id' => $this->integer(3)
        ]);

        $this->addPrimaryKey('pk' ,$this->_tableName, ['user_id', 'notification_type_id']);

        $this->addForeignKey(Yii::$app->security->generateRandomString(12), $this->_tableName, 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey(Yii::$app->security->generateRandomString(12), $this->_tableName, 'notification_type_id', 'notification_type', 'id', 'CASCADE', 'CASCADE');

        $values = [
            ['user_id' => 1, 'notification_type_id' => 1],
            ['user_id' => 2, 'notification_type_id' => 1],
            ['user_id' => 3, 'notification_type_id' => 1],
            ['user_id' => 3, 'notification_type_id' => 2],
        ];

        foreach($values as $value) {
            $this->insert($this->_tableName, [
                'user_id' => $value['user_id'],
                'notification_type_id' => $value['notification_type_id']
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
