<?php

use yii\db\Migration;

class m161026_154753_customers extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('customers', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->comment('Имя клиента'),
            'surname' => $this->string(255)->comment('Фамилия клиента'),
            'phone' => $this->string(255)->comment('Номер телефона'),
            'time_add' => $this->dateTime()->notNull()->comment('Время добавления'),
            'status' => "ENUM('new', 'registered', 'refused', 'unavailable') NOT NULL DEFAULT 'new'",
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('customers');
    }
}