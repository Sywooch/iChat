<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "messages".
 *
 * @property integer $id_message
 * @property integer $from_user
 * @property integer $for_user
 * @property string $chat_name
 * @property string $message
 * @property string $file
 * @property string $date_time
 */
class Messages extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_user', 'for_user', 'chat_name', 'message', 'date_time'], 'required'],
            [['from_user', 'for_user'], 'integer'],
            [['date_time'], 'safe'],
            [['chat_name'], 'string', 'max' => 250],
            [['message'], 'string', 'max' => 10000],
            [['file'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_message' => 'ID Message',
            'from_user' => 'From User',
            'for_user' => 'For User',
            'chat_name' => 'Chat Name',
            'message' => 'Message',
            'file' => 'File',
            'date_time' => 'Date Time',
        ];
    }


    public static function findAllMessageFromUser($id_user, $my_id)
    {
        return (new \yii\db\Query())
            ->select(['*'])
            ->from('messages')
            ->join('LEFT JOIN', 'user', 'messages.from_user = user.id')
            ->where(['from_user' => $my_id, 'for_user' => $id_user])
            ->orWhere(['from_user' => $id_user, 'for_user' => $my_id])
            ->andWhere(['chat_name' => 'private'])
            ->orderBy('date_time')
            ->all();
    }

    public static function findAllMessageChat($chat_name)
    {
        return (new \yii\db\Query())
            ->select(['*'])
            ->from('messages')
            ->join('LEFT JOIN', 'user', 'messages.from_user = user.id')
            ->Where(['chat_name' => $chat_name])
            ->orderBy('date_time')
            ->all();
    }
}
