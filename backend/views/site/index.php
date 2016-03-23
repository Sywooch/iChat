<?php

/* @var $this yii\web\View */

use yii\bootstrap\Html;
$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="row">
        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title" style="text-align: center">Пользователей</h3>
                </div>
                <div class="panel-body"  style="text-align: center; font-size: 50px">
                    <?= $count_user ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title" style="text-align: center">Сообщений</h3>
                </div>
                <div class="panel-body" style="text-align: center; font-size: 50px">
                    <?= $count_messages ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title" style="text-align: center">Чатов</h3>
                </div>
                <div class="panel-body" style="text-align: center; font-size: 50px">
                    <?= $count_chats ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <?= Html::a('Пользователи', ['user/index'], ['class' => 'btn btn-block btn-info']) ?>
        </div>
    </div>
</div>
