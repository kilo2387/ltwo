<?php
/**
 * Created by kilo with IntelliJ IDEA on 2017/6/26 0:03.
 *
 */

namespace aweibo\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\redis;


class RedistestController extends Controller{

    /**
     * @inheritdoc
     */
    public function behaviors(){
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'actions'   =>  ['add', 'release'],
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
    public function actionAdd(){
//        $r = new redis\Connection();
//        $r->database = 0;

        $redis = new \Redis();
        $redis->connect('localhost');

        var_dump($redis->get('a11'));

////        var_dump($r);
////        $r->connect('localhost');
//        var_dump($r->g);
    }


    public function actionRelease(){
        $redis = new \Redis();
        $redis->connect('localhost');
        $wb_id = $redis->incr('global:wb_id');
        $content = 'what the fuck is 我不知道 that';
        $redis->hMset('wb:wb_id:'.$wb_id, array('userid'=>$_SESSION['auth']['userid'], 'time'=>time(), 'content'=>$content));
    }
}