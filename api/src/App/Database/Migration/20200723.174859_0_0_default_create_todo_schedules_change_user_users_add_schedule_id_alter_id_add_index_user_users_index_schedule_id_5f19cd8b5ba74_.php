<?php

namespace Migration;

use Spiral\Migrations\Migration;

class OrmDefaultE6c6de23b6f4072e1e375fe883103bf9 extends Migration
{
    protected const DATABASE = 'default';

    public function up()
    {
        $this->table('user_users')
            ->alterColumn('id', 'string', [
                'nullable' => false,
                'default'  => 0
            ])
        ;

        $this->table('todo_schedules')
            ->addColumn('id', 'string', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('user', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 32
            ])
            ->addColumn('date', 'datetime', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('tasks_count', 'integer', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('value', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 16
            ])
            ->setPrimaryKeys(["id"])
            ->create();
    }

    public function down()
    {
        $this->table('user_users')
            ->alterColumn('id', 'string', [
                'nullable' => false,
                'default'  => 'nextval(\'user_users_id_seq',
                'size'     => 255
            ])
            ->update();
        
        $this->table('todo_schedules')->drop();
    }
}
