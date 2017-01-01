<?php
/**
 * 
 * Модель авторизации
 */
namespace app\models;

use Yii;
use yii\base\Model;

class Login extends Model {

    /**
     * @var Имя_пользователя
     */
    public $username;

    /**
     * @var Пароль
     */
    public $password;

    /**
     * @var bool Запомни_меня
     */
    public $rememberMe = true;

    /**
     * @return array Заголовки полей
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
            'rememberMe' => 'Запомни меня',
        ];
    }

    /**
     * @return array Валидация входящих данных
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'], // имя пользователя и пароль не может быть пустым
            ['rememberMe', 'boolean'], // запомни меня принимается только булево значение
            ['password', 'passwordValidate'], // соответствие username и пароля
            ['username', 'rolesValidate'], // проверка на наличие ролей (заблокирован или нет)
        ];
    }

    /**
     *
     * Проверка пароля на соответсвие с username
     *
     * @param $attribute Пароль
     */
    public function passwordValidate($attribute)
    {
        if( ! $this->hasErrors()) {
            $user = $this->getUser();

            if( ! $user || ! $user->passwordValidate($this->password)) {
                $this->addError($attribute, 'Не верное имя пользователя или пароль.');
            }
        }
    }

    /**
     *
     * Проверка на наличие ролей у юзера
     *
     * @param $attribute Параметр_username
     */
    public function rolesValidate($attribute)
    {
        if( ! $this->hasErrors()) {
            $user = $this->getUser();

            $userHasRoles = count(Yii::$app->authManager->getRolesByUser($user->id));

            if( ! $userHasRoles) {
                $this->addError($attribute, 'Ваш аккаунт заблокирован или еще не прошел модерацию.');
            }
        }
    }

    /**
     * @return null|static Результат поиска юзера по username
     */
    public function getUser()
    {
        return User::findOne(['username' => $this->username]);
    }

    /**
     * @return bool Результат авторизации
     */
    public function login($autoLogin = false)
    {
        if( ! $autoLogin) {
            if ($this->validate()) {
                return $this->_login();
            }
            return false;
        }
        else {
            return $this->_login();
        }
    }

    /**
     * @return bool Результат авторизации
     */
    public function _login()
    {
        return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
    }


}
