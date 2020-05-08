<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

class CreatePermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->enum('removable', [0,1]);
            $table->timestamps();
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedBigInteger('permission_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->primary(['permission_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedBigInteger('role_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['role_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary');
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
        });

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));

        // add roles
        DB::table('roles')->insert([
            ['name' => 'superadmin', 'guard_name' => 'web', 'removable' => '0', 'created_at' => new DateTime()],
            ['name' => 'admin', 'guard_name' => 'web', 'removable' => '1', 'created_at' => new DateTime()],
            ['name' => 'hrd', 'guard_name' => 'web', 'removable' => '1', 'created_at' => new DateTime()],
            ['name' => 'karyawan', 'guard_name' => 'web', 'removable' => '1', 'created_at' => new DateTime()],
        ]);
        
        // add permissions
        DB::table('permissions')->insert([
            // roles
            ['name' => 'menu roles', 'guard_name' => 'web', 'created_at' => new DateTime()],
            ['name' => 'read roles', 'guard_name' => 'web', 'created_at' => new DateTime()],
            ['name' => 'create roles', 'guard_name' => 'web', 'created_at' => new DateTime()],
            ['name' => 'edit roles', 'guard_name' => 'web', 'created_at' => new DateTime()],
            ['name' => 'delete roles', 'guard_name' => 'web', 'created_at' => new DateTime()],

            // users
            ['name' => 'menu users', 'guard_name' => 'web', 'created_at' => new DateTime()],
            ['name' => 'read users', 'guard_name' => 'web', 'created_at' => new DateTime()],
            ['name' => 'create users', 'guard_name' => 'web', 'created_at' => new DateTime()],
            ['name' => 'edit users', 'guard_name' => 'web', 'created_at' => new DateTime()],
            ['name' => 'delete users', 'guard_name' => 'web', 'created_at' => new DateTime()],

            // permissions
            ['name' => 'menu role permissions', 'guard_name' => 'web', 'created_at' => new DateTime()],
            ['name' => 'read role permissions', 'guard_name' => 'web', 'created_at' => new DateTime()],
            ['name' => 'update role permissions', 'guard_name' => 'web', 'created_at' => new DateTime()],
        ]);

        // add users
        DB::table('users')->insert([
            ['name' => 'superadmin', 'email' => 'superadmin@mail.com', 'email_verified_at' => new DateTime(), 'username' => 'superadmin', 'password' => Hash::make('superadmin'), 'avatar' => 'user1.jpg', 'created_at' => new DateTime()],
            ['name' => 'admin', 'email' => 'admin@mail.com', 'email_verified_at' => new DateTime(), 'username' => 'admin', 'password' => Hash::make('admin'), 'avatar' => 'user2.jpg', 'created_at' => new DateTime()],

            ['name' => 'andri', 'email' => 'andri@lambangjayagroup.com', 'email_verified_at' => new DateTime(), 'username' => 'andri', 'password' => Hash::make('andri'), 'avatar' => 'user5.jpg', 'created_at' => new DateTime()],
            ['name' => 'hasan', 'email' => 'hasan@lambangjayagroup.com', 'email_verified_at' => new DateTime(), 'username' => 'hasan', 'password' => Hash::make('hasan'), 'avatar' => 'user6.jpg', 'created_at' => new DateTime()],
            ['name' => 'rika', 'email' => 'rika@lambangjayagroup.com', 'email_verified_at' => new DateTime(), 'username' => 'rika', 'password' => Hash::make('rika'), 'avatar' => 'user7.jpg', 'created_at' => new DateTime()],
            ['name' => 'padli', 'email' => 'padli@lambangjayagroup.com', 'email_verified_at' => new DateTime(), 'username' => 'padli', 'password' => Hash::make('padli'), 'avatar' => 'user8.jpg', 'created_at' => new DateTime()],
            ['name' => 'iwan', 'email' => 'iwan@lambangjayagroup.com', 'email_verified_at' => new DateTime(), 'username' => 'iwan', 'password' => Hash::make('iwan'), 'avatar' => 'user9.jpg', 'created_at' => new DateTime()],
            ['name' => 'tigor', 'email' => 'tigor@lambangjayagroup.com', 'email_verified_at' => new DateTime(), 'username' => 'tigor', 'password' => Hash::make('tigor'), 'avatar' => 'user10.jpg', 'created_at' => new DateTime()],
            ['name' => 'farid', 'email' => 'farid@lambangjayagroup.com', 'email_verified_at' => new DateTime(), 'username' => 'farid', 'password' => Hash::make('farid'), 'avatar' => 'user11.jpg', 'created_at' => new DateTime()],
        ]);

        // assigned a roles to models/users
        DB::table('model_has_roles')->insert([
            ['role_id' => 1, 'model_type' => 'App\Models\User', 'model_id' => 1],
            ['role_id' => 2, 'model_type' => 'App\Models\User', 'model_id' => 2],

            ['role_id' => 4, 'model_type' => 'App\Models\User', 'model_id' => 3],
            ['role_id' => 4, 'model_type' => 'App\Models\User', 'model_id' => 4],
            ['role_id' => 3, 'model_type' => 'App\Models\User', 'model_id' => 5],
            ['role_id' => 4, 'model_type' => 'App\Models\User', 'model_id' => 6],
            ['role_id' => 4, 'model_type' => 'App\Models\User', 'model_id' => 7],
            ['role_id' => 3, 'model_type' => 'App\Models\User', 'model_id' => 8],
            ['role_id' => 4, 'model_type' => 'App\Models\User', 'model_id' => 9],
        ]);

        // assigned a permissions to roles
        DB::table('role_has_permissions')->insert([
            // superadmin
            ['permission_id' => 1, 'role_id' => 1],
            ['permission_id' => 2, 'role_id' => 1],
            ['permission_id' => 3, 'role_id' => 1],
            ['permission_id' => 4, 'role_id' => 1],
            ['permission_id' => 5, 'role_id' => 1],
            ['permission_id' => 6, 'role_id' => 1],
            ['permission_id' => 7, 'role_id' => 1],
            ['permission_id' => 8, 'role_id' => 1],
            ['permission_id' => 9, 'role_id' => 1],
            ['permission_id' => 10, 'role_id' => 1],
            ['permission_id' => 11, 'role_id' => 1],
            ['permission_id' => 12, 'role_id' => 1],
            ['permission_id' => 13, 'role_id' => 1],

            // admin
            ['permission_id' => 1, 'role_id' => 2],
            ['permission_id' => 2, 'role_id' => 2],
            ['permission_id' => 3, 'role_id' => 2],
            ['permission_id' => 4, 'role_id' => 2],
            ['permission_id' => 5, 'role_id' => 2],
            ['permission_id' => 6, 'role_id' => 2],
            ['permission_id' => 7, 'role_id' => 2],
            ['permission_id' => 8, 'role_id' => 2],
            ['permission_id' => 9, 'role_id' => 2],
            ['permission_id' => 10, 'role_id' => 2],
            ['permission_id' => 11, 'role_id' => 2],
            ['permission_id' => 12, 'role_id' => 2],
            ['permission_id' => 13, 'role_id' => 2],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
}
