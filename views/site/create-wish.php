<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($wish, 'name')->label('Название') ?>
    <?= $form->field($wish, 'price')->label('Цена') ?>
    <?= $form->field($wish, 'img_path')->label('Изображение') ?>
    <?= $form->field($wish, 'url')->label('Ссылка на товар') ?>
    <div class = 'form-group'>
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>