<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Вход: iChat';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">


    <div class="row">
        <?php if(Yii::$app->session->hasFlash('notValidEmail')): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Внимание!</strong><p><?php echo Yii::$app->session->getFlash('notValidEmail') ?></p>
            </div>
        <?php endif; ?>
        <div class="col-lg-4"></div>
        <div class="col-lg-4">

            <div class="panel panel-default panel-info">
                <div class="panel-heading">Вход</div>
                <div class="panel-body">

                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                        <?= $form->field($model, 'username')->label('Логин или Email') ?>

                        <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>

                        <div style="color:#999;margin:1em 0">
                            <div style="float: left">
                                <?= $form->field($model, 'rememberMe')->checkbox()->label('запомнить меня') ?>
                            </div>
                            <div style="padding: .7em 0;float: right">
                                <?= Html::a('Забыли пароль?', ['site/request-password-reset']) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= Html::submitButton('Войти', ['class' => 'btn btn-block btn-primary', 'name' => 'login-button']) ?>
                        </div>
                    <?php ActiveForm::end(); ?>

                    <div style="text-align: center">
                        <?= Html::a('Регистрация', ['site/signup']) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4"></div>
    </div>
</div>
