<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m190712_075654_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%amo_user}}', [
            'id' => $this->primaryKey(),
            'login' => $this->string(255),
            'hash' => $this->string(255),
            'subdomen' => $this->string(255),
            'table_id' => $this->string(255),
        ]);
        $this->insert('amo_user',[
            'login' => 'apuc06@mail.ru',
            'hash' => '4889e913176a3691388b20940a3e42e25b2f683f',
            'subdomen' => 'apuc06',
            'table_id' => '1W4k9zrS-6NO2H9d-_Zegoo9-O6Y33948CKGDi2YmQm8',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
