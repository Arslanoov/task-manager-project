<?php

namespace Migration;

use Spiral\Migrations\Migration;

class OrmDefault94f12343306d549015155fb02ca323b8 extends Migration
{
    protected const DATABASE = 'default';

    public function up()
    {
        $this
            ->table('user_users')
            ->addColumn('id', 'string', [
                'nullable' => false,
                'default'  => null
            ])
            ->addColumn('login', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 32
            ])
            ->addColumn('email', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 32
            ])
            ->addColumn('status', 'string', [
                'nullable' => false,
                'default'  => null,
                'size'     => 16
            ])
            ->addIndex(["login", "email"], [
                'name'   => 'user_users_index_login_email_5f1aab47aee64',
                'unique' => false
            ])
            ->setPrimaryKeys(["id"])
            ->create();
    }

    public function down()
    {
        $this
            ->table('user_users')
            ->dropIndex(["login", "email"])
            ->drop();
    }
}
