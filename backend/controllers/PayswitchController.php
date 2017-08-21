<?php
/**
 * Created by kilo with IntelliJ IDEA on 2017/8/21 23:45.
 *
 */

namespace backend\controllers;
use Yii;
use backend\models\Payswitch;
use yii\filters\AccessControl;
use yii\web\Controller;

class PayswitchController extends Controller{
    public function behaviors(){
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'actions'   =>  ['read'],
                        'allow'     =>  true,
                    ],
                    [
                        'actions'   =>  ['read'],
                        'allow'     =>  true,
                        'roles'     =>  ['@']
                    ]
                ]
            ],
//            'verbs'=>[]
        ];
    }
    public function actionRead(){

        $model = Payswitch::findOne(1);
        if(Yii::$app->request->isAjax){
            if($model->config_value){
                $model->config_value = 0;
            } else{
                $model->config_value = 1;
            }
            $model->updated = time();

            return $model->save();
        }

        return $this->render('read', [
            'model'=>$model
        ]);
    }
}