<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\User;
use app\models\UserSearch;
use app\models\ContactForm;
use app\models\Chat;
use app\models\Roles;
use app\models\Assigment;

class SiteController extends Controller
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['logout', 'uncorrect'],
				'rules' => [
					[
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'],
					],
					[
						'actions' => ['uncorrect'],
						'allow' => true,
						'roles' => ['updateUncorrect'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['POST'],
					'delete' => ['POST'],
				],
			],
		];
	}

	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	public function actionIndex()
	{
		$model = new Chat();

		if (Yii::$app->user->can('admin')) {
			$id = Yii::$app->user->id;
			$messages = $model->find()->all();
			return $this->render('adm_index', [
				'messages' => $messages,
				'model' => $model,
				'admin_id' => $id,
			]);
		} else {
			$messages = $model->getMessages();
			return $this->render('index', [
				'messages' => $messages,
				'model' => $model,
			]);
		}

	}

	public function actionLogin()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		}

		$model->password = '';
		return $this->render('login', [
			'model' => $model,
		]);
	}

	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}

	public function actionUncorrect()
	{
		$model = new Chat();
		$post = !empty(Yii::$app->request->post('Chat')['correct']) ? Yii::$app->request->post('Chat')['correct'] : '';
		if ($post) {
			$model->onMessage($post);
			$this->refresh();
		}

		$messages = $model->getUncorrect();
		return $this->render('uncorrect', [
			'messages' => $messages,
		]);
	}

	public function actionAddmessage() {
		if (Yii::$app->user->isGuest)
			return false;

		$model = new Chat();
		
		if ($model->load(Yii::$app->request->post())) {
			$user = Yii::$app->user->getId();		
			$model->saveNewMessage($user);
		} else {
			echo "Error adding record<br>";
		}
		
		return $this->goHome(); 
	}
	
	public function actionOffmessage() {
		$id = isset(Yii::$app->request->post()['off-message']) ? addslashes(Yii::$app->request->post()['off-message']) : '';
		$model = new Chat();
		$model->offMesssage($id);

		return $this->goHome();
	}
}
