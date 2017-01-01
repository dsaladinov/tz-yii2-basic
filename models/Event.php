<?php

namespace app\models;

use Yii;
use yii\web\HttpException;

/**
 * Это модель класса для таблицы "событие".
 *
 * @property integer $id Первичный ключи
 * @property string $code Код
 * @property string $name Название
 * @property string $params Параметры для вставки
 */
class Event extends \yii\db\ActiveRecord
{
    /**
     * @return string Название таблицы
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * @return array Правила валидации
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'string', 'max' => 32],
            [['params'], 'string', 'max' => 64],
            [['code', 'name', 'params'], 'required']
        ];
    }

    /**
     * @return array Заголовки для полей
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Код',
            'name' => 'Название события',
            'params' => 'Параметры',
        ];
    }
}
