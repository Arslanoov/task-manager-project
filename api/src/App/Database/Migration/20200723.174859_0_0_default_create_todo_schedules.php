<?php

namespace Migration;

use Spiral\Migrations\Migration;

class OrmDefaultE6c6de23b6f4072e1e375fe883103bf9 extends Migration
{
    protected const DATABASE = 'default';

    public function up()
    {
        $this->table('todo_schedules')
            ->addColumn('id', 'string', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('user', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 255
            ])
            ->addColumn('date', 'datetime', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('tasks_count', 'integer', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('important_level', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 16
            ])
            ->addColumn('type', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 16
            ])
            ->setPrimaryKeys(["id"])
            ->addIndex(["user"], [
                'name'   => 'todo_schedules_index_user_5f1aab96d4c94',
                'unique' => false
            ])
            ->addForeignKey(["user"], 'user_users', ["id"], [
                'name'   => 'todo_schedules_foreign_user_5f1aab96d4ced',
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->create();
    }

    public function down()
    {
        $this
            ->table('todo_schedules')
            ->dropForeignKey(['user'])
            ->dropIndex(['user'])
            ->drop();
    }
}
