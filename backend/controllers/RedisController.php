<?php
/**
 * Created by kilo with IntelliJ IDEA on 2017/6/15 21:50.
 *
 */


namespace backend\controllers;

use Codeception\Module\Redis;
use yii\web\Controller;

//use yii\filters\VerbFilter;
//use yii\filters\AccessControl;





class RedisController extends Controller{

    /**
     * @inheritdoc
     */
//    public function behaviors()
//    {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'actions' => ['connect', 'index','login', 'error'],
//                        'allow' => true,
//                    ],
//                    [
//                        'actions' => ['logout', 'index'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
//        ];
//    }


    public function actionConnect(){

        $redis = new \Redis();
//        $redis->auth('jkljkl');
//        $redis->connect('112.74.182.162', 6379);
        $redis->connect('localhost', 6379);
        $redis->auth('jkljkl');
        echo "Connection to server sucessfully";
        //查看服务是否运行
        echo "Server is running: " . $redis->ping();
    }

}