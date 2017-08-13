<?php
namespace aweibo\models;
use Yii;
use yii\base\Model;
//use common\models\User;
use yii\redis\ActiveRecord;

/**
 * Signup form
 */
class SignupForm extends ActiveRecord
{
    public $username;
    public $email;
    public $password;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
//        var_dump($this->username);die();
//
//        if (!$this->validate()) {
//            die('vlals');
//            return null;
//        }
//        die('vali');
        
//        $user = new User();
//        $user->username = $this->username;
//        $user->email = $this->email;
//        $user->setPassword($this->password);
//        $user->generateAuthKey();

//        var_dump(Yii::$app->cache->get('user:username:'.$this->username.':id'));die('eresd');
//        var_dump($id = Yii::$app->redis->incr('userid'));die();
        if(!Yii::$app->redis->get('user:username:'.$this->username.':id')){
            $id = Yii::$app->redis->incr('userid');
            Yii::$app->redis->set('user:userid:'.$id.':username',  $this->username);
            Yii::$app->redis->set('user:userid:'.$id.':email', $this->email);
            Yii::$app->redis->set('user:userid:'.$id.':password', $this->password);
            Yii::$app->redis->set('user:username:'.$this->username.':id', $id);
            return array('id'=>$id,'user'=>$this->username);
        }else{
            var_dump('有人了');die();
            return false;
        }


    }


    public function Login(){
        die('3');
        if(session('auth')){
            return true;
        }else{
            return false;
        }
    }
}
