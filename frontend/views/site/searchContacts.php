<?php

/* @var $this yii\web\View */
/* @var $dataProvider */
/* @var $contacts array */
/* @var $blocked_users array */

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\UserProfileWidget;
use app\components\ContactsWidget;


$this->title = 'My Yii Application';
?>
<div class="site-contact">
    <div class="row">
        <div class="col-lg-4">

            <?= UserProfileWidget::widget() ?>
            <?= ContactsWidget::widget(['contacts' => $contacts]) ?>

        </div>
        <div class="col-lg-8">


                <?= GridView::widget([
                'dataProvider' => $dataProvider,
                    'rowOptions' => function ($data) use ($contacts, $blocked_users)
                    {
                        if($data['id'] == Yii::$app->user->identity->id) {
                            return ['style' => 'background-color:#D5FED2;'];
                        }
                        foreach($contacts as $contact) {
                            if($data['id'] == $contact['contact_id']) {
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
                    'value' => 'username',
                ],
                [
                    'label' => 'Имя',
                    'value' => 'firstname',
                ],
                    [
                        'label' => 'Фамилия',
                        'value' => 'lastname',
                    ],
                    [
                        'label' => 'E-Mail',
                        'value' => 'email',
                    ],
                [
                    'label' => 'Контакты',
                    'format' => 'raw',
                    'value' => function ($data) use ($contacts, $blocked_users) {
                        if($data['id'] == Yii::$app->user->identity->id) {
                            return '';
                        }else {
                            foreach ($blocked_users as $blocked_user) {
                                if ($data['id'] == $blocked_user['blocked_user_id']) {
                                    return '';
                                }
                            }
                            foreach($contacts as $contact) {
                                if($data['id'] == $contact['contact_id']) {
                                    return Html::a('Удалить из контактов', ['site/delete-contact', 'id' => $data['id']], ['class' => 'btn btn-xs btn-danger']);
                                }
                            }
                        }
                        return Html::a('Добавить в контакты', ['site/add-contact', 'id' => $data['id']], ['class' => 'btn btn-xs btn-primary']);
                    },
                ],
                    [
                        'label' => 'Черный список',
                        'format' => 'raw',
                        'value' => function ($data) use ($blocked_users) {
                            if ($data['id'] == Yii::$app->user->identity->id) {
                                return '';
                            } else {
                                foreach ($blocked_users as $blocked_user) {
                                    if ($data['id'] == $blocked_user['blocked_user_id']) {
                                        return Html::a('Разблокировать', ['site/un-blocked', 'id' => $data['id']], ['class' => 'btn btn-xs btn-success']);
                                    }
                                }
                            }
                            return Html::a('В черный список', ['site/add-black-list', 'id' => $data['id']], ['class' => 'btn btn-xs btn-danger']);
                        },
                    ],
                //['class' => 'yii\grid\ActionColumn'],
                ],
                ]); ?>





        </div>
    </div>
</div>