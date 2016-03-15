<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;


/**
 * ContactForm is the model behind the contact form.
 */

class About extends Model
{
    public $username;
    public $validation_email;
    private $_user;


    /**
     * Получение информации из GET
     */
    public function getValue() {
        $this->username = Yii::$app->request->get('username');
        $this->validation_email = Yii::$app->request->get('validation_email');
    }

    /**
     *
     */
    public function validationEmail() {
        if ($this->_user['validation_email'] == $this->validation_email) {
            $this->updateValidationEmail();
            $this->sendEmail();
            Yii::$app->session->setFlash('success', 'Поздравляем! Ваш аккаунт активирован.');
        }elseif ($this->_user['validation_email'] == 'confirmed') {
            Yii::$app->session->setFlash('success', 'Ваш аккаунт уже активирован. Повторная проверка не требуется.');
        }elseif ($this->_user['validation_email'] != 'confirmed' && $this->_user['validation_email'] == $this->validation_email) {
            Yii::$app->session->setFlash('notConfirmed', 'Не верная ссылка, попробуйте еще!');
        }
    }

    /**
     * Обновление записи пользователя о проверке E-Mail
     * @throws \yii\db\Exception
     */
    private function updateValidationEmail() {
        Yii::$app->db->createCommand()->update('user', array('validation_email'=>'confirmed'), 'username=:username', array(':username'=>$this->username))->execute();

    }

    /**
     * Отправка сообщения пользователю об успешной регистрации
     */
    private function sendEmail() {
        Yii::$app->mail->compose()
            ->setFrom(Yii::$app->params['supportEmail'])
            ->setTo($this->_user['email'])
            ->setSubject('Подтверждение регистрации')
            ->setTextBody('Поздравляем! Ваш аккаунт активирован.')
            ->send();
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }



}