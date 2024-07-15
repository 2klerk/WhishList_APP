<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Wish */

$this->title = 'Редактировать желание: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Мои желания', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактировать';
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="wish-update">

    <div class="wish-form">

        <?php $form = ActiveForm::begin([
            'id' => 'update-wish-form',
            'enableAjaxValidation' => true,
            'action' => ['update', 'id' => $model->wishid],
        ]); ?>

        <div class="form-group">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'price')->textInput(['type' => 'number']) ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'img_path')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
