<?php

use yii\db\Migration;

/**
 * Class m190715_115255_add_gmail_column
 */
class m190715_115255_add_gmail_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('amo_user', 'gmail', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('amo_user', 'gmail');
    }
}
