<?php
namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;



/**
 * Смена пользователем E-Mail.
 */

class ChangeEmailForm extends Model
{
    public $email;
    public $user;
    public $validation_email;



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required', 'message' => 'Это поле обязательно для заполнения'],
            ['email', 'email', 'message' => 'Проверьте правильность вашего Email'],
            ['email', 'string', 'max' => 60],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Пользователь с таким eMail уже зарегистрирован.'],
        ];
    }

    public function changeEmail()
    {
        if ($this->validate()) {
            $user = User::findByUsername(Yii::$app->user->identity->username);
            $this->validation_email = $user->generateValidationCodeEmail();
            $user->email = $this->email;
            $user->validation_email = $this->validation_email;
            if ($user->save()) {
                $this->sendEmail();
                Yii::$app->session->setFlash('success', 'Вы успешно изменили E-Mail. На почту указанную Вами при регистрации отправлено письмо с инструкцией для активации аккаунта.');
                return true;
            }
        }
    }


    /**
     * Отправка пользователю ссылки для проверки почты
     */
    private function sendEmail() {
        Yii::$app->mail->compose()
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo($this->email)
            ->setSubject('Завершение регистрации')
            ->setTextBody(Yii::$app->urlManager->createAbsoluteUrl(['site/about', 'username' => Yii::$app->user->identity->username, 'validation_email' => $this->validation_email]))
            ->send();
    }
}