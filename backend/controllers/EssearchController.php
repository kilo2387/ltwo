<?php
/**
 * Created by PhpStorm.
 * User: SEELE
 * Date: 2017/4/1
 * Time: 10:34
 */

namespace backend\controllers;


use yii\base\ErrorException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class EssearchController
{
    public function behaviors(){
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'actions'=>['index', 'between', 'grange', 'small', 'and', 'or'],
                        'allow'=>true,
                    ],
                    [
                        'actions'=>['index', 'between', 'grange', 'small', 'and', 'or'],
                        'allow'=>true,
                        'roles' => ['@'],
                    ]
                ]
            ],
            'verbs'=>[
                'class'=>VerbFilter::className(),
                'actions'=>[]
            ],
        ];
    }

    public function ations(){
        return [
            'errors'=>[
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    public function actionBetween(){

    }
}