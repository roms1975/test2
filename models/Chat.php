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
}
