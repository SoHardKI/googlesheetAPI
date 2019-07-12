<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $login
 * @property string $hash
 * @property string $subdomen
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'amo_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'hash', 'subdomen','table_id'], 'required'],
            [['login', 'hash', 'subdomen','table_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Логин',
            'hash' => 'Hash',
            'subdomen' => 'Поддомен',
            'table_id' => 'ID таблицы'
        ];
    }
}
