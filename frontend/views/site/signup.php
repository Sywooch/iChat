<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'iChat';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">


    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4">


            <div class="panel panel-info" style="margin: 15% 0px 0px 0px">
                <div class="panel-heading">
                    <h1 style="margin: 10px 36%"><?= Html::encode($this->title) ?></h1>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                        <?= $form->field($model, 'firstname')->label('Имя') ?>

                        <?= $form->field($model, 'lastname')->label('Фамилия') ?>

                        <?= $form->field($model, 'username')->label('Логин') ?>

                        <?= $form->field($model, 'email') ?>

                        <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>

                        <?= $form->field($model, 'verifyCode')->label('Проверочный код')->widget(Captcha::className(), [
                            'template' => '<div class="row"><div class="col-lg-4">{image}</div><div  class="col-lg-8"  style="margin: 10px 0px">{input}</div></div>',
                        ]) ?>

                        <div class="form-group" style="margin: 30px 0px 0px 0px">
                            <?= Html::submitButton('Регистрация', ['class' => 'btn btn-primary btn-block', 'name' => 'signup-button']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>



        </div>
        <div class="col-lg-4"></div>
    </div>
</div>
