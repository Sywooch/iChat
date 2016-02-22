<?php
namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;



/**
* ContactForm is the model behind the contact form.
*/

class ChangePasswordForm extends Model
{
    public $password;
    public $new_password;
    public $user;
    public $password_hash;


    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            ['password', 'required', 'message' => 'Это поле обязательно для заполнения'],

            ['new_password', 'required', 'message' => 'Это поле обязательно для заполнения'],
            ['new_password', 'string', 'min' => 6, 'message' => 'Пароль должен состоять минимум из 6 символов.'],
        ];
    }

    public function changePassword()
    {
       if (Yii::$app->security->validatePassword($this->password, Yii::$app->user->identity->password_hash)) {
           $user = User::findByUsername(Yii::$app->user->identity->username);
           $user->password_hash = Yii::$app->security->generatePasswordHash($this->new_password);
           $user->save(false);
           return true;
       }else {
           $this->addError('password', 'Неверный пароль.');
       }
    }
}