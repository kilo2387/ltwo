<?php
/**
 * Created by kilo with IntelliJ IDEA on 2017/6/20 23:16.
 *
 */

namespace aweibo\models;
use Yii;
use yii\base\Model;
//use common\models\User;
use yii\web\Session;
use yii\redis\ActiveRecord;

class Login extends ActiveRecord{
    public $username;
    public $password;
    public $rememberMe = true;

    public function isLogin(){


        if(isset($_SESSION['auth']) && !empty($_SESSION['auth'])){

            return true;
        }else{
            return false;
        }
    }

    public function Login(){
        $id = Yii::$app->redis->get('user:username:'.$this->username.':id');
var_dump($this->username);die();
        if($id &&  Yii::$app->redis->get('user:userid:'.$id.':password') == $this->password){
           $_SESSION['auth'] = ['userid'=>$id, 'username'=>$this->username];
           return $_SESSION['auth'];
        }else{
            return false;
        }

    }
}