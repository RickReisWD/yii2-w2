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

        return $provider->getModels();

        // $a = new Pessoas();
        // return $a->getIdChat(2); // testando
    }

    public function actionView(){

        // $url = "http://events.webdanca.com:8084";
        $url = "http://events.webdanca.com:8084";
        $request_header = ["Content-Type: application/json"];
        $body = ["email"=>"zeus@webdanca.com", "password"=>"zeus"];
        array_push($request_header, 'Authorization: Basic emV1c0B3ZWJkYW5jYS5jb206emV1cw==');
        $token = $this->req('POST', $url.'/v1/auth/authenticate', $request_header, $body);
        $token = $token->access_token;

        $planners = $this->planners($url, $token);
        $features = $this->features($url, $token);
        $environments = $this->environments($url, $token, $planners, $features);
        $events = $this->events($url, $token, $planners, $environments);
        return $planners;

    }



    public function req($method = null, $url, array $header = ["Content-Type: application/json",], $body = null){

        $ch = curl_init();
     
        curl_setopt($ch, CURLOPT_URL, $url);

        if($method == 'POST'){

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $res = curl_exec($ch);

        return json_decode($res);
    }

    public function planners($url, $token){
        $resp = $this->req('GET', $url.'/v1/admin/planners', ["Authorization: Bearer $token"]);
        if($resp == null){
            for($i = 0; $i<10; $i++){
                $this->req('POST', $url.'/v1/admin/planners', ["Authorization: Bearer $token"], [
                    "fancy_name" => "Planner $i",
                    "name" => "Nome $i",
                    "status" => "active",
                    "phone" => "4399999999",
                    "email" => "email2@email$i.com"
                ]);
            }
        }
        return $resp = $this->req('GET', $url.'/v1/admin/planners', ["Authorization: Bearer $token"]);
    }

    public function features($url, $token){
        
        return $this->req('GET', $url.'/v1/admin/features', ["Content-Type: application/json","Authorization: Bearer $token"]);

    }

    public function environments($url, $token, $planners, $features){
        $resp = $this->req('GET', $url.'/v1/admin/environments', ["Authorization: Bearer $token"]);
        if($resp == null){
            $address = require __DIR__ . '/arrayAddress.php';
            $lang = require __DIR__ . '/arrayEnviLang.php';
            $types = $this->req('GET', $url.'/v1/admin/environments-types', ["Authorization: Bearer $token"]);
            foreach($address as $item){
                $feat = [
                    "feature_id" => json_decode(json_encode($features[rand(0, count($features)-1)]))->id,
                    "language_iso_code" => "pt-BR",
                    "observation" => ""
                ];
                $body = [
                    "environment_type_id" => json_decode(json_encode($types[rand(0, count($types)-1)]))->id,
                    "planner_id" => json_decode(json_encode($planners[rand(0, count($planners)-1)]))->id,
                    "features" => json_decode(json_encode([$feat])),
                    "capacity" => "1000",
	                "layout_map" => "{\"layout_map\":{\"konva\":{\"width\":1,\"height\":1,\"x\":0,\"y\":0,\"scaleX\":1,\"scaleY\":1,\"draggable\":false},\"seats\":[{\"id\":\"asd56567sda\",\"x\":4,\"y\":9,\"text\":\"\",\"fontSize\":\"\",\"name\":\"seat-text\",\"draggable\":false,\"offsetX\":\"\",\"offsetY\":8,\"lineHeight\":0},{\"id\":\"asd56567sda\",\"x\":4,\"y\":9,\"text\":\"\",\"fontSize\":\"\",\"name\":\"seat-text\",\"draggable\":false,\"offsetX\":\"\",\"offsetY\":8,\"lineHeight\":0}],\"podiums\":{\"id\":0,\"rotation\":0,\"x\":\"\",\"y\":10,\"fill\":\"grey\",\"stroke\":\"black\",\"strokeWidth\":2,\"strokeScaleEnabled\":false,\"name\":\"podium\",\"draggable\":true},\"texts\":{\"id\":\"\",\"rotation\":0,\"x\":\"\",\"y\":10,\"text\":\"\",\"fontSize\":\"\",\"fill\":\"#000\",\"padding\":5,\"align\":\"center\",\"name\":\"text\",\"draggable\":true}}}",
                    "address" => json_encode($item),
                    "lang" => json_decode(json_encode($lang[rand(0, count($lang)-1)]))
                ];

                $this->req('POST', $url.'/v1/admin/environments', ["Content-Type: application/json","Authorization: Bearer $token"], json_encode($body));

            }
            $resp = $this->req('GET', $url.'/v1/admin/environments', ["Authorization: Bearer $token"]);
        }
        return $resp;
    }

    public function events($url, $token, $planners, $environments){
        $resp = $this->req('GET', $url.'v1/admin/events', ["Authorization: Bearer $token"]);
        if($resp == null){
            $types = $this->req('GET', $url.'/v1/admin/events-types', ["Authorization: Bearer $token"]);
            $images = require __DIR__ . '/arrayImages.php';
            $events = require __DIR__ . '/arrayEvents.php';
            foreach($planners as $planner){
                foreach($events as $event){
                    $body = [
                        "planner_id" => json_decode(json_encode($planner))->id,
                        "event_type_id" => json_decode(json_encode($types[rand(0, count($types)-1)]))->id,
                        "environment_id" => json_decode(json_encode($environments[rand(0, count($environments)-1)]))->id,
                        "maximum_sale_limit" => 1000,
                        "image" => $images[rand(0, count($images)-1)],
                        "lang" => json_decode(json_encode($event))
                    ];
                    $eventCreated = $this->req('POST', $url.'/v1/admin/events', ["Content-Type: application/json","Authorization: Bearer $token"], json_encode($body));

                    $this->sessions($url, $token, $eventCreated->id);
                }
            }
            $resp = $this->req('GET', $url.'/v1/admin/events', ["Authorization: Bearer $token"]);
        }
        return $resp;
    }

    public function sessions($url, $token, $event_id){

        $sessions = [];
        $finalDatetime = "2023-10-29 00:00:00";
        for ($i = 0; $i < 50; $i++) {
            $rand = mt_rand(1, 24 * 60 * 60);
            $initialDatetime = date("Y-m-d H:i:s", strtotime($finalDatetime)+$rand);
            $daysToAdd = mt_rand(1, 24 * 60 * 60);
            $finalDatetime = date("Y-m-d H:i:s", strtotime($initialDatetime) + $daysToAdd);
            $sessions[] = [
                "initial_datetime" => $initialDatetime,
                "final_datetime" => $finalDatetime
            ];
        }
       
        $rand2 = rand(0, count($sessions)-5);
        for($j=0; $j<rand(1, 4); $j++){
            $session = $sessions[$rand2+$j];
            $session = json_decode(json_encode($session));
            $body = [
                "event_id" => $event_id,
                "initial_datetime" => $session->initial_datetime,
                "final_datetime" => $session->final_datetime,
                "maximum_sale_limit" => 100
            ];
            $this->req('POST', $url.'/v1/admin/sessions', ["Content-Type: application/json", "Authorization: Bearer $token"], json_encode($body));
        }
        
    }
}