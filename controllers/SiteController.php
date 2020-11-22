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
		$roles = Roles::find()
					->select('name')
					->where(['type' => 1])
					->asArray()
					->all();
		
		if (!empty(Yii::$app->request->post('User'))) {
			
			foreach (Yii::$app->request->post('User') as $id => $row) {
				$model2 = new Assigment();
				$role = $model2->findOne(['user_id' => $id]);
				if (!$role) {
					$model2->item_name = $row['role'];
					$model2->user_id = $id;
					$model2->created_at = strtotime('now');
					$model2->save();
				} else if (empty($row['role'])) {
					$role->delete();
				} else {
					$role->item_name = $row['role'];
					$role->save();
				}
			}
		}

		$users = $model->find()->all();
		
		return $this->render('showusers', [
			'users' => $users,
			'roles' => $roles,
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
