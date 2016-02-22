<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Сброс пароля';

?>
<div class="site-request-password-reset">




    <div class="col-lg-4"></div>
    <div class="col-lg-4">


    <h1><?= Html::encode($this->title) ?></h1>

    <p>Введите E-Mail указанный при регистрации. Вам на почту будет отправлена ссылка для сброса пароля.</p>




            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?= $form->field($model, 'email') ?>

                <div class="form-group">
                    <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>





        </div>
    <div class="col-lg-4"></div>
</div>
