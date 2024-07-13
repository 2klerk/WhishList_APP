<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$this->title = 'Cabinet';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-cabinet">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Имя <?= Html::encode($user->name) ?></p>
    <p>Фамилия <?= Html::encode($user->surname) ?></p>
    <p>Дата регистрации <?= Html::encode($user->registration_date) ?></p>
    <p>Электронная почта <?= Html::encode($user->email)?></p>
</div>
