<?php
namespace backend\controllers;

use backend\models\Jobnumber;
use backend\models\Team;
use Yii;
use Codeception\Module\MongoDb;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\mongodb\Command;
use yii\mongodb\Connection;
use yii\mongodb\rbac\MongoDbManager;
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
                        'actions'   =>  ['find', 'list', 'count', 'sum'],
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
        //pdo方式查找数据
//        $query = new Query();
//        $query->where("Fcreate_time >= '2017-04-01'")->from ('t_count_team' );
//        $rows = $query->all();

        //ActiveQuery查找数据
        $rows = ArrayHelper::toArray(Team::find()->where("Fcreate_time >= '2017-04-01'")->all());

        // execute batch (bulk) operations:
        $dsn = new Connection(['dsn'=>'mongodb://localhost:27017/statis']);
        $add = new Command([
            'db' => $dsn,
            'databaseName' => 'statis',
            'document' => '',
        ]);
        $add->batchInsert('team',$rows);
        //Yii::$app->mongodb->createCommand()->batchInsert('jobnumber',$rows);

//        Yii::$app->mongodb->createCommand()
//            ->addInsert(['name' => 'new'])
//            ->addUpdate(['name' => 'existing'], ['name' => 'updated'])
//            ->addDelete(['name' => 'old'])
//            ->executeBatch('customer');

    }
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
    public function actionCount(){
        $HeaderTime = microtime(true);
        echo Yii::$app->mongodb->createCommand()->count('team');
    }
    public function actionSum1(){
        $manager = new Connection(['dsn'=>'mongodb://localhost:27017/statis']);
        $manager->open();
        $add = new Command([
            'db' => $manager,
            'databaseName' => 'statis',
            'document' => '',
        ]);
        $plines = [
            [
                '$project' =>[
                    '_id'=>0,
                    'team_id'=>'$Fteam_id',
                    'order_num' => '$Forder_num',
                    'amount_price' => '$Famount_price',
                    'create_time' => '$Fcreate_time' ,
                ]
            ],
            [
                '$match' => [
                    '$create_time' => ['$gte' => '2017-02-01'],
                ],
            ],
            [
                '$group' => ['_id' => '$team_id', 'count' => ['$sum' =>'$order_num'], 'price' => ['$sum' =>'$amount_price']]
            ],

            [
                '$sort' => ['count' => -1]
            ]
        ];
        $option = [

            'allowDiskUse' => true
        ];
        $ret = $add->aggregate('team',$plines, $option);
        var_dump($ret);die();
    }

    public function actionSum(){
        $plines = [
            [
                '$project' =>[
                    '_id'=>0,
                    'jobnumber'=>'$Fjob_number',
                    'order_num' => '$Forder_num',
                    'amount_price' => '$Famount_price',
                    'create_time' => '$Fcreate_time' ,
                ]
            ],

            [
                '$match' => [
                    'create_time' => ['$gte' => '2017-03-15'],
                ],
            ],
            [
                '$group' => [
                    '_id' => '$team_id',
                    'count' => ['$sum' =>'$order_num'],
                    'price' => ['$sum' =>'$amount_price']
                ]
            ],

            [
                '$sort' => ['count' => -1]
            ],
            [
                '$skip' => 0
            ],
            [
                '$limit' => 5
            ]
        ];
        $ret = Yii::$app->mongodb->createCommand()->aggregate('jobnumber',$plines);
        var_dump($ret);die();

    }
}