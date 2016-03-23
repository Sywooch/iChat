<?php

namespace app\components;

use yii\base\Widget;

class MessageWidget extends Widget {

    public $user;
    public $messages;
    public $model;

    public function init() {
        parent::init();
        if(!isset($this->user)) {
            $this->user = null;
        }

        if(!isset($this->messages)) {
            $this->messages = null;
        }
        if(!isset($this->model)) {
            $this->model = null;
        }
    }

    public function run() {
        return $this->render('message',
            [
                'user' => $this->user,
                'messages' => $this->messages,
                'model' => $this->model,
            ]);
    }
}