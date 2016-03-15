<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Chats;
use yii\bootstrap\Modal;
/**
 * @var $user array
 * @var $messages array
 */
?>

<div class="panel panel-default">
    <div class="panel-heading panel-heading-contacts">
        <div class="row">
            <div class="col-lg-10">
                                <span class="panel-title">
                                    <?php
                                    $temp = 'Я и ';
                                    foreach($user as $item)
                                    {
                                        if($item['id'] != Yii::$app->user->identity->id)
                                        {
                                            $temp .= $item['username'] . ', ';
                                        }
                                    }
                                    $temp = substr($temp, 0, -2);
                                    if(strlen($temp) > 35)
                                    {
                                        $temp = substr($temp, 0, 35) . ' . . .';
                                    }
                                    echo $temp;
                                    ?>
                                </span>
            </div>
            <div class="col-lg-2">
                <?php if(Chats::isIGreat()) {


                    Modal::begin([
                        //'size' => 'modal-lg',
                        'header' => '<h4>Удаление чата</h4>',
                        // Кнопка за пределами
                        'options' => [
                            'id' => 'delete-chat'
                        ],
                        // Кнопка создается прямо здесь
                        //'toggleButton' => [
                        //    'label' => 'Удалить аккаунт',
                        //    'tag' => 'button',
                        //    'class' => 'list-group-item list-group-item-danger',
                        //],
                        'footer' => Html::a('Удалить', ['site/delete-chat', 'chat' => Yii::$app->request->get('chat')], ['type' => 'button', 'class' => 'btn btn-danger']) . Html::a('Закрыть', [], ['type' => 'button', 'class' => 'btn btn-default', 'data-dismiss' => 'modal']),
                    ]);
                    echo ("<h4>Внимание!!!</h4>Чат и все его сообщения будет удален без возможности восстановления! <p>Удалить чат?</p>");
                    Modal::end();

                    echo Html::a('Удалить',
                        ['#'],
                        [
                            'tag' => 'button',
                            'class' => 'btn btn-xs btn-danger',
                            'style' => 'margin: 0px 5px',
                            'data-toggle' => 'modal',
                            'data-target' => '#delete-chat'
                        ]);


                 }else {
                    echo Html::a('Выйти', ['site/exit-chat', 'chat' => Yii::$app->request->get('chat')], ['class' => 'btn btn-xs btn-info', 'style' => 'margin: 0px 10px']);
                 }
                 echo Html::a('+', ['site/add-user-chat', 'chat' => Yii::$app->request->get('chat')], ['class' => 'btn btn-xs btn-info']) ?>
            </div>
        </div>
    </div>
    <div class="panel-body panel-body-chats" id="scroll_div">
        <div class="chat-message">
            <ul class="chat" id="message">

            </ul>
        </div>
    </div>
    <div class="panel-footer">
        <div class="row">
            <?php $form = ActiveForm::begin(['id' => 'index-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="col-lg-2">
                <div class="file-upload">
                    <label>
                        <?= $form->field($model, 'file')->label('')->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>
                    </label>
                </div>
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
