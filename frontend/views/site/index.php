<?php
/**
 * @var $this yii\web\View
 * @var $contacts array
 * @var $all_chats array
 * @var $model
 */
use app\components\ContactsWidget;
use app\components\UserProfileWidget;
use app\components\ChatsWidget;
use app\components\PrivateMessagesWidget;
use app\components\ChatMessagesWidget;
$this->title = 'My Yii Application';
?>

<div class="site-contact">
    <div class="row">
        <div class="col-sm-4">
            <?= UserProfileWidget::widget() ?>
            <?= ContactsWidget::widget() ?>
            <?= ChatsWidget::widget() ?>
        </div>
        <div class="col-sm-8">
            <?php if(Yii::$app->request->get('id')) {
                $id = Yii::$app->request->get('id');
                echo PrivateMessagesWidget::widget([
                    'user' => $user,
                    'form' => $form,
                    'model' => $model,
                ]);
            }elseif(Yii::$app->request->get('chat')) {
                $chat_name = Yii::$app->request->get('chat');
                echo ChatMessagesWidget::widget([
                    'user' => $user,
                    'form' => $form,
                    'model' => $model,
                ]);
            }else {?>
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
setTimeout(getMessage, 0);
setInterval(getMessage, 5000);
function getMessage(){
    var div = document.getElementById('scroll_div');
    var position = $('#scroll_div').scrollTop();
    $.ajax({
      type: 'GET',
      url: 'http://amarstd.myjino.ru/iChat/site/ajax-message',
      data: {chat: "$chat_name", id: "$id"},
      success: function(data) {
        $('#message').html(data);
        var div = document.getElementById('scroll_div');
        $('#scroll_div').scrollTop(div.scrollHeight-div.offsetHeight);
      }
    });
}
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>