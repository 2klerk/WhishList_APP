<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'API News'; // Заголовок страницы

// Проверяем, есть ли данные для отображения
if (!empty($data)) {
    echo '<h1>Список новостей</h1>';

    // Выводим данные в виде списка или таблицы
    echo '<ul>';
    foreach ($data as $item) {
        echo '<li>' . Html::encode($item['title']) . '</li>';
    }
    echo '</ul>';
} else {
    echo '<p>Данные не найдены.</p>';
}
?>