<?php

namespace Migration;

use Spiral\Migrations\Migration;

class OrmDefault34d6baf426c5124deb501a7d7fac0f3e extends Migration
{
    protected const DATABASE = 'default';

    public function up()
    {
        $this->table('todo_schedules')
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
            ->alterColumn('id', 'string', [
                'nullable' => false,
                'default'  => null
            ])
            ->dropColumn('value')
            ->update();
        
        $this->table('user_users')
            ->alterColumn('id', 'string', [
                'nullable' => false,
                'default'  => 0
            ])
            ->update();
    }

    public function down()
    {
        $this->table('user_users')
            ->dropForeignKey(["schedule_id"])
            ->dropIndex(["schedule_id"])
            ->alterColumn('id', 'string', [
                'nullable' => false,
                'default'  => 'nextval(\'user_users_id_seq',
                'size'     => 255
            ])
            ->dropColumn('schedule_id')
            ->update();
        
        $this->table('todo_schedules')
            ->alterColumn('id', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 255
            ])
            ->dropColumn('important_level')
            ->dropColumn('type')
            ->update();
    }
}
