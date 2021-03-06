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
                <div class="col-lg-6">
                    Контакты
                </div>
                <div class="col-lg-6">
                    <?= Html::a('Добавить пользователя', ['site/search-contacts'], ['class' => 'btn btn-xs btn-info']) ?>
                </div>
            </div>
        </span>
    </div>
    <div class="panel-body panel-body-contact">
        <ul class="friend-list" id="contacts-friend-list">
        </ul>
    </div>
</div>
<?php
$script = <<< JS
setTimeout(getContacts, 0);
setInterval(getContacts, 5000);
function getContacts(){
    $.ajax({
    url: 'http://amarstd.myjino.ru/iChat/site/ajax-contacts',
    success: function(data) {
        $('#contacts-friend-list').html(data);
    }
    });
}
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>