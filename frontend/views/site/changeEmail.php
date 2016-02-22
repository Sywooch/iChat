<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ChangePasswordForm */

use Yii;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Смена E-Mail';
?>

<div class="site-contact">
    <div class="row">
        <div class="col-lg-3">
            <?= Html::img(Yii::$app->user->identity->avatar, ['width' => '100%', 'class' => 'thumbnail']) ?>

            <div class="list-group">
                <a href=" <?= Yii::$app->urlManager->createAbsoluteUrl('site/setting') ?>" class="list-group-item">Личные данные</a>
                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl('site/change-password') ?>" class="list-group-item">Смена пароля</a>
                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl('site/change-email') ?>" class="list-group-item active">Смена E-Mail</a>

            </div>







        </div>
        <div class="col-lg-9">
            <div class="panel panel-info">
                <div class="panel-heading"><b><?= Html::encode($this->title) ?></b></div>
                <div class="panel-body">
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Внимание!</strong> <p />После смены E-Mail вам будет отправлено письмо со ссылкой на подтвеждение. Аккаунт будет заблокирован до подтверждения!
                    </div>

                    <?php $form = ActiveForm::begin(['id' => 'change-password']); ?>

                    <?= $form->field($model, 'email')->label('Новый E-Mail') ?>

                    <div class="form-group">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary',
                            'name' => 'changeemail-button',
                            'style' => 'margin: 0px 0px -13px 0px']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>






        </div>
        <div class="col-lg-4"></div>
    </div>
</div>