<?php

namespace Migration;

use Spiral\Migrations\Migration;

class OrmDefault1cb21f49626f83b15531a07b3f7b5726 extends Migration
{
    protected const DATABASE = 'default';

    public function up()
    {
        $this->table('todo_schedule_tasks')
            ->addColumn('status', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 16
            ])
            ->update();
        
        $this->table('todo_schedule_task_steps')
            ->addColumn('id', 'primary', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('task', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 255
            ])
            ->addColumn('name', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 32
            ])
            ->addColumn('sort_order', 'integer', [
                'nullable' => true,
                'default'  => null
            ])
            ->addColumn('status', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 16
            ])
            ->addIndex(["task"], [
                'name'   => 'todo_schedule_task_steps_index_task_5f1b14770d7b9',
                'unique' => false
            ])
            ->addForeignKey(["task"], 'todo_schedule_tasks', ["id"], [
                'name'   => 'todo_schedule_task_steps_foreign_task_5f1b14770d7f6',
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->setPrimaryKeys(["id"])
            ->create();
    }

    public function down()
    {
        $this->table('todo_schedule_task_steps')->drop();

        $this->table('todo_schedule_tasks')
            ->dropColumn('status')
            ->update();
    }
}
