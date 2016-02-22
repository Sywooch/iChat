<?php

/* @var $this yii\web\View */

use Yii;
use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-contact">
    <div class="row">
        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-body panel-body-info-user">
                    <div class="col-lg-4">
                        <?= Html::img(Yii::$app->user->identity->avatar, ['width' => '100%', 'class' => 'thumbnail']) ?>
                    </div>
                    <div class="col-lg-8">
                        <p class="p-height"><b>Имя: </b><?= Yii::$app->user->identity->firstname ?></p>
                        <p class="p-height"><b>Фамилия: </b><?= Yii::$app->user->identity->lastname ?></p>
                        <p class="p-height"><b>Логин: </b><?= Yii::$app->user->identity->username ?></p>
                    </div>
                </div>
            </div>


            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Контакты</h3>
                </div>
                <div class="panel-body panel-body-contact">
                    Panel content
                </div>
            </div>




        </div>
        <div class="col-lg-8">

        </div>

    </div>
</div>
