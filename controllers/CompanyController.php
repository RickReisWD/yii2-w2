<?php

namespace app\controllers;
use app\models\Companies;
use yii\web\Controller;

class CompanyController extends Controller{
    public function actionCriar(){
        $empresa = new Companies;
        $empresa->name = "Nova";
        $empresa->legal_name = "empresa";
        $empresa->insert();
    }

    public function actionPesquisar(){
        $empresas = Companies::find()->all();
        return $empresas;
    }

    public function actionAtualizar(){
        $id = $_GET['id'];
        $name = $_GET['name'];
        $legal_name = $_GET['legal_name'];
        $empresa = Companies::findOne($id);
        $empresa->name = $name;
        $empresa->legal_name = $legal_name;
        $empresa->save();
    }

    public function actionDeletar(){
        $id = $_GET['id'];
        $empresa = Companies::findOne($id);
        $empresa->delete();
    }
}
