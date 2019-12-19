<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->bigIncrements('id');
            $table->string('realname')->default('')->comment('用户姓名');
            $table->char('mobile',11)->nullable()->unique()->comment('手机');
            $table->char('email',50)->default('')->unique()->comment('用户邮箱');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar')->default('')->comment('头像');
            $table->string('wechat_avatar')->default('')->comment('微信头像');
            $table->enum('sex',[1,2,0])->nullable()->comment('性别');
            $table->unsignedTinyInteger('age')->nullable()->comment('年龄');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
