<?php

namespace app\components;

use yii\base\Widget;

class ContactsWidget extends Widget {

    public $contacts;

    public function init() {
        parent::init();
        if(!isset($this->contacts)) {
            $this->contacts = null;
        }
    }

    public function run() {
        return $this->render('contacts', ['contacts' => $this->contacts]);
    }
}