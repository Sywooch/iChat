<?php

namespace app\components;

use yii\base\Widget;

class PrivateMessagesWidget extends Widget {

    public $user;

    public $form;
    public $model;

    public function init() {
        parent::init();
        if(!isset($this->user)) {
            $this->user = null;
        }

        if(!isset($this->form)) {
            $this->form = null;
        }
        if(!isset($this->model)) {
            $this->model = null;
        }
    }

    public function run() {
        return $this->render('privatemessages',
            [
                'user' => $this->user,

                'form' => $this->form,
                'model' => $this->model,
            ]);
    }
}