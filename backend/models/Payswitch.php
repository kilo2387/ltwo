<?php
/**
 * Created by kilo with IntelliJ IDEA on 2017/8/21 23:46.
 *
 */
namespace backend\models;

use yii\db\ActiveRecord;

class Payswitch extends ActiveRecord{
    public static function tableName(){
        return 'payswitch';
    }
}