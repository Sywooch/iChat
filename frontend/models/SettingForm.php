<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;


/**
 * ContactForm is the model behind the contact form.
 */

class SettingForm extends Model
{
    public $username;
    public $lastname;
    public $firstname;
    public $email;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['firstname', 'filter', 'filter' => 'trim'],
            ['firstname', 'required', 'message' => 'Это поле обязательно для заполнения'],

            ['lastname', 'filter', 'filter' => 'trim'],
            ['lastname', 'required', 'message' => 'Это поле обязательно для заполнения'],

            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required', 'message' => 'Это поле обязательно для заполнения'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Пользователь с таким именим уже существует.', 'filter' => ['!=', 'username', Yii::$app->user->identity->username]],
            ['username', 'string', 'min' => 3, 'max' => 25],
        ];
    }



    public function changeSetting()
    {
            $this->_user = User::findByUsername(Yii::$app->user->identity->username);
            $user = $this->_user;
            if ($this->username != Yii::$app->user->identity->username) {
                $user->username = $this->username;
            }
            $user->firstname = $this->firstname;
            $user->lastname = $this->lastname;
            if ($user->save(false)) {
                Yii::$app->session->setFlash('success', 'Настройки аккаунта успешно обновлены.');
            }
        return null;
    }
}
