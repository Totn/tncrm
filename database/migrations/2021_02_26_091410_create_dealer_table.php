<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 多经销商
        Schema::create('dealers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->default('');
            $table->string('short_name', 20)->default('');
            $table->string('address', 250)->default('');
            $table->string('manager', 20)->default('');
            $table->string('phone', 20)->default('');
            $table->string('email', 50)->default('');
            $table->softDeletes();
            $table->timestamps();
        });
        // 经销商登陆账号
        Schema::create('dealer_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('dealer_id')->default(0);
            $table->string('username', 120)->unique();
            $table->string('password', 80);
            $table->string('name')->default('');
            $table->string('avatar')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });

        // 经销商角色表，经销商不需要编辑角色，所有经销商共用角色
        Schema::create('dealer_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->string('slug', 50)->unique();
            $table->timestamps();
        });

        // 经销商权限表，所有经销商共用权限
        Schema::create('dealer_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->string('slug', 50)->unique();
            $table->string('http_method')->nullable();
            $table->text('http_path')->nullable();
            $table->integer('order')->default(0);
            $table->bigInteger('parent_id')->default(0);
            $table->timestamps();
        });

        // 经销商菜单表，所有经销商共用菜单
        Schema::create('dealer_menu', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->string('title', 50);
            $table->string('icon', 50)->nullable();
            $table->string('uri', 50)->nullable();
            $table->tinyInteger('show')->default(1);
            $table->string('extension', 50)->default('');
            $table->timestamps();
        });

        // 经销商用户-角色关联表
        Schema::create('dealer_role_users', function (Blueprint $table) {
            $table->bigInteger('role_id');
            $table->bigInteger('user_id');
            $table->unique(['role_id', 'user_id']);
            $table->timestamps();
        });

        // 经销商角色 - 权限绑定表
        Schema::create('dealer_role_permissions', function (Blueprint $table) {
            $table->bigInteger('role_id');
            $table->bigInteger('permission_id');
            $table->unique(['role_id', 'permission_id']);
            $table->timestamps();
        });

        // 经销商角色 - 菜单绑定表
        Schema::create('dealer_role_menu', function (Blueprint $table) {
            $table->bigInteger('role_id');
            $table->bigInteger('menu_id');
            $table->unique(['role_id', 'menu_id']);
            $table->timestamps();
        });

        // 经销商权限 - 菜单绑定表
        Schema::create('dealer_permission_menu', function (Blueprint $table) {
            $table->bigInteger('permission_id');
            $table->bigInteger('menu_id');
            $table->unique(['permission_id', 'menu_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dealers');
        Schema::dropIfExists('dealer_users');
        Schema::dropIfExists('dealer_roles');
        Schema::dropIfExists('dealer_permissions');
        Schema::dropIfExists('dealer_menu');
        Schema::dropIfExists('dealer_role_users');
        Schema::dropIfExists('dealer_role_permissions');
        Schema::dropIfExists('dealer_role_menu');
        Schema::dropIfExists('dealer_permission_menu');
    }
}
