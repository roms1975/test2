<?php

use yii\helpers\Html;
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
		
		foreach ($users as $user) {
			echo (
				'<div class="row">' .
					'<span class="col-md-3">' . 
						$form->field($user, 'username')
							->textInput(['name' => 'User[' . $user['id'] . '][name]'])
							->label('Пользователь') .
					'</span>' .
					'<span class="col-md-3">' . 
						$form->field($user, 'role')
							->textInput(['name' => 'User[' . $user['id'] . '][role]'])
							->label('Роль') .
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
