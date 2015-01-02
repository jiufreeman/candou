<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;


/**
 * Site controller
 */
class SphinxController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'testupload', 'say', 'sphinx'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
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

	public function actionSphinx() 
	{
		$sql = 'SELECT * FROM test1  WHERE id = :id';
		$params = [
		    ':id' => 1
		];
		$rows = Yii::$app->sphinx->createCommand($sql, $params)->queryAll();
		print_r($rows);
	}

	public function actionSay() 
	{
/*
		$collection = Yii::$app->mongodb->getCollection('cd_movie');

		$query = new Query;
		$query->select(['id', 'name'])
		->from("cd_movie")
		->limit(10);
		$rows = $query->all();
		var_dump($rows);
*/
		$collection = Yii::$app->mongodb->getCollection('re_celebrity_id');

		$query = new Query;
		$query->select(['id'])
			->from("re_celebrity_id")
			->limit(10);
		$rows = $query->all();
		foreach ($rows as $row) {
			print_r($row['id']);
			echo "<br />";
		}

	}

    public function actionTestupload()
    {
        $data = file_get_contents("http://img5.douban.com/view/photo/thumb/public/p2216009426.jpg");
        Yii::$app->upyun->upload("movie", "a.jpg", $data);
//        Yii::app()->upyun->upload($domain,$savedname,$datas,$autoCreateDir=true);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
