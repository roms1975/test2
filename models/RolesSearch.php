<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Roles;

class RolesSearch extends Roles
{
	public $roleName;

    public function rules()
    {
        return [
            [['id', 'role', 'created_at', 'updated_at'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'status', 'roleName'], 'safe'],
        ];
    }

    public function rolesList()
    {	
		$roles = array();
		$array = Roles::findAll(['type' => 1]);
		foreach ($array as $role) {
			$roles[$role['name']] = $role['name'];
		}
		
		return $roles;
    }
}
