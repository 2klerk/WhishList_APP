<?php
namespace app\models;

use yii\base\Model;

class WishlistForm extends Model {
    public $name;
    public $price;
    public $img_path;
    public $url;
    public $category;

    public function rules() {
        return [
            [['name', 'price', 'img_path', 'url'], 'required'],
            ['url', 'url'],
        ];
    }
}

?>