<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Wish extends ActiveRecord
{
    public static function tableName()
    {
        return 'wish';
    }

    public function rules()
    {
        return [
            [['userid', 'name', 'price'], 'required'],
            [['userid', 'price'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['img_path', 'url'], 'string', 'max' => 1024],
        ];
    }
}

