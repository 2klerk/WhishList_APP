<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Мои желания';
$this->params['breadcrumbs'][] = $this->title;
?>

>

<h1><?= Html::encode($this->title) ?></h1>


<div class="wish-list">
    <div class="wish-card create-wish-card" id="create-wish-card">
        <h2>Добавить желание</h2>
        <button class="btn btn-success" onclick="toggleForm()">Добавить</button>
        <div class="wish-form" style="display: none;" id="wish-form">
            <?php $form = ActiveForm::begin([
                'id' => 'create-wish-form',
                'enableAjaxValidation' => true,
                'action' => ['wish/create-wish'],
                'options' => ['data-pjax' => true],
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

            <div class="form-group" id="create-button">
                <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="wish-cards">
        <?php echo $this->render('_wishes', ['wishes' => $wishes]); ?>
    </div>
</div>

<style>
    .wish-list {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .wish-cards {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
    }

    .wish-card {
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 16px;
        text-align: center;
        width: 200px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .wish-card:hover {
        transform: scale(1.05);
    }

    .wish-image {
        max-width: 100%;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    .wish-actions .btn {
        margin: 5px;
    }

    .create-wish-card {
        text-align: center;
    }
</style>

<script>
    function toggleForm() {
        const form = document.getElementById('wish-form');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }

    document.getElementById('create-wish-form').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent default form submission
        const form = event.target;
        const formData = new FormData(form);
        const csrfToken = form.querySelector('input[name="_csrf"]').value;
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        let data = {};
        formData.forEach((value, key) => {
            if (key.startsWith('Wish[')) {
                const fieldName = key.slice(5, -1);
                data[fieldName] = value;
            }
        });

        fetch('<?= \yii\helpers\Url::to(['wish/create-wish']) ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken
            },
            body: JSON.stringify({Wish: data}),
        })
            .then(response => {
                submitButton.disabled = false;
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Server response:', data);
                if (data.success) {
                    alert('Желание добавлено!');
                    // Add the new wish to the DOM
                    const wish = data.wish;
                    const wishElement = document.createElement('div');
                    wishElement.innerHTML = `<div class="wish-card">
                <h3>${wish.name}</h3>
                <p>Цена: ${wish.price}</p>
                <img src="${wish.img_path}" class="wish-image" alt="${wish.name}">
                <a href="${wish.url}">Ссылка</a>
            </div>`;
                    document.querySelector('.wish-cards').appendChild(wishElement);
                    toggleForm();
                    document.getElementById('create-wish-form').reset();
                } else {
                    alert('Ошибка: ' + data.message);
                }
            })
            .catch(error => {
                submitButton.disabled = false; // Re-enable the submit button
                console.error('Error:', error);
                alert('Произошла ошибка. Пожалуйста, попробуйте еще раз.');
            });
    });

</script>
