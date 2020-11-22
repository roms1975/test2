<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->title = 'Пользователи';
?>

<div class="site-index">
<?php
	if (count($users) > 0) {
		$form = ActiveForm::begin([
			'id' => 'user-form',
			'action' => [''],
		]); 
		
		$roles_arr = ArrayHelper::map($roles, 'name', 'name');
		$params = array(
			'prompt' => 'Не выбрано',
			'class' => 'form-control',
		);
		
		echo (
			'<div class="row">' .
				'<h4>' .
					'<span class="col-md-3">Пользователь</span>' .
					'<span class="col-md-3">Роль</span>' .
				'</h3>' .
			'</div>'
		);
		
		foreach ($users as $user) {
			if (!empty($user->assigment['item_name'])) {
				$key = $user->assigment['item_name'];
				$params['options'] = array(
					$key => ['selected' => true]
				);
			} else {
				$params['options'] = array();
			}
				
			echo (
				'<div class="row">' .
					'<span class="col-md-3">' . 
						$form->field($user, 'username')
							->textInput([
								'name' => 'User[' . $user['id'] . '][name]', 
								'disabled' => 'disabled'
							])
							->label(false) .
					'</span>' .
					/*
					'<span class="col-md-3">' . 
						$form->field($user, 'role')
							->textInput(['name' => 'User[' . $user['id'] . '][role]'])
							->label('Роль') .
					'</span>' .
					
					'<span class="col-md-3">' .
						'<input class="form-control" type=text name=User[' . $user['id'] . '][role] value="' . $role . '" >' .
					'</span>' .
					*/
					'<span class="col-md-3">' .
						Html::dropDownList('User[' . $user['id'] . '][role]', 'null', $roles_arr, $params) .
					'</span>' .
				'</div>'
				
			);
		}
		
		echo (
			'<div class="form-group">' .
				'<div class="col-lg-11">' .
					Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) .
				'</div>' .
			'</div>'
		);

		ActiveForm::end();
	} else {
		echo '<p class="col-sm-12">Пользователей не найдено</p>';
	}
?> 
</div>
