<?php
namespace app\models;

use yii\base\Model;

/**
 * Класс форма таблицы wishlist БД.
 */
class WishlistForm extends Model {
    /** @var string Название подарка */
    public $name;
    /** @var int    Цена подарка */
    public $price;
    /** @var string Ссылка на изображение подарка */
    public $img_path;
    /** @var string Ссылка на подарок */
    public $url;
    /** @var string Категория, к которой относится подарок */
    public $category;

    public function rules() {
        return [
            [['name', 'price', 'img_path', 'url', 'category',], 'required'],
            ['url', 'url'],
        ];
    }
}

?>