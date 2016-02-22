<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ChangePasswordForm */

use Yii;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Смена пароля';
?>

<div class="site-contact">
    <div class="row">
        <div class="col-lg-3">
            <?= Html::img(Yii::$app->user->identity->avatar, ['width' => '100%', 'class' => 'thumbnail']) ?>

            <div class="list-group">
                <a href=" <?= Yii::$app->urlManager->createAbsoluteUrl('site/setting') ?>" class="list-group-item">Личные данные</a>
                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl('site/change-password') ?>" class="list-group-item active">Смена пароля</a>
                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl('site/change-email') ?>" class="list-group-item">Смена E-Mail</a>

            </div>




        </div>
        <div class="col-lg-9">
            <div class="panel panel-info">
                <div class="panel-heading"><b><?= Html::encode($this->title) ?></b></div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(['id' => 'change-password']); ?>

                    <?= $form->field($model, 'password')->passwordInput()->label('Действующий пароль') ?>

                    <?= $form->field($model, 'new_password')->passwordInput()->label('Новый пароль') ?>

                    <div class="form-group">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary',
                            'name' => 'changepassword-button',
                            'style' => 'margin: 0px 0px -13px 0px']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>






        </div>
        <div class="col-lg-4"></div>
    </div>
</div>