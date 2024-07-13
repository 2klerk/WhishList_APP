<?php

namespace app\models;

use yii\db\ActiveRecord;

class News extends ActiveRecord
{
     public static function tableName()
    {
        return 'news'; // Имя вашей таблицы в базе данных PostgreSQL
    }

}