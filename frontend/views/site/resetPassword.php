<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Сброс пароля';

?>
<div class="site-reset-password">


    <div class="col-lg-4"></div>
    <div class="col-lg-4">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>Введите ваш новый пароль:</p>

            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>

    </div>
    <div class="col-lg-4"></div>


</div>
