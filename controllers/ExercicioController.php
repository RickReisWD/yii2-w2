<?php
namespace app\controllers;
use Yii;
use yii\base\DynamicModel;
use \yii\web\Controller;
use app\models\Pessoas;
use yii\data\{ActiveDataFilter, ActiveDataProvider, Pagination};

class ExercicioController extends Controller {

    public function actionIndex(){
        $filter = new ActiveDataFilter([
            'searchModel' => (new DynamicModel(['id', 'name', 'rua']))
                ->addRule(['id'], 'integer')
                ->addRule(['name'], 'string', ['min' => 2, 'max' => 200])
                ->addRule(['rua'], 'string', ['min' => 2, 'max' => 200])

        ]);

        $filter->attributeMap = [
            'rua' => 'address.street',
            'id' => 'pessoas.id'
        ];

        $filterCondition = null;

        if ($filter->load(Yii::$app->request->get())) { 
            $filterCondition = $filter->build();
            if ($filterCondition === false) {
                return ['Erro'=>'Argumento inválido!'];
            }
        }
        
        $query = Pessoas::find()->distinct()->joinWith('mensagens')->joinWith('address');

        if ($filterCondition !== null) {
            $query->andWhere($filterCondition);
        }

        $count = $query->count();

        $pagination = new Pagination(['totalCount'=>$count]);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>$pagination
        ]);
    
        $header_http = Yii::$app->response->headers;
        $header_http->add('X-Pagination-Total-Count', $provider->getTotalCount());
        $header_http->add('X-Pagination-Page-Count', $provider->getPagination()->getPageCount());
        $header_http->add('X-Pagination-Current-Page', $provider->getPagination()->getPage()+1);
        $header_http->add('X-Pagination-Per-Page', $provider->getPagination()->pageSize);

        //return $provider->getModels();

        $a = new Pessoas();
        return $a->getIdChat(2); // testando
    }
}