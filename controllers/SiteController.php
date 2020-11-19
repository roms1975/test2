<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\User;
use app\models\ContactForm;
use app\models\Chat;

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
                    'logout' => ['post'],
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

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
		
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionUncorrect()
    {
		$model = new Chat();
		if (!empty(Yii::$app->request->post('Chat')['correct'])) {
			$rows = Yii::$app->request->post('Chat')['correct'];
			foreach ($rows as $key => $data) {
				$row = $model->find()->where('id = :id', [':id' => $key])->one();
				if (!$row)
					continue;
				
				$row->correct = addslashes($data);
				$row->save();
			}
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
			$model->user = Yii::$app->user->getId();
			$model->correct = 1;
			$model->save();
		} else {
			echo "Error adding record<br>";
		}
		
		return $this->goHome(); 
	}
	
	public function actionOffmessage() {
		$id = isset(Yii::$app->request->post()['off-message']) ? addslashes(Yii::$app->request->post()['off-message']) : '';
		$model = new Chat();
		$row = $model->find()->where('id = :id', [':id' => $id])->one();
		if (!empty($row)) {
			$row->correct = 0;
			$row->save();
		}
		
		return $this->goHome();
	}
	
	public function actionEditusers() {
		$model = new User();
		
		if (!empty(Yii::$app->request->post('User'))) {
			foreach (Yii::$app->request->post('User') as $id => $row) {
				$user = User::findOne($id);
				$user->role = $row['role'];
				
error_log(print_r($user, true), 3, Yii::getAlias('@app/log/roms.log'));
error_log("---------------------------------\n", 3, Yii::getAlias('@app/log/roms.log'));
				
				$user->save();
			}
		}
		
		$users = $model->find()->all();
		
		return $this->render('showusers', [
			'users' => $users,
		]);
	}
	
/*	
	public function actionAdduser() 
	{
		if (empty($model)) {
			$user = new User();
			$user->username = 'user1';
			$user->email = 'user1@ya.ru';
			$user->setPassword('user1');
			$user->generateAuthKey();
			if ($user->save()) {
				echo 'good';
			} else {
				echo 'fail';
			}
		}
	}
*/	
}
