<?php

namespace app\models;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;

class Roles extends ActiveRecord
{	
	public static function tableName()
    {
        return 'auth_item';
    }

}
