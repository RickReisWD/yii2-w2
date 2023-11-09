<?php

namespace app\Models;
use Yii;
use yii\base\Model;

class CadastroModel extends Model{
    public $nome;
    public $email;
    public $idade;
    public function formName()
    {
        return '';
    }
    public function rules(){
        return [
            [['nome', 'email', 'idade'], 'required'],
            ['email', 'email'],
            ['idade', 'number', 'integerOnly'=>true]
        ];
    }
    
}