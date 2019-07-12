<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Создание';
$this->params['breadcrumbs'][] = ['label' => 'Аккаунты Amo CRM', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->login;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
