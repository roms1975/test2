<?php

namespace app\models;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;

class Chat extends ActiveRecord
{	
	public static function tableName()
    {
        return 'chat';
    }

	public function rules()
    {
        return [
            [['user', 'message'], 'required'],
            [['correct'], 'boolean']            
        ];
    }

    public static function getMessages()
    {
        return static::find()->where(['correct' => true])->all();
    }
	
    public static function getUncorrect()
    {
        return static::find()->where(['!=', 'correct',  true])->all();
    }
	
	public function saveNewMessage($user) 
	{
		$this->user = $user;
		$this->correct = 1;
		$this->save();
		
		return true;
	}
	
	public function offMesssage($id) 
	{
		$row = $this->find()->where('id = :id', [':id' => $id])->one();
		if (!empty($row)) {
			$row->correct = 0;
			$row->save();
		}
		
		return true;
	}
	
	public function onMessage($post)
	{
		//$rows = Yii::$app->request->post('Chat')['correct'];
		foreach ($post as $key => $data) {
			$row = $this->find()->where('id = :id', [':id' => $key])->one();
			if (!$row)
				continue;
			
			$row->correct = addslashes($data);
			$row->save();
		}
		
		return true;
	}
}
