<?php
namespace backend\controllers;

use backend\models\Jobnumber;
use backend\models\Team;
use Yii;
use Codeception\Module\MongoDb;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\mongodb\Command;
use yii\mongodb\Connection;
use yii\mongodb\rbac\MongoDbManager;
use yii\web\Controller;
use yii\filters\AccessControl;
class HaController extends Controller
{
    public function behaviors(){
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'actions'   =>  ['find', 'list', 'group', 'sum'],
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
        $HeaderTime = microtime();
        //pdo方式查找数据
//        $query = new Query();
//        $query->where("Fcreate_time >= '2017-04-01'")->from ('t_count_team' );
//        $rows = $query->all();

        //ActiveQuery查找数据
        $rows = ArrayHelper::toArray(Team::find()->where("Fcreate_time >= '2017-04-01'")->all());
//        var_dump($rows);
        // execute command:
//        $result = Yii::$app->mongodb->createCommand(['listIndexes' => 'jobnumber'])->execute();
//        // execute query (find):
//        $cursor =Yii::$app->mongodb->createCommand(['projection' => ['title' => true]])->query('jobnumber');

//        printf(" total run: %.2f s<br>".
//            "memory usage: %.2f M<br> ",
//            microtime(true)-$HeaderTime,
//            memory_get_usage() / 1024 / 1024 );
//        die();

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
        $add->batchInsert('team',$rows);
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

        $redis = new \Redis();
        var_dump($redis);die();
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
    public function actionGroup(){
        $HeaderTime = microtime(true);
//        echo $HeaderTime;die();
        echo Yii::$app->mongodb->createCommand()->count('team');
        $cursor = Yii::$app->mongodb->createCommand(['Forder_num' => 1,
                    'Fcreate_time' => 1 ])->aggregate('team', [
//            [
//                '$project' =>[
//                    'Forder_num' => 1,
//                    'Fcreate_time' => 1 ,
//                ]
//            ],
            [
                '$match' => [
                    'Fcreate_time' => ['$gte' => '2017-04-01'],
                ],
            ],
            [
                '$group' => [
                    '_id' => '$Fteam_id',
                    'Forder_num' => [
                        '$sum' => 'Forder_num'
                    ],
                ]
            ],
        ]);
//        printf(" total run: %.2f s<br>".
//            "memory usage: %.2f M<br> ",
//            microtime(true)-$HeaderTime,
//            memory_get_usage() / 1024 / 1024 );
//        die();
        var_dump($cursor);
//        $HeaderTime = microtime(true);
//        $query = new Query();
//        $query->where("Fcreate_time >= '2017-04-14'")->select([])->from ('t_count_jobnumber' )->groupBy('Fjob_number');
//        $rows = $query->all();
//        printf(" total run: %.2f s<br>".
//            "memory usage: %.2f M<br> ",
//            microtime(true)-$HeaderTime,
//            memory_get_usage() / 1024 / 1024 );
        die();
        var_dump($rows);

    }
    public function actionSum1(){
        $manager = new Connection(['dsn'=>'mongodb://localhost:27017/statis']);
        $manager->open();


//        var_dump($dsn);die('my');
        $add = new Command([
            'db' => $manager,
            'databaseName' => 'statis',
            'document' => '',
        ]);
//        $command = new Command([
//            'aggregate' => 'team',
//            'pipeline' => [
//                ['$group' => ['_id' => '$Fteam_id', 'sum' => ['$sum' => '$Forder_num']]],
//            ],
////            'cursor' => new stdClass,
//        ]);
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
                    '$create_time' => ['$gte' => '2017-04-15'],
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
//        $cursor = $manager->executeCommand('db', $command);
        var_dump($ret);die();
//        $command = new MongoDB\Driver\Command([
//            'aggregate' => 'collection',
//            'pipeline' => [
//                ['$group' => ['_id' => '$y', 'sum' => ['$sum' => '$x']]],
//            ],
//            'cursor' => new stdClass,
//        ]);
//        $cursor = $manager->executeCommand('db', $command);
    }

    public function actionSum(){
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
                    'create_time' => ['$gte' => '2017-04-16'],
                ],
            ],
            [
                '$group' => ['_id' => '$team_id', 'count' => ['$sum' =>'$order_num'], 'price' => ['$sum' =>'$amount_price']]
            ],

            [
                '$sort' => ['count' => -1]
            ]
        ];
        $ret = Yii::$app->mongodb->createCommand()->aggregate('team',$plines);
        var_dump($ret);die();

    }
}