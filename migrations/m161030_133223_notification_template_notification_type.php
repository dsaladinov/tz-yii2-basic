<?php

use yii\db\Migration;

/**
 * Создание и вставка данных в смежную таблцу между шаблонами и видами событий
 * Class m161030_110451_notification_template_notification_type
 */
class m161030_133223_notification_template_notification_type extends Migration
{
    /**
     * @var string Название таблицы
     */
    private $_tableName = 'notification_template_notification_type';

    /**
     * Миграция
     */
    public function up()
    {
        $this->createTable($this->_tableName, [
            'notification_template_id' => $this->integer(3),
            'notification_type_id' => $this->integer(3),
        ]);

        $this->addPrimaryKey('notification_template_id', $this->_tableName, ['notification_template_id', 'notification_type_id']);

        $this->addForeignKey(Yii::$app->security->generateRandomString(12), $this->_tableName, 'notification_template_id', 'notification_template', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey(Yii::$app->security->generateRandomString(12), $this->_tableName, 'notification_type_id', 'notification_type', 'id', 'CASCADE', 'CASCADE');

        $values = [
//            ['notification_template_id' => 1, 'notification_type_id' => 1],
            ['notification_template_id' => 1, 'notification_type_id' => 2],
            ['notification_template_id' => 2, 'notification_type_id' => 1],
            ['notification_template_id' => 3, 'notification_type_id' => 1],
            ['notification_template_id' => 4, 'notification_type_id' => 1],
            ['notification_template_id' => 5, 'notification_type_id' => 1],
        ];

        foreach($values as $value) {
            $this->insert($this->_tableName, [
                'notification_template_id' => $value['notification_template_id'],
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
