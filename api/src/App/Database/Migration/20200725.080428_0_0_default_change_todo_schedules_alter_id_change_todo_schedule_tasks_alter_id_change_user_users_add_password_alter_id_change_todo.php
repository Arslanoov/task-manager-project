<?php

namespace Migration;

use Spiral\Migrations\Migration;

class OrmDefault288cc6db118f821244a08ae8008233c0 extends Migration
{
    protected const DATABASE = 'default';

    public function up()
    {
        $this->table('user_users')
            ->addColumn('password', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 128
            ])
            ->update();
    }

    public function down()
    {
        $this->table('user_users')
            ->dropColumn('password')
            ->update();
    }
}
