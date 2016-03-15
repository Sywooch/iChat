<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "chat_users".
 *
 * @property integer $id
 * @property string $chat_name
 * @property integer $id_user
 * @property integer $datetime_lastmessage
 * @property integer $read_message
 */
class ChatUsers extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chat_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['chat_name', 'id_user'], 'required'],
            [['id_user'], 'integer'],
            [['chat_name'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'chat_name' => 'Chat Name',
            'id_user' => 'Id User',
            'datetime_lastmessage' => 'Datetime Lastmessage',
            'read_message' => 'Read Message',
        ];
    }

    public static function saveUser($chat_name, $id_user)
    {
        $chat_user = ChatUsers::findOne([
            'chat_name' => $chat_name,
            'id_user' => $id_user,
        ]);
        if(!isset($chat_user)) {
            $chat_user = new ChatUsers();
            $chat_user->chat_name = $chat_name;
            $chat_user->id_user = $id_user;
            $chat_user->datetime_lastmessage = date("Y-m-d H:i:s");
            $chat_user->save();
        }
    }

    public static function isIChat()
    {
        $user_chat = parent::findAll([
            'chat_name' => Yii::$app->request->get('chat')
        ]);
        foreach($user_chat as $item)
        {
            if($item['id_user'] == Yii::$app->user->identity->id)
            {
                return true;
            }
        }
        return false;
    }


    public static function deleteUser($chat_name, $id_user)
    {
        $chat_user = ChatUsers::findOne([
            'chat_name' => $chat_name,
            'id_user' => $id_user,
        ]);
        if(isset($chat_user)) {
            $chat_user->delete();
        }
    }

    public static function readChat($chat_name)
    {
        parent::updateAll(
            ['read_message' => 1],
            ['chat_name' => $chat_name, 'id_user' => Yii::$app->user->identity->id]
        );
    }


    public static function updateChat()
    {
        parent::updateAll(
            ['datetime_lastmessage' => date("Y-m-d H:i:s"), 'read_message' => 0],
            ['AND', ['=', 'chat_name', Yii::$app->request->get('chat')],['<>', 'id_user', Yii::$app->user->identity->id]]
        );
    }

    public static function exitChat($chat_name)
    {
        $chat_user = ChatUsers::findOne([
            'chat_name' => $chat_name,
            'id_user' => Yii::$app->user->identity->id,
        ]);
        if(isset($chat_user)) {
            $chat_user->delete();
        }
    }



    public static function findAllChatUser($chat_name)
    {
        return (new \yii\db\Query())
            ->select(['*'])
            ->from('chat_users')
            ->join('LEFT JOIN', 'user', 'chat_users.id_user = user.id')
            ->where(['chat_name' => $chat_name])
            ->all();
    }


    public static function getAllChat()
    {
        $my_chats_name = self::findAll([
           'id_user' => Yii::$app->user->identity->id,
        ]);
            foreach($my_chats_name as $item)
            {
                $all_my_chats_name[] = $item['chat_name'];
            }
        if(!$all_my_chats_name)
        {
            return null;
        }
        $my_chats = (new \yii\db\Query())
            ->select(['*'])
            ->from('chat_users')
            ->where(['in', 'chat_name', $all_my_chats_name])
            ->all();
        foreach($my_chats as $item)
        {
            $all_user_in_my_chats[] = $item['id_user'];
        }
        $all_user_in_my_chats = (new \yii\db\Query())
            ->select(['*'])
            ->from('user')
            ->where(['in', 'id', $all_user_in_my_chats])
            ->all();
        foreach ($all_my_chats_name as $all_my_chat_name)
        {
            foreach ($my_chats as $my_chat)
            {
                if($all_my_chat_name == $my_chat['chat_name'])
                {
                    $temp['chat_name'] = $all_my_chat_name;
                    if($my_chat['id_user'] == Yii::$app->user->identity->id)
                    {
                        $temp['datetime_lastmessage'] = $my_chat['datetime_lastmessage'];
                        $temp['read_message'] = $my_chat['read_message'];
                    }
                    foreach ($all_user_in_my_chats as $all_user_in_my_chat)
                    {
                        if($my_chat['id_user'] == $all_user_in_my_chat['id'])
                        {
                            $temp['USER'][] = $all_user_in_my_chat;
                        }
                    }
                }
            }
            $result[] = $temp;
            $temp['chat_name'] = null;
            $temp['USER'] = null;
        }
        //echo '<pre>';print_r($result);die("\n\ndebug fron");
        return $result;
    }







}
