<?php

use yii\db\Migration;
use app\models\Article;

/**
 * Создание и вставка данных в таблицу Статей
 * Class m161031_154829_article
 */
class m161031_154829_article extends Migration
{
    /**
     * @var string Название таблицы
     */
    private $_tableName = 'article';

    /**
     * Миграция
     */
    public function up()
    {
        $this->createTable($this->_tableName, [
            'id' => $this->primaryKey(6),
            'title' => $this->string(128),
            'text' => $this->text()
        ]);

        for($i = 1; $i <= 20; $i++) {
            $this->insert($this->_tableName, [
                'id' => $i,
                'title' => 'Тестовая статья №'.$i,
                'text' => 'Текст тестовой статьи №'.$i,
            ]);

            $article = Article::findOne(['id' => $i]);

            $article->on(Article::AFTER_INSERT_ARTICLE, [$article, 'sendNotification'], [
                'code' => Article::AFTER_INSERT_ARTICLE,
            ]);

            $article->trigger(Article::AFTER_INSERT_ARTICLE);
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
