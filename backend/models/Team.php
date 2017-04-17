<?php
/**
 * Created by PhpStorm.
 * User: SEELE
 * Date: 2017/4/17
 * Time: 20:27
 */

namespace backend\models;

use yii\db\ActiveRecord;
class Team extends ActiveRecord {
    public static function tableName(){
        return 't_count_team';
    }
}