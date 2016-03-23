<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\MessageWidget;

/* @var $this yii\web\View */
/* @var $model common\models\User
 * @var $count_chat
 * @var $count_contact
 * @var $count_message
 * @var $contacts
 * @var $all_chats
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-view">
    <div class="row">
        <div class="col-sm-12">
            <?php
            if(Yii::$app->request->get('id_user_2') || Yii::$app->request->get('chat_name')) {
                echo MessageWidget::widget([
                    'user' => $user,
                    'messages' => $messages,
                    'model' => $model,
                ]);
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            <h1 style="margin-top: 10px">Данные пользователя</h1>
            <p>
                <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?php if($model->id != Yii::$app->user->identity->id) {?>
                <?= Html::a('Удалить пользователя', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы действительно хотите удалить данного пользователя?',
                        'method' => 'post',
                    ],
                ]) ?>
                <?php } ?>
                <?php
                    if($model->id != Yii::$app->user->identity->id) {
                        if (!Yii::$app->getAuthManager()->checkAccess($model->id, 'admin')) {
                            echo Html::a('Назначить администратором', ['set-admin', 'id' => $model->id], [
                                'class' => 'btn btn-primary',
                                'data' => [
                                    'confirm' => 'Вы действительно хотите назначить пользователя администратором?',
                                    'method' => 'get',
                                ],
                            ]);
                        } elseif (Yii::$app->getAuthManager()->checkAccess($model->id, 'admin')) {
                            echo Html::a('Удалить из администраторов', ['set-user', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Вы действительно хотите разжаловать пользователя из администраторов?',
                                    'method' => 'get',
                                ],
                            ]);
                        }
                    }
                ?>
            </p>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'username',
                    'firstname',
                    'lastname',
                    'avatar:image',
                    'email:email',
                    'password_hash',
                    'password_reset_token',
                    'auth_key',
                    'status',
                    'created_at',
                    'updated_at',
                    'validation_email',
                    'send_email',
                ],
            ]) ?>
        </div>
        <div class="col-sm-4">
            <h2>Личные переписки</h2>
            <ul class="list-group">
                <?php if($contacts == null) { ?>
                    <li class="list-group-item">
                        У пользователя нет личных переписок
                    </li>
                <?php } else { $count_buttonview = null; ?>
                <?php foreach($contacts as $contact) { $count_buttonview += 1;?>
                    <li class="list-group-item">
                        <?= $contact['firstname'] . ' (' . $contact['username'] . ') ' . $contact['lastname'] ?>
                        <?= Html::a('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', ['user/delete-contact', 'id' => $model->id, 'id2' => $contact['id']], [
                            'class' => 'btn btn-xs btn-danger',
                            'data' => [
                                'confirm' => 'Вы действительно хотите удалить переписку?',
                            ],
                        ]); ?>
                        <?= Html::a('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>', ['user/view', 'id' => $model->id, 'id_user_2' => $contact['id'],], [
                            'class' => "btn btn-xs btn-info buttonview_$count_buttonview",
                        ]); ?>
                    </li>
                <?php } ?>
                <?php } ?>
            </ul>
            <h2>Чаты</h2>
            <ul class="list-group">
                <?php if(!isset($all_chats)) { ?>
                    <li class="list-group-item">
                        Пользователь не состоит в чатах
                    </li>
                <?php } else { ?>
                <?php foreach($all_chats as $all_chat) { ?>
                    <li class="list-group-item">
                        <?php
                        $string = null;
                        foreach($all_chat['USER'] as $item) {
                            $string .= $item['username'] . ', ';
                        }
                        echo substr($string, 0, -2)
                        ?>
                        <?= Html::a('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', ['user/delete-chat', 'id' => $model->id, 'chat_name' => $all_chat['chat_name']], [
                            'class' => 'btn btn-xs btn-danger',
                            'data' => [
                                'confirm' => 'Вы действительно хотите удалить данный чат?',
                            ],
                        ]); ?>
                        <?= Html::a('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>', ['user/view', 'id' => $model->id, 'chat_name' => $all_chat['chat_name'],], [
                            'class' => 'btn btn-xs btn-info',
                        ]); ?>
                    </li>
                <?php } ?>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php //echo '<pre>';print_r($messages);("\n\ndebug fron"); ?>
</div>