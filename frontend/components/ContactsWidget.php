<?php

namespace app\components;

use yii\base\Widget;

class ContactsWidget extends Widget {

    public function init() {

    }

    public function run() {
        return $this->render('contacts');
    }
}