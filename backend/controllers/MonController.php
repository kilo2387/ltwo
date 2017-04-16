<?php
namespace backend\controllers;

use backend\models\Jobnumber;
use Yii;
use Codeception\Module\MongoDb;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\mongodb\Command;
use yii\mongodb\Connection;
use yii\web\Controller;
use yii\filters\AccessControl;
class MonController extends Controller
{
    public function behaviors(){
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'actions'   =>  ['find', 'list'],
                        'allow'     =>  true,
                    ],
                    [
                        'actions'   =>  [],
                        'allow'     =>  true,
                        'roles'     =>  ['@']
                    ]
                ]
            ],
//            'verbs'=>[]
        ];
    }
//    public function actionIndex()
//    {
//        $collection = Yii::$app->mongodb->getCollection ( 'jobnumber' );
//        $res = $collection->insert ( [
//            'name' => 'John Smith22',
//            'status' => 2
//        ] );
//        var_dump($res);
//    }

    /**
     * 从mysql获取数据,批量添加到mongodb
     */
    public function actionList()
    {
        $query = new Query();
        $query->where("Fcreate_time > '2017-04-16'")->select([])->from ('jobnumber' );
        $rows = $query->all();
        // execute command:
//        $result = Yii::$app->mongodb->createCommand(['listIndexes' => 'jobnumber'])->execute();
//        // execute query (find):
//        $cursor =Yii::$app->mongodb->createCommand(['projection' => ['title' => true]])->query('jobnumber');

        var_dump($rows);

        // execute batch (bulk) operations:


        $dsn = new Connection(['dsn'=>'mongodb://localhost:27017/statis']);


//        var_dump($dsn);die('my');
        $add = new Command([
            'db' => $dsn,
            'databaseName' => 'statis',
            'document' => '',
        ]);
//        var_dump($add);
//        echo '<br>';
        $add->batchInsert('jobnumber',$rows);
//        var_dump($add);die();
       die();
        Yii::$app->mongodb->createCommand()->batchInsert('jobnumber',$rows);

//        Yii::$app->mongodb->createCommand()
//            ->addInsert(['name' => 'new'])
//            ->addUpdate(['name' => 'existing'], ['name' => 'updated'])
//            ->addDelete(['name' => 'old'])
//            ->executeBatch('customer');

    }
//    public function actionView()
//    {
//        $query = new Query ();
//        $row = $query->from ( 'jobnumber' )->one ();
//        echo Url::toRoute ( [
//            'item/update',
//            'id' => ( string ) $row ['_id']
//        ] );
//        var_dump ( $row ['_id'] );
//        var_dump ( ( string ) $row ['_id'] );
//    }
    public function actionFind()
    {
        $provider = new ActiveDataProvider ( [
            'query' => Jobnumber::find (),
            'pagination' => [
                'pageSize' => 10000
            ]
        ] );

        $models = ArrayHelper::toArray($provider->getModels());
        var_dump( $models );
    }
//    public function actionQuery()
//    {
//        $query = new Query ();
//        $query->from ( 'jobnumber' )->where ( [
//            'status' => 2
//        ] );
//        $provider = new ActiveDataProvider ( [
//            'query' => $query,
//            'pagination' => [
//                'pageSize' => 10
//            ]
//        ] );
//        $models = $provider->getModels ();
//        var_dump ( $models );
//    }
    public function actionSave(){
        $res = Jobnumber::saveInfo ();
        var_dump ( $res );
    }
//    public function actionFind(){
//
//        $data = ArrayHelper::toArray(Jobnumber::find());
//        var_dump($data);
//    }
}