<?php

namespace Migration;

use Spiral\Migrations\Migration;

class OrmDefaultD87070ccb2498e390cae0d64190db481 extends Migration
{
    protected const DATABASE = 'default';

    public function up()
    {
        $this->table('user_users')
            ->addColumn('email', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 32
            ])
            ->alterColumn('id', 'string', [
                'nullable' => false,
                'default'  => \Spiral\Database\Injection\Fragment::__set_state(array(
               'fragment' => 'nextval(\'user_users_id_seq\'::regclass)',
            ))
            ])
            ->addIndex(["login", "email"], [
                'name'   => 'user_users_index_login_email_5f194d3a82d5c',
                'unique' => false
            ])
            ->update();
    }

    public function down()
    {
        $this->table('user_users')
            ->dropIndex(["login", "email"])
            ->alterColumn('id', 'primary', [
                'nullable' => false,
                'default'  => \Spiral\Database\Injection\Fragment::__set_state(array(
               'fragment' => 'nextval(\'user_users_id_seq\'::regclass)',
            ))
            ])
            ->dropColumn('email')
            ->update();
    }
}
