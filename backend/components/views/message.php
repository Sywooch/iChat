<?php
use yii\helpers\Html;

/**
 * @var $user array
 * @var $messages array
 */
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?php $name = null ?>
            <?php foreach($user as $item) { ?>
                <?php $name .= $item['username'] . ', ' ?>
            <?php } ?>
            <?= substr(substr($name, 0, -2), 0, 35) ?>
        </h3>
    </div>
    <div class="panel-body messages-backend">
        <div class="chat-message">
            <ul class="chat">
                <?php if($messages != null) { ?>
                    <?php foreach($messages as $message) { ?>
                        <li class="<?php if($message['from_user'] == $model->id) { ?>right<?php }else { ?>left<?php } ?> clearfix">
                                <span class="chat-img <?php if($message['from_user'] == $model->id) { ?>pull-right<?php }else { ?>pull-left<?php } ?>">
                                    <img src="<?= $message['avatar'] ?>" alt="User Avatar">
                                </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <strong class="primary-font"><?= $message['username'] ?></strong>
                                    <small class="pull-right text-muted"><i class="fa fa-clock-o"></i><?= $message['date_time'] ?></small>
                                </div>
                                <p class="message-delete-button">
                                    <?php if($message['file'] != null) { ?>
                                        <img class = "message-img img-thumbnail" src="<?= 'http://amarstd.myjino.ru/iChat/frontend/web/' . $message['file']; ?>">
                                    <?php } ?>
                                    <?= Html::encode($message['message']); ?>
                                    <?= Html::a('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', ['user/delete-message', 'id' => $model->id, 'chat_name' => Yii::$app->request->get('chat_name'), 'id_user_2' => Yii::$app->request->get('id_user_2'), 'id_message' => $message['id_message']], [
                                        'class' => 'btn btn-xs btn-danger',
                                        'data' => [
                                            'confirm' => 'Вы действительно хотите удалить данное сообщение?',
                                        ],
                                    ]); ?>
                                </p>
                            </div>
                        </li>
                    <?php } ?>
                <?php }else { ?>
                    <p>Переписка пустая</p>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="panel-footer"></div>
</div>
