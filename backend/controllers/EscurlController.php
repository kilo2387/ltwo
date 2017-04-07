<?php
/**
 * Created by PhpStorm.
 * User: SEELE
 * Date: 2017/4/7
 * Time: 13:54
 */
namespace backend\controllers;
use yii\web\Controller;
use yii\filters\AccessControl;
class EscurlController extends Controller{
    public function behaviors(){
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'actions'   =>  ['index', 'all'],
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

}