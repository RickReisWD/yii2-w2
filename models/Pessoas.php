<?php

namespace app\models;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Pessoas extends ActiveRecord {

    public function getAddress(){

        return $this->hasMany(Address::className(), ['pessoas_id'=>'id']);

    }

    public function getMensagens(){

        return $this->hasMany(Mensagens::className(), ['pessoa_id'=>'id']);

    }

    public function getIdChat($id){
        $this->id = $id;
        return $this->mensagens[0]->chat_id;

    }
    

    public function fields(){
        return ArrayHelper::merge(parent::fields(), ['address']);
    }
    
}