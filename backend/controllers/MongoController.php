<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;

use yii\mongodb\Query;

/**
 * Mongo controller
 */
class MongoController extends Controller
{

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
