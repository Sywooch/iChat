<?php

namespace common\models;

use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "chat".
 *
 * @property integer $id
 * @property string $chat_name
 * @property integer $who_great
 */
class Chats extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chats';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['chat_name', 'who_great'], 'required'],
            [['who_great'], 'integer'],
            [['chat_name'], 'string', 'max' => 120]
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
            'who_great' => 'Who Great',
        ];
    }

    public static function saveChat($chat_name)
    {
        $chats = new Chats();
        $chats->chat_name = $chat_name;
        $chats->who_great = Yii::$app->user->identity->id;
        $chats->save();
    }

    public static function isIGreat()
    {
        $chats = parent::findOne([
            'chat_name' => Yii::$app->request->get('chat')
        ]);

        if($chats['who_great'] == Yii::$app->user->identity->id)
        {
            return true;
        }else {
            return false;
        }
    }


}
