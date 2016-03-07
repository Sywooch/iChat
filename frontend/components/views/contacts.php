<?php
use yii\helpers\Html;
use yii\helpers\Url;
/**
 * @var $contacts array
 */
?>

<div class="panel panel-default">
    <div class="panel-heading panel-heading-contacts">
        <span class="panel-title">Приватное общение</span>
    </div>
    <div class="panel-body panel-body-contact">
        <ul class="friend-list">

            <?php if($contacts == null) { ?>
                <?= '<p style="text-align: center; margin-top: 15px">У вас нет ни одного контакта!<br />Начните с добавления '?>
                <?= Html::a('пользователей', ['site/search-contacts'])?>
                <?= 'к себе в контакты.</p>' ?>
            <?php }else{ ?>
                <?php foreach($contacts as $item) {?>
                    <li class="active bounceInDown">
                        <a href="<?= Url::to(['site/index', 'id_user' => $item['id']]) ?>" class="clearfix">
                            <img src="<?= $item['avatar'] ?>" alt="" class="img-circle">
                            <div class="friend-name">
                                <strong><?= $item['firstname'] . ' (' . $item['username'] . ') ' . $item['lastname'] ?></strong>
                            </div>
                            <div class="last-message text-muted"></div>
                            <small class="time text-muted"></small>
                            <small class="chat-alert label label-danger"><?php if($item['read_message'] == '0') { ?>new<?php }?></small>
                        </a>
                    </li>
                <?php } ?>
            <?php } ?>

        </ul>
    </div>
</div>
