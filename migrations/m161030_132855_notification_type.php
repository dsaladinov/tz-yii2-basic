<?php

use yii\db\Migration;

/**
 * Создание и вставка данных в таблицу типов событий
 * Class m161030_164855_notification_type
 */
class m161030_132855_notification_type extends Migration
{
    /**
     * @var string Название таблицы
     */
    private $_tableName = 'notification_type';

    /**
     * Миграция
     */
    public function up()
    {
        $this->createTable($this->_tableName, [
            'id' => $this->primaryKey(3),
            'name' => $this->string(128)
        ]);

        $values = [
            ['name' => 'email'],
            ['name' => 'web']
        ];

        foreach($values as $value) {
            $this->insert($this->_tableName, [
                'name' => $value['name']
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
