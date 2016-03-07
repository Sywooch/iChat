<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "black_list".
 *
 * @property integer $id
 * @property integer $my_id
 * @property integer $blocked_user_id
 */
class BlackList extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'black_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['my_id', 'blocked_user_id'], 'required'],
            [['my_id', 'blocked_user_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'my_id' => 'My ID',
            'blocked_user_id' => 'Blocked User ID',
        ];
    }

    public static function getBlockedUsers ()
    {
        return self::findAll([
            'my_id' => Yii::$app->user->identity->id,
        ]);
    }
}
