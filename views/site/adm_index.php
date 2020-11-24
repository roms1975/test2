<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'My Yii Application';
?>
<div class="site-index">

<?php
	foreach ($messages as $key => $message) {
		$form = ActiveForm::begin([
			'id' => 'login-form-' . $message['id'],
			'layout' => 'horizontal',
			'action' => ['site/offmessage'],
			'fieldConfig' => [
				'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
				'labelOptions' => ['class' => 'col-lg-1 control-label'],
			],
		]);
		echo (
			'<div class="chat">' .
				'<form method="post">' .
					'<p>' . 
						Html::encode($message['message']) . 
						'<input name="off-message" value="' . $message['id'] . '" hidden>' . 
						($message['correct'] ? 
							Html::submitButton('Отметить как некорректное', ['class' => 'btn btn-primary uncorrect', 'name' => 'off-button']) :
						'<span class="warn">Некорректное сообщение</span>') .
						($admin_id == $message['user'] ? '<span class="adm-message">Сообщение от администратора<span>' : '').
					'</p>' .
				'</form>' .
				'<hr />' .
			'</div>'
		);
		ActiveForm::end();
	}
	
	if(!Yii::$app->user->isGuest) {
		$form = ActiveForm::begin([
			'id' => 'login-form',
			'layout' => 'horizontal',
			'action' => ['site/addmessage'],
			'fieldConfig' => [
				'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
				'labelOptions' => ['class' => 'col-lg-1 control-label'],
			],
		]); 

		echo '<div class="chat">';
        echo $form->field($model, 'message')->textarea(['rows' => 2, 'cols' => 5])->label('Сообщение');
		echo (
			'<div class="form-group">' .
				'<div class="col-lg-11">' .
					Html::submitButton('Отправить сообщение', ['class' => 'btn btn-primary', 'name' => 'login-button']) .
				'</div>' .
			'</div>'
		);
		echo '</div>';

		ActiveForm::end();
	
	}
?> 

</div>
