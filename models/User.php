<?php

namespace app\models;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\base\Security;
use yii\web\IdentityInterface;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
	public $passwordHashCost = 5;
	public $_randomFile = null;
	
	public function shouldUseLibreSSL()
	{
		return false;
	}
		
	public function isWindows()
	{
		return false;
	}
		
	public static function tableName()
    {
        return 'users';
    }

	public function rules()
    {
        return [
            [['username', 'password_hash'], 'required'],
            [['username', 'password_hash'], 'string', 'max' => 100]            
        ];
    }


    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

	public static function findByPasswordResetToken($token)
    {
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token
        ]);
    }
	
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password, $user)
    {
		$hash = $user->password_hash;
        return Security::validatePassword($password, $hash);
    }
	
	public function setPassword($password)
    {
        $this->password_hash = Security::generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Security::generateRandomKey();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Security::generateRandomKey() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
	
	public function getAssigment() 
	{
		return $this->hasOne(Assigment::className(), ['user_id' => 'id']);
	}
}
