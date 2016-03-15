<?php
namespace frontend\models;

use common\models\ChatUsers;
use common\models\User;
use Yii;
use yii\base\Model;
use common\models\Messages;
use common\models\Contacts;
use yii\helpers\Url;


/**
 * Login form
 */
class IndexForm extends Model
{
    public $message;
    public $file;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['message'], 'required', 'message' => ''],
            //[['file'], 'file'],
            [['file'], 'file', 'extensions' => ['jpg', 'gif'], 'maxSize' => 1024*1024*2],
        ];
    }

    public function sendMessage($file_upload_name, $chat_name = 'private') {
            $message = new Messages();
            $message->from_user = Yii::$app->user->identity->id;
            if(Yii::$app->request->get('id')) {
                $message->for_user = Yii::$app->request->get('id');
            } elseif(Yii::$app->request->get('chat')) {
                $message->for_user = 0;
            }
            $message->message = $this->message;
            $message->chat_name = $chat_name;
            $message->date_time = date("Y-m-d H:i:s");
            $message->file = $file_upload_name;
            if ($message->save()) {
                if(Yii::$app->request->get('id')) {
                    Contacts::updateContact(Yii::$app->request->get('id'), Yii::$app->user->identity->id);
                    if(User::sendEmailUser(Yii::$app->request->get('id'))) {
                        $user = User::findOne([
                            'id' => Yii::$app->request->get('id')
                        ]);
                        Yii::$app->mail->compose()
                            ->setFrom(Yii::$app->params['supportEmail'])
                            ->setTo($user['email'])
                            ->setSubject('Новое сообщение')
                            ->setHtmlBody('У вас новое сообщение на сайте iChat от пользователя: ' . Yii::$app->user->identity->username . '.<br /> Просмотреть: ' . Url::to(['site/index', 'id' => Yii::$app->user->identity->id], true) . '.<br /> Отключить отправку оповешений на E-Mail: ' . Url::to(['site/setting'], true))
                            ->send();
                    }
                } elseif(Yii::$app->request->get('chat')) {
                    ChatUsers::updateChat();
                    $users = ChatUsers::findAllChatUser(Yii::$app->request->get('chat'));
                    foreach($users as $user) {
                        if(User::sendEmailUser($user['id']) and $user['id'] != Yii::$app->user->identity->id) {
                            Yii::$app->mail->compose()
                                ->setFrom(Yii::$app->params['supportEmail'])
                                ->setTo($user['email'])
                                ->setSubject('Новое сообщение для ' . $user['username'])
                                ->setHtmlBody('У вас новое сообщение в чате на сайте iChat от пользователя: ' . Yii::$app->user->identity->username . '.<br /> Просмотреть: ' . Url::to(['site/index', 'chat' => Yii::$app->request->get('chat')], true) . '.<br /> Отключить отправку оповешений на E-Mail: ' . Url::to(['site/setting'], true))
                                ->send();
                        }
                    }
                }
            }
    }






}