<?php

namespace Migration;

use Spiral\Migrations\Migration;

class OrmDefault1b70760c017e5ec2602b780d3b38fcdc extends Migration
{
    protected const DATABASE = 'default';

    public function up()
    {
        $this->table('todo_schedule_tasks')
            ->addColumn('id', 'string', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('schedule', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 255
            ])
            ->addColumn('name', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 32
            ])
            ->addColumn('description', 'text', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('important_level', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 16
            ])
            ->addIndex(["schedule"], [
                'name'   => 'todo_schedule_tasks_index_schedule_5f1ad4f6ad2ac',
                'unique' => false
            ])
            ->addForeignKey(["schedule"], 'todo_schedules', ["id"], [
                'name'   => 'todo_schedule_tasks_foreign_schedule_5f1ad4f6ad2ec',
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->setPrimaryKeys(["id"])
            ->create();
    }

    public function down()
    {
        $this->table('todo_schedule_tasks')->drop();
    }
}
