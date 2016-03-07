<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "messages".
 *
 * @property integer $id
 * @property integer $from_user
 * @property integer $for_user
 * @property string $chat_name
 * @property string $message
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
            [['message'], 'string', 'max' => 10000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from_user' => 'From User',
            'for_user' => 'For User',
            'chat_name' => 'Chat Name',
            'message' => 'Message',
            'date_time' => 'Date Time',
        ];
    }


    public static function findAllMessageFromUser($id_user)
    {
        return (new \yii\db\Query())
            ->select(['*'])
            ->from('messages')
            ->join('LEFT JOIN', 'users', 'messages.from_user = users.id')
            ->where(['from_user' => Yii::$app->user->identity->id, 'for_user' => $id_user])
            ->orWhere(['from_user' => $id_user, 'for_user' => Yii::$app->user->identity->id])
            ->andWhere(['chat_name' => 'private'])
            ->all();
    }



}
