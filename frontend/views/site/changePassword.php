<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ChangePasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;

$this->title = 'Смена пароля';
?>

<div class="site-contact">
    <div class="row">

        <div class="col-lg-3">
            <?= Html::img(Yii::$app->user->identity->avatar, ['width' => '100%', 'class' => 'thumbnail']) ?>
            <div class="list-group">
                <?= Html::a('Личные данные', ['site/setting'], ['class' => 'list-group-item']) ?>
                <?= Html::a('Смена пароля', ['site/change-password'], ['class' => 'list-group-item active']) ?>
                <?= Html::a('Смена E-Mail', ['site/change-email'], ['class' => 'list-group-item']) ?>
                <?php
                Modal::begin([
                    //'size' => 'modal-lg',
                    'header' => '<h4>Удаление аккаунта</h4>',
                    // Кнопка за пределами
                    'options' => [
                        'id' => 'delete-user'
                    ],
                    // Кнопка создается прямо здесь
                    //'toggleButton' => [
                    //    'label' => 'Удалить аккаунт',
                    //    'tag' => 'button',
                    //    'class' => 'list-group-item list-group-item-danger',
                    //],
                    'footer' => Html::a('Удалить', ['site/delete-user'], ['type' => 'button', 'class' => 'btn btn-danger']) . Html::a('Закрыть', [], ['type' => 'button', 'class' => 'btn btn-default', 'data-dismiss' => 'modal']),
                ]);
                echo ("<h4>Внимание!!!</h4>Аккаунт будет удален без возможности восстановления! <p>Удалить аккаунт?</p>");
                Modal::end();
                ?>
                <?= Html::a('Удалить аккаунт',
                    ['#'],
                    [
                        'tag' => 'button',
                        'class' => 'list-group-item list-group-item-danger',
                        'data-toggle' => 'modal',
                        'data-target' => '#delete-user'
                    ]
                )?>
            </div>
        </div>

        <div class="col-lg-9">
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