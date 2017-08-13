<?php
/**
 * Created by kilo with IntelliJ IDEA on 2017/6/15 21:50.
 *
 */


namespace backend\Controllers;
use yii\web\Controller;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class RedisController extends Controller{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['connect', 'index','login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    public function actionConnect(){
        die('wrer');
        $redis = new \Redis();
        var_dump($redis);die();
    }

}