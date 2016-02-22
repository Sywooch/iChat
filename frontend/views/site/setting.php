<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SettingForm */

use Yii;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Настройки аккаунта';
?>

<div class="site-contact">
    <div class="row">
        <div class="col-lg-3">
            <?= Html::img(Yii::$app->user->identity->avatar, ['width' => '100%', 'class' => 'thumbnail']) ?>
            <div class="list-group">
                <a href=" <?= Yii::$app->urlManager->createAbsoluteUrl('site/setting') ?>" class="list-group-item active">Личные данные</a>
                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl('site/change-password') ?>" class="list-group-item">Смена пароля</a>
                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl('site/change-email') ?>" class="list-group-item">Смена E-Mail</a>

            </div>
        </div>
        <div class="col-lg-9">
            <div class="panel panel-info">
                <div class="panel-heading"><b><?= Html::encode($this->title) ?></b></div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(['id' => 'change-setting']); ?>

                    <?= $form->field($model, 'username')->label('Логин')->textInput(['value'=> \Yii::$app->user->identity->username]) ?>

                    <?= $form->field($model, 'firstname')->label('Имя')->textInput(['value'=> \Yii::$app->user->identity->firstname]) ?>

                    <?= $form->field($model, 'lastname')->label('Фамилия')->textInput(['value'=> \Yii::$app->user->identity->lastname]) ?>


                        <div class="form-group">
                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary',
                                'name' => 'setting-button',
                                'style' => 'margin: 0px 0px -13px 0px']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>


                </div>
            </div>







        </div>
        <div class="col-lg-4"></div>
    </div>
</div>
