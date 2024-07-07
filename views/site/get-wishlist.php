<?php
use Yii\helpers\Html;
?>
<?php foreach($wishlist as $wish): ?>
    <div class='wish_block'>
        <?= $wish->name ?>
        <?= $wish->price ?>
        <?= $wish->img_path ?>
        <?= $wish->url ?>
        <?= $wish->category ?>
    </div>
<?php endforeach; ?>