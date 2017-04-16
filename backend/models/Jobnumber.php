<?php
/**
 * Created by PhpStorm.
 * User: kilo
 * Date: 2017/4/17
 * Time: 0:40
 */

namespace backend\models;
use yii;
use yii\mongodb\ActiveRecord;
class Jobnumber extends ActiveRecord
{
    public static function collectionName()
    {
        return 'jobnumber';
    }

    public function findData(){
        $customer = new Jobnumber ();
        $customer->find();
        return $customer;
    }

    public function addOne($value){
        $customer = new Jobnumber ();
        $customer->insert(false, $value);
    }

    public function saveInfo()
    {
        $customer = new Jobnumber ();
        $customer->find();
        return $customer;
    }
    public function attributes()
    {
        return [
            '_id',
//            'title',
//            'description',
//            'by',
//            'url',
//            'tags',
//            'likes'
            'Fjob_number',
  'Fuser_name',
  'Forganization_id ',
  'Forganization_name',
  'Forder_num',
  'Famount_price',
  'Fonway_num',
  'Fonway_price',
  'Fbargain_num',
  'Fquotation_price',
  'Fdetect_price',
  'Fcreate_time',
        ];//{ "_id" : ObjectId("58f2038924e4fefd83c3559c"), "title" : "PHP 教程", "description" : "PHP 是一种创建动态交互性站点的强有力的服务器端脚本语言。", "by" : "菜鸟教程",
// "url" : "http://www.runoob.com", "tags" : [ "php" ], "likes" : 200 }

    }
//    public static function getDb()
//    {
//        return \Yii::$app->get('mongodb');
//    }
//
//    public static function find()
//    {
//        return Yii::createObject(yii\mongodb\ActiveQuery::className(), [get_called_class()]);
//    }
}