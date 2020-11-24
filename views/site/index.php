<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'My Yii Application';
?>
<div class="site-index">

<?php
	foreach ($messages as $key => $message) {
		echo (
			'<div class="chat">' .
				'<p>' . Html::encode($message['message']) . '</p>' .
				'<hr />' .
			'</div>'
		);
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

        echo $form->field($model, 'message')->textarea(['rows' => 2, 'cols' => 5])->label('Сообщение');
		echo (
			'<div class="form-group">' .
				'<div class="col-lg-11">' .
					Html::submitButton('Отправить сообщение', ['class' => 'btn btn-primary', 'name' => 'login-button']) .
				'</div>' .
			'</div>'
		);

		ActiveForm::end();
	}
?> 

</div>
