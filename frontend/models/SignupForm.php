<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Форма регистрации
 */
class SignupForm extends Model
{
    public $username;
    public $firstname;
    public $lastname;
    public $avatar;
    public $email;
    public $password;
    public $validation_email;
    public $verifyCode;

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
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Пользователь с таким именим уже существует.'],
            ['username', 'string', 'min' => 3, 'max' => 25],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required', 'message' => 'Это поле обязательно для заполнения'],
            ['email', 'email', 'message' => 'Проверьте правильность вашего Email'],
            ['email', 'string', 'max' => 60],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Пользователь с таким eMail уже зарегистрирован.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'message' => 'Пароль должен состоять минимум из 6 символов.'],

            ['verifyCode', 'captcha', 'message' => 'Не верный проверочный код.'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->firstname = $this->firstname;
            $user->lastname = $this->lastname;
            $user->email = $this->email;
            $user->avatar = Yii::$app->urlManager->createAbsoluteUrl('frontend/web/img') . '/avatar/user.jpg';
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $this->validation_email = $user->generateValidationCodeEmail();
            if ($user->save()) {
                $this->sendEmail();
                Yii::$app->session->setFlash('success', 'Вы успешно зарегистрировались. На почту указанную Вами при регистрации отправлено письмо с инструкцией для активации аккаунта.');
            }
        }
        return null;
    }

    /**
     * Отправка пользователю ссылки для проверки почты
     */
    private function sendEmail() {
        Yii::$app->mail->compose()
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo($this->email)
            ->setSubject('Завершение регистрации')
            ->setTextBody(Yii::$app->urlManager->createAbsoluteUrl(['site/about', 'username' => $this->username, 'validation_email' => $this->validation_email]))
            ->send();
    }


}
