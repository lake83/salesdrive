<?php

use yii\db\Migration;

/**
 * Class m221116_152507_candidates
 */
class m221116_152507_candidates extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('candidates', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'birth_date' => $this->integer()->notNull(),
            'experience' => $this->integer()->notNull(),
            'frameworks' => $this->string()->notNull(),
            'cv' => $this->string()->defaultValue(null),
            'comment' => $this->text(),
            'is_active' => $this->boolean()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull()
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('candidates');
    }
}
