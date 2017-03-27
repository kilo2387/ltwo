<?php
namespace backend\controllers;
use Yii;
use backend\models\ESearch;
use yii\elasticsearch\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
/**
 * Site controller
 */
class EstestController extends Controller
{
    private $_es;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $this->_es = new ESearch();
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'read', 'go', 'select-one',
                            'select-all', 'count', 'up' ,'se', 'insert', 'create-type'
                        ],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * 添加一条记录
     * @param $data
     */
    public function createOne($data){
        //primaryKey 定义 _id 字段，当不设置改值时，会随机生成22位字符。
        $record = ESearch::findOne($data['id']);
        if(!empty($record)){
            die('已有记录，不能添加操作!');
        }
        $data['primaryKey'] = $data['id'];
        $ret = ESearch::createOne($data['primaryKey'],$data);
        print_r($ret);die();
    }

    /**
     * 更新一条记录
     * @param $data
     */
    public function updateOne($data){
        $obj = ESearch::findOne($data['id']);
        if(empty($obj)){
            die('没有记录，无法更新操作!');
        }
        $ret = ESearch::updateRecord($obj['primaryKey'],$data);
        print_r($ret);die();
    }

    public function deleteOne($id){
        $customer = ESearch::findOne($id);
        if(empty($customer)){
            die('暂无数据');

        }
        $ret = $customer->delete();
        print_r($ret);die();
    }


    /**
     * elasticsearch 操纵入口DML data manipulation language
     */
    public function actionGo(){
        $options = [
            'id'=>1,
            'name' => 'hahahaha&',
            'address' => 'haohoaod',
            'update_datetime' => time()
        ];

        //更新
        $this->updateOne($options);
        //添加
//        $this->createOne($options);
        //删除
//        $this->deleteOne($options['id']);
        die('没有操作');
    }

    /**
     * 查找一条id为$id的记录
     * @param $id
     */
    public function actionSelectOne($id){
//        $ret = ArrayHelper::toArray(ESearch::findOne($id));
        $ret = ESearch::findOne($id);
//        foreach ($ret as $value){
//            print_r($value);
//        }
        print_r($ret->getScore());
        if(empty($ret)){
            die('没有记录!');
        }
        print_r($ret);die();
    }

    public function actionSelectAll(){
        $ret = ArrayHelper::toArray(ESearch::find()->all()
        );
        if(empty($ret)){
            die('没有记录!');
        }
        print_r($ret);die();
    }

    /**
     * 统计
     *
     * find()创建一个ActiveQuery对象
     * query() 设置这个对象的查询属性
     * 执行count方法：
     * 1、生成数据库对象
     * 2、请求elastic
     * 3、。。。。。
     *
     */
    public function actionCount(){
//        $ret = ESearch::find()->where(['name' => ['like','hahahah']])->count();
//        {"size":10,"query":{"constant_score":{"filter":{"bool":{"must":[{"term":{"name":"mm-m"}}]}}}}}
        $ret = ESearch::find()->query(["match" => ["name" => "hahahaha"]])->count();
//        {"size":10,"query":{"match":{"name":"mm-m"}}}
//        if(!$ret){
//            die('没有记录!');
//        }
        print_r($ret);die('e');

//        $url = 'http://127.0.0.1:9200/catalog/user/_count';
////        $url = 'http://127.0.0.1:9200/catalog/user/_search?size=0';
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //不直接输出
//        $output = curl_exec($ch);
//
//        curl_close($ch);
//        $ret = json_decode($output, true);
//        print_r($ret['count']);

    }
    public function actionUp(){
        $url = 'http://127.0.0.1:9200/catalog/user/7/_update'; //?op_type=create
        $ret = '{"doc":{"id":"4","name":"mmm","address":"mmmmmmmmmmm","update_datetime":1490555261}}';
//        {"doc":{"registration_date":1490558613}}
//        $ret = json_decode($ret, true);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_POST, 1 ); //设置post
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $ret );
        $output = curl_exec($ch);
        var_dump(curl_error($ch));

        curl_close($ch);
    }

    /**
     * 未完方法 GET 参数
     */
    public function actionSe(){
        $ret = '{"size":10,"query":{"constant_score":{"filter":{"false":{"must":[{"term":{"name":"mm-m"}}]}}}}}';
        $url = 'http://127.0.0.1:9200/catalog/user/_search?size=0';
//        $ret = http_request(1,$ret);
        print_r($ret);die();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt ($ch, CURLOPT_POST, 1 ); //设置post
//        curl_setopt ($ch, CURLOPT_POSTFIELDS, $ret);
        $output = curl_exec($ch);
        var_dump(curl_error($ch));

        curl_close($ch);
    }

    public function actionCreateType(){
//        $ret =  '{"title": "My first blog entry","text":  "Just trying this out..."}';
        $url = 'http://127.0.0.1:9200/catalog/book';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_POST, 1 ); //设置post
//        curl_setopt ($ch, CURLOPT_POSTFIELDS, $ret);
        $output = curl_exec($ch);
        var_dump(curl_error($ch));
        curl_close($ch);
    }
}
