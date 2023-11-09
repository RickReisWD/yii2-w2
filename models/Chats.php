<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Chats extends ActiveRecord{

    public function getMensagems(){
        return $this->hasMany();
    }
}