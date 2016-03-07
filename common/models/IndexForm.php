<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class IndexForm extends Model
{
    public $message;



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['message'], 'required', 'message' => ''],
        ];
    }

    public function sendMessage() {
        if ($this->validate()) {
            $message = new Messages();
            $message->from_user = Yii::$app->user->identity->id;
            $message->for_user = Yii::$app->request->get('id_user');
            $message->message = $this->message;
            $message->chat_name = 'private';
            $message->date_time = date("Y-m-d H:i:s");
            if ($message->save()) {
                Contacts::updateContact(Yii::$app->request->get('id_user'), Yii::$app->user->identity->id);
            }
        }
    }







}