<?php
use yii\helpers\Html;
/**
 * @var $contacts array
 */
?>

<div class="panel panel-default">
    <div class="panel-heading panel-heading-contacts">
        <span class="panel-title">
            <div class="row">
                <div class="col-lg-9">
                    Чаты
                </div>
                <div class="col-lg-3">
                    <?= Html::a('Новый', ['site/great-new-chat'], ['class' => 'btn btn-block btn-xs btn-info']) ?>
                </div>
            </div>
        </span>
    </div>
    <div class="panel-body panel-body-contact">
        <ul class="friend-list" id="chats-friend-list">
        </ul>
    </div>
</div>

<?php
$script = <<< JS
setTimeout(getChats, 0);
setInterval(getChats, 5000);
function getChats(){
    $.ajax({
    url: 'http://amarstd.myjino.ru/iChat/site/ajax-chats',
    success: function(data) {
        $('#chats-friend-list').html(data);
    }
    });
}
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>