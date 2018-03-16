<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('slug')->unique(); // admin, editor etc.
            $table->string('label');
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('role_id');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); // if user delete ->  cascade down (delete this row as well)

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade'); // if permission delete ->  cascade down (delete this row as well)

            $table->index(['user_id', 'role_id']);
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('slug')->unique()->index(); // edit-post, add-user ect.
            $table->string('label');
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade'); // if permission delete ->  cascade down (delete this row as well)

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade'); // if permission delete ->  cascade down (delete this row as well)

            $table->index(['permission_id', 'role_id']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
        Schema::enableForeignKeyConstraints();
    }
}