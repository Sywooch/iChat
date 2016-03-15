<?php

namespace app\components;

use yii\base\Widget;

class ChatsWidget extends Widget {


    public function init() {

    }

    public function run() {
        return $this->render('chats');
    }
}