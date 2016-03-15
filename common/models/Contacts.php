<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "Contacts".
 *
 * @property integer $id
 * @property integer $my_id
 * @property integer $contact_id
 * @property $datetime_lastmessage
 * @property $read_message
 */
class Contacts extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contacts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['my_id', 'contact_id'], 'required'],
            [['my_id', 'contact_id'], 'integer']
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
            'contact_id' => 'Contact ID',
            'datetime_lastmessage' => 'Datetime Lastmessage',
            'read_message' => 'Read Message',
        ];
    }

    public static function findAllContacts($my_id)
    {
        return (new \yii\db\Query())
            ->select(['*'])
            ->from('contacts')
            ->join('LEFT JOIN', 'user', 'contacts.contact_id = user.id')
            ->where(['my_id' => $my_id])
            ->orderBy(['datetime_lastmessage' => SORT_DESC])
            ->all();
    }

    public static function newContact($first_id, $second_id)
    {
        $contacts = Contacts::findOne([
            'my_id' => $first_id,
            'contact_id' => $second_id,
        ]);
        if(!isset($contacts)) {
            $contacts = new Contacts();
            $contacts->my_id = $first_id;
            $contacts->contact_id = $second_id;
            $contacts->datetime_lastmessage = date("Y-m-d H:i:s");
            $contacts->save();
        }
    }

    public static function updateContact($first_id, $second_id)
    {
        $contacts = Contacts::findOne([
            'my_id' => $first_id,
            'contact_id' => $second_id,
        ]);
        if(isset($contacts)) {
            $contacts->datetime_lastmessage = date("Y-m-d H:i:s");
            $contacts->read_message = 0;
            $contacts->save();
        }
        if(!isset($contacts)) {
            $contacts = new Contacts();
            $contacts->my_id = $first_id;
            $contacts->contact_id = $second_id;
            $contacts->datetime_lastmessage = date("Y-m-d H:i:s");
            $contacts->read_message = 0;
            $contacts->save();
        }
    }

    public static function readContact($first_id, $second_id)
    {
        $contacts = Contacts::findOne([
            'my_id' => $first_id,
            'contact_id' => $second_id,
        ]);
        if(isset($contacts)) {
            $contacts->read_message = 1;
            $contacts->save();
        }
    }






}
