<?php
/**
 * Created by PhpStorm.
 * User: SEELE
 * Date: 2017/4/7
 * Time: 13:54
 */
namespace backend\controllers;
<<<<<<< HEAD
use yii\helpers\Json;
=======
>>>>>>> 1659e18... dele
use yii\web\Controller;
use yii\filters\AccessControl;
class EscurlController extends Controller{
    public function behaviors(){
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
<<<<<<< HEAD
                        'actions'   =>  ['index', 'all', 'bulk', 'selectdata', 'newindex' , 'delindex', 'addalign',
                        'copydata', 'change-alias', 'newdata'],
=======
                        'actions'   =>  ['index', 'all'],
>>>>>>> 1659e18... dele
                        'allow'     =>  true,
                    ],
                    [
                        'actions'   =>  [],
                        'allow'     =>  true,
                        'roles'     =>  ['@']
                    ]
                ]
            ],
//            'verbs'=>[]
        ];
    }

    public function actionAll(){
        $url = 'http://localhost:9200/_search?pretty';
        $ret = '{"match": {"_all": "record"}}';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'get');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $ret );
        $output = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
    }

<<<<<<< HEAD
    /**
     * 批量添加
     * post
     *
     */
    public function actionBulk(){
        $url = 'http://localhost:9200/catalog/products/_bulk';
        $ret = '{ "index": { "_id": 1 }}
            { "price" : 10, "productID" : "XHDK-A-1293-#fJ3" }
            { "index": { "_id": 2 }}
            { "price" : 20, "productID" : "KDKE-B-9947-#kL5" }
            { "index": { "_id": 3 }}
            { "price" : 30, "productID" : "JODL-X-1937-#pV7" }
            { "index": { "_id": 4 }}
            { "price" : 30, "productID" : "QQPX-R-3956-#aD8" }';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $ret );
        $output = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
    }

    /**
     * 结构化搜索
     */
    public function actionSelectdata(){
        $ret = '{
            "query" : {
                "bool" : {
                    "filter" : {
                        "term" : {
                            "productID" : "KDKE-B-9947-#kL5"
                        }
                    }
                }
            }
        }';

        $url = 'http://localhost:9200/catalog_v3/products/_search';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $ret );
        $output = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
    }

    /**
     * 重建索引
     */
    public function actionNewindex(){
        $ret = '{
            "mappings" : {
                "products" : {
                    "properties" : {
                        "productID" : {
                            "type" : "string",
                            "index" : "analyzed"
                        }
                    }
                }
            }
        
        }';

        $url = 'http://localhost:9200/catalog_v7/';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $ret );
        $output = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
    }


    /**
     * 删除索引
     */
    public function actionDelindex(){
        $url = 'http://localhost:9200/catalog_v6/';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        $output = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
    }

    /**
     * 指向别名
     */
    public function actionAddalign(){

        $url = 'localhost:9200/catalog_v3/_alias/catalog';
//        $ret = '{  "aliases":{  "catalog_alias":{}}}';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $ret );
        $output = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
    }

    public function actionChangeAlias(){
        $ret = '{
            "actions": [
        { "remove": { "index": "catalog_v3", "alias": "catalog" }},
        { "add":    { "index": "catalog_v7", "alias": "catalog" }}
    ]
}';
        $url = 'localhost:9200/_aliases';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $ret);
        $output = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

    }

    public function actionCopydata(){
        $msg[] = $this->actionNewindex();
//die();
        $ret = '{
            "query":{
                "bool":{
                    "should":[
                    {
                        "range": {
                            "price": {
                                "gte":  5,
                                "lt":   35
                            }
                        }
                     },
                     {"term":{"price":30}}
                    ]
                }
            },
            "size": 67
        }';

        $url = 'localhost:9200/catalog_v3/products/_search?pretty';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $ret );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //不直接输出
        $output = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        $info = Json::decode($output);

        if(!$info){
            $info or $msg[] = 'DATA NULL'; die('DATA NULL');
        }


            $body = '';
            foreach ($info['hits']['hits'] as $key => $row) {

                $index = ['index'=>['_id'=>$row['_id']]];
                $body .= Json::encode($index) . "\n" .Json::encode($row['_source']) . "\n";

            }
        $msg[] = $this->actionNewdata($body);
        $msg[] = $this->actionChangeAlias();
        print_r($msg);

    }

    public function actionNewdata($data){
//        $this->actionBulk();
//        $this->actionChangeAlias();


//        $data = '{
//        "_index" : "catalog_v4",
//        "_type" : "products",
//        "_id" : "3",
//        "_score" : 2.0,
//        "_source" : {
//          "price" : 30,
//          "productID" : "JODL-X-1937-#pV7"
//        }
//      },
//      {
//        "_index" : "catalog_v4",
//        "_type" : "products",
//        "_id" : "2",
//        "_score" : 1.0,
//        "_source" : {
//          "price" : 20,
//          "productID" : "KDKE-B-9947-#kL5"
//        }
//      },
//      {
//        "_index" : "catalog_v4",
//        "_type" : "products",
//        "_id" : "1",
//        "_score" : 1.0,
//        "_source" : {
//          "price" : 10,
//          "productID" : "XHDK-A-1293-#fJ3"
//        }
//      }';
        $url = 'localhost:9200/catalog_v7/products/_bulk';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data );
        $output = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);



    }

=======
>>>>>>> 1659e18... dele
}