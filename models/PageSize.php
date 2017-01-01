<?php

namespace app\models;

use Yii;

/**
 * Это модель класса для таблицы "page_size".
 *
 * @property integer $id Первичный ключ
 * @property integer $value Значение
 */
class PageSize extends \yii\db\ActiveRecord
{
    /**
     * @return string Название таблицы
     */
    public static function tableName()
    {
        return 'page_size';
    }

    /**
     * @return array Правила валидации
     */
    public function rules()
    {
        return [
            [['id', 'value'], 'required'],
            [['id', 'value'], 'integer'],
        ];
    }

    /**
     * @return array Заголовки атрбитутов
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value' => 'Значение',
        ];
    }
}
