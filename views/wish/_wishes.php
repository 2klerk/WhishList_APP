<?php
use yii\helpers\Html; // Add this line

foreach ($wishes as $wish): ?>
    <div class="wish-card">
        <h2><?= Html::encode($wish->name) ?></h2>
        <p>Цена: <?= Html::encode($wish->price) ?>₽</p>
        <img src="<?= Html::encode($wish->img_path) ?>" alt="<?= Html::encode($wish->name) ?>" class="wish-image" />
        <div class="wish-actions">
            <?= Html::a('Редактировать', ['update', 'id' => $wish->wishid], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $wish->wishid], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить это желание?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>
<?php endforeach; ?>