<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/**
 * @var $user array
 * @var $messages array
 */
?>

<div class="panel panel-default">
    <div class="panel-heading panel-heading-contacts">
        <div class="row">
            <div class="col-lg-11">
                <span class="panel-title">Я и <?= $user['firstname'] . ' (' . $user['username'] . ') ' . $user['lastname'] ?></span>
            </div>
            <div class="col-lg-1">
                <?= Html::a('+', ['site/great-new-chat', 'id' => $user['id']], ['class' => 'btn btn-xs btn-info']) ?>
            </div>
        </div>
    </div>
    <div class="panel-body panel-body-chats" id="scroll_div">
        <div class="chat-message">
            <ul class="chat"  id="message">

            </ul>
        </div>
    </div>
    <div class="panel-footer">
        <div class="row">
            <?php $form = ActiveForm::begin(['id' => 'index-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="col-lg-2">

                        <?= $form->field($model, 'file')->label('')->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>

            </div>
            <div class="col-lg-8">
                <?= $form->field($model, 'message')->label(''); ?>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <?= Html::submitButton('Отправить', ['class' => 'btn btn-block btn-primary', 'name' => 'index-button', 'style' => 'margin-top: 20px']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
