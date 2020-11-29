<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'password_hash')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'disabled' => true]) ?>
    <?= $form->field($model->roles, 'item_name')->dropDownList($roles) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
