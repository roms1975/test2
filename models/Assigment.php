<?php

namespace app\models;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;

class Assigment extends ActiveRecord
{	
	public function rules()
    {
        return [
            [['item_name'], 'safe'],
        ];
    }

	public static function tableName()
    {
        return 'auth_assignment';
    }
	
	public function saveRoles($post) 
	{
		foreach ($post as $id => $row) {
			$role = $this->findOne(['user_id' => $id]);
			
			if (!$role) {
				$this->item_name = $row['role'];
				$this->user_id = $id;
				$this->created_at = strtotime('now');
				$this->save();
			} else if (empty($row['role'])) {
				$role->delete();
			} else {
				$role->item_name = $row['role'];
				$role->save();
			}
		}
		
		return true;
	}
	
	public function attributeLabels()
    {
        return [
            'item_name' => 'Роль',
        ];
    } 
	
	public function createNewAssigment($user_id)
	{
		$this->item_name = 'author';
		$this->user_id = $user_id;
		if ($this->save())
			return true;
		
		return false;
	}
}
