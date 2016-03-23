<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1>Работа с пользователями</h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],


            [
                'label' => 'ID',
                'attribute' => 'id',
                'options' => ['style' => 'width: 60px; max-width: 65px;'],
            ],
            [
                'label' => 'Ава',
                'format' => 'raw',
                'value' => function($data){
                    return Html::img(($data->avatar),[
                        'alt'=>'yii2 - картинка в gridview',
                        'style' => 'width:25px;'
                    ]);
                },
            ],
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


            'email:email',
            // 'password_hash',
            // 'password_reset_token',
            // 'auth_key',
            [
                'label' => 'Статус',
                'attribute' => 'status',
                'options' => ['style' => 'width: 30px;'],
            ],


            // 'created_at',
            // 'updated_at',
            [
                'label' => 'Подтверждение E-Mail',
                'attribute' => 'validation_email',
            ],

            // 'send_email:email',

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['style' => 'width: 70px;'],
            ],
        ],
    ]); ?>

</div>