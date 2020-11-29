<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use app\models\Assigment;
use app\models\Roles;
use app\models\RolesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class RoleController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new User();
        if (($post = Yii::$app->request->post()) && !empty($post['password'])) {
			$hash = $model->setPassword($post['password']);
			$model->load($post);
			$model->save();
			$id = Yii::$app->db->getLastInsertID();
			
			$assigment = new Assigment();
			$assigment->createNewAssigment($id);
			
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$roles = RolesSearch::rolesList();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$assigment = Assigment::findOne(['user_id' => $id]);
			$assigment->load(Yii::$app->request->post());
			
			if ($assigment->load(Yii::$app->request->post()) && $assigment->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			}
        }

        return $this->render('update', [
            'model' => $model,
            'roles' => $roles,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
