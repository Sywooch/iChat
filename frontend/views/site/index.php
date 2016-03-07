<?php

/**
 * @var $this yii\web\View
 * @var $contacts array
 */

use yii\widgets\ActiveForm;
use app\components\ContactsWidget;
use app\components\UserProfileWidget;
use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-contact">
    <div class="row">
        <div class="col-lg-4">

            <?= UserProfileWidget::widget() ?>
            <?= ContactsWidget::widget(['contacts' => $contacts]) ?>

        </div>
        <div class="col-lg-8">













            <?php if(Yii::$app->request->get('id_user')) { ?>





                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-contacts">
                        <span class="panel-title">Я и <?= $user['firstname'] . ' (' . $user['username'] . ') ' . $user['lastname'] ?></span>
                    </div>
                    <div class="panel-body panel-body-chats" id="scroll_div">
                        <?php if($messages == null) { ?>
                        <p class="text-center" style="margin-top: 50px; font-size: 18px">Сообщений нет! Будь первым!</p>


                        <?php } ?>
                            <div class="chat-message">
                                <ul class="chat">
                                    <?php foreach($messages as $message) { ?>
                                        <li class="<?php if($message['from_user'] == Yii::$app->user->identity->id) {?>right <?php }else{ ?>left <?php } ?>clearfix">
                                            <span class="chat-img <?php if($message['from_user'] == Yii::$app->user->identity->id) {?>pull-right <?php }else{ ?>pull-left <?php } ?>">
                                                <img src="<?= $message['avatar'] ?>" alt="User Avatar">
                                            </span>
                                            <div class="chat-body clearfix">
                                                <div class="header">
                                                    <strong class="primary-font"> <?= $message['firstname'] . ' (' . $message['username'] . ') ' . $message['lastname'] ?> </strong>
                                                    <small class="pull-right text-muted">
                                                        <i class="fa fa-clock-o"></i><?= $message['date_time'] ?>
                                                    </small>
                                                </div>
                                                <p>
                                                    <?= $message['message'] ?>
                                                </p>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>

                    </div>

                    <div class="panel-footer">
                        <div class="row">
                            <?php $form = ActiveForm::begin(['id' => 'index-form']); ?>
                                <div class="col-lg-1"></div>
                                <div class="col-lg-9">
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








            <?php }else{ ?>



                <div class="panel panel-default">
                    <div class="panel-body not-chats">
                        <p class="text-center" style="margin-top: 250px; font-size: 18px">Не выбрано ни одного чата.</p>
                    </div>
                </div>




            <?php } ?>









        </div>
    </div>
</div>
<?php
$script = <<< JS
var scrollDiv = document.getElementById("scroll_div");
scrollDiv.scrollTo(0, scrollDiv.scrollHeight);
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>

