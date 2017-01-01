<?php

use yii\db\Migration;

/**
 * Создание и вставка данных в таблицу юзеров
 * Class m161028_112926_user
 */
class m161028_112926_user extends Migration
{
    /**
     * @var string Название таблицы
     */
    private $_tableName = 'user';

    /**
     * Миграция
     * @throws \yii\base\Exception
     */
    public function up()
    {
        $this->createTable($this->_tableName, [
            'id' => $this->primaryKey(6),
            'username' => $this->string(128),
            'email' => $this->string(128),
            'password' => $this->string(128),
            'activation_hash' => $this->string(128)
        ]);

        $values = [
            ['username' => 'admin', 'email' => 'admin@gmail.com', 'password' => Yii::$app->security->generatePasswordHash('123456')],
            ['username' => 'moderator', 'email' => 'moderator@gmail.com', 'password' => Yii::$app->security->generatePasswordHash('123456')],
            ['username' => 'user', 'email' => 'user@gmail.com', 'password' => Yii::$app->security->generatePasswordHash('123456')]
        ];

        foreach($values as $value) {
            $this->insert($this->_tableName, [
                'username' => $value['username'],
                'email' => $value['email'],
                'password' => $value['password']
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
