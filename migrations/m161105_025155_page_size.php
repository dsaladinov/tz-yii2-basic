<?php

use yii\db\Migration;

/**
 * Создание и вставка данных в таблцу настройки кол-ва стр.
 * Class m161105_025155_page_size
 */
class m161105_025155_page_size extends Migration
{
    /**
     * @var string Название таблицы
     */
    private $_tableName = 'page_size';

    /**
     * Миграция
     */
    public function up()
    {
        $this->createTable($this->_tableName, [
            'id' => $this->primaryKey(3),
            'value' => $this->integer(3)
        ]);

        $this->insert($this->_tableName, [
            'value' => 2
        ]);
    }

    /**
     * Откат
     */
    public function down()
    {
        $this->dropTable($this->_tableName);
    }
}
