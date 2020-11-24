<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Некорректные сообщения';
?>

<div class="site-index">
<?php
	if (count($messages) > 0) {
		$form = ActiveForm::begin([
			'id' => 'uncorrect-form',
			'action' => [''],
		]); 
		
		foreach ($messages as $key => $message) {
			echo (
				'<span>' . 
					Html::encode($message['message']) . 
					$form->field($message, 'correct')
						->checkbox(['label' => ' Отметить как корректное', 'name' => 'Chat[correct][' . $message['id'] . ']']) .
				'</span>' .
				'<hr />'
			);
		}
		
		echo (
			'<div class="form-group">' .
				'<div class="col-lg-11">' .
					Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'login-button']) .
				'</div>' .
			'</div>'
		);

		ActiveForm::end();
	} else {
		echo '<p class="col-sm-12">Некорректных сообщений нет</p>';
	}
?> 
</div>
