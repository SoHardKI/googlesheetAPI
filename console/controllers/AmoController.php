<?php

namespace console\controllers;

use Exception;
use frontend\models\Amo;
use Yii;
use common\classes\Debug;
use yii\console\Controller;

class AmoController extends Controller
{
    public static function getTable($login, $hash, $subdomen)
    {
        $amo = new \AmoCRM\Client($subdomen, $login, $hash);
        $pervichniy_contract = '';
        $soglasovanie_dogovora = '';
        $uspeshno_realizovano = '';
        $fl = 0;
        $models = [];
        $account = $amo->account;
        $users = $account->apiCurrent();
        $voronki = $amo->pipelines->apiList();
        $deals = $amo->lead->apiList([]);
        foreach ($voronki as $value)
        {
            foreach ($value['statuses'] as $item)
            {
                if($item['name'] == 'Первичный контакт')
                {
                    $pervichniy_contract = $item['id'];
                }
                if($item['name'] == 'Согласование договора')
                {
                    $soglasovanie_dogovora = $item['id'];
                }
                if($item['name'] == 'Успешно реализовано')
                {
                    $uspeshno_realizovano = $item['id'];
                }
            }
        }
        foreach ($deals as $item)
        {
            $deal = new Amo();
            $deal->primary_contact = 0;
            $deal->harmonization_of_contract = 0;
            $deal->successfully_implemented = 0;
            $deal->date = date("d-m-Y",$item['date_create']);
            foreach ($users['users'] as $user)
            {
                if ($item['responsible_user_id'] == $user['id'])
                {
                    $deal->manager = $user['name'];
                }
            }
            if($item['status_id'] == $pervichniy_contract)
            {
                $deal->primary_contact++;
            }
            if($item['status_id'] == $soglasovanie_dogovora)
            {
                $deal->harmonization_of_contract++;
            }
            if($item['status_id'] == $uspeshno_realizovano)
            {
                $deal->successfully_implemented++;
            }
            $deal->price = $item['price'];
            if($models)
            {
                foreach ($models as $model)
                {
                    if($model->date == $deal->date && $model->manager == $deal->manager)
                    {
                        $model->primary_contact += $deal->primary_contact;
                        $model->harmonization_of_contract += $deal->harmonization_of_contract;
                        $model->successfully_implemented += $deal->successfully_implemented;
                        $model->price += $deal->price;
                        $fl = 1;
                        break;
                    }
                }
            }
            if ($fl == 0)
            {
                array_push($models, $deal);
            }
            $fl = 0;
        }

        return json_encode($models,true);
    }
}