<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */
/* @var $chat_users */
/* @var $contacts */
/* @var $blocked_users */
/* @var $dataProvider */
use app\components\UserProfileWidget;
use app\components\ContactsWidget;
use yii\grid\GridView;
use yii\bootstrap\Html;
use app\components\ChatsWidget;
$this->title = 'Добавление пользователей';
?>

<div class="site-reset-password">
    <div class="col-lg-4">
        <?= UserProfileWidget::widget() ?>
        <?= ContactsWidget::widget() ?>
        <?= ChatsWidget::widget() ?>
    </div>
    <div class="col-lg-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <p class="text-center" style="font-size: 25px">Добавление пользователей в чат.</p>
                <?= Html::a('Перейти к общению', ['site/index', 'chat' => Yii::$app->request->get('chat')], ['class' => 'btn btn-block btn-info'])?>
            </div>
        </div>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'rowOptions' => function ($data) use ($chat_users, $blocked_users)
            {
                if($data['id'] == Yii::$app->user->identity->id) {
                    return ['style' => 'background-color:#D5FED2;'];
                }
                foreach($chat_users as $chat_user) {
                    if($data['id'] == $chat_user['id_user']) {
                        return ['style' => 'background-color:#D2E5FE;'];
                    }
                }
                foreach($blocked_users as $blocked_user) {
                    if($data['id'] == $blocked_user['blocked_user_id']) {
                        return ['style' => 'background-color:#F7A2AE;'];
                    }
                }
            },
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                'id',
                [
                    'label' => 'Логин',
                    'attribute' => 'username',
                ],
                [
                    'label' => 'Имя',
                    'attribute' => 'firstname',
                ],
                [
                    'label' => 'Фамилия',
                    'attribute' => 'lastname',
                ],
                [
                    'label' => 'E-Mail',
                    'attribute' => 'email',
                ],
                [
                    'label' => 'Контакты',
                    'format' => 'raw',
                    'value' => function ($data) use ($chat_users, $blocked_users) {
                        if($data['id'] == Yii::$app->user->identity->id) {
                            return '';
                        }else {
                            foreach ($blocked_users as $blocked_user) {
                                if ($data['id'] == $blocked_user['blocked_user_id']) {
                                    return 'В черном списке';
                                }
                            }
                            foreach($chat_users as $chat_user) {
                                if($data['id'] == $chat_user['id_user']) {
                                    return Html::a('Удалить из чата', ['site/delete-to-chat', 'id' => $data['id'], 'chat' => Yii::$app->request->get('chat')], ['class' => 'btn btn-xs btn-danger']);
                                }
                            }
                        }
                        return Html::a('Добавить в чат', ['site/add-to-chat', 'id' => $data['id'], 'chat' => Yii::$app->request->get('chat')], ['class' => 'btn btn-xs btn-primary']);
                    },
                ],
                //['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>