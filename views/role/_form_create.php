<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    <? //= $form->field($model, 'password_hash')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
	<?= Html::label('Пароль', 'pass') ?>
    <?= Html::passwordInput(
		'password', null, 
		[
			'id' => 'pass',
			'class' => 'form-control',
		]
	) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
