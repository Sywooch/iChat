<?php
use yii\helpers\Html;
?>
<div class="panel panel-default">
    <div class="panel-heading panel-heading-contacts">
        <span class="panel-title">Профиль</span>
    </div>
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
    <div class="panel-footer panel-footer-right"><?= Html::a('Редактировать', ['site/setting'], ['class' => 'btn btn-link btn-sm']) ?></div>
</div>
