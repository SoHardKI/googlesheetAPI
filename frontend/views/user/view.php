<?php

use yii\bootstrap\Alert;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->login;
$this->params['breadcrumbs'][] = ['label' => 'Аккаунты Amo Crm', 'url' => ['/']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Список', ['/'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Добавить данные в таблицу', ['table', 'id' => $model->id, 'view' => 'view'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'login',
            'hash',
            'subdomen',
            'gmail',
        ],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $provider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Дата',
                'value' =>  'date',
            ],
            [
                'label' => 'Менеджеры',
                'value' =>  'manager',
            ],
            [
                'label' => 'Новых сделок в воронке',
                'value' =>  'primary_contact',
            ],
            [
                'label' => 'Согласование договора',
                'value' =>  'harmonization_of_contract',
            ],
            [
                'label' => 'Успешно реализовано',
                'value' =>  'successfully_implemented',
            ],
            [
                'label' => 'Сумма оплат',
                'value' =>  'price',
            ],
        ],
    ]); ?>
</div>
