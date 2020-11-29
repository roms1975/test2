<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'username',
			['attribute' => 'roles.item_name', 'label' => 'Роль'],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
