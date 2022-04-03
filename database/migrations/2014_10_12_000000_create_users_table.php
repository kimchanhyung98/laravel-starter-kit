<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('아이디');
            $table->string('email', 100)->comment('이메일');
            //$table->timestamp('email_verified_at')->nullable()->comment('이메일 인증 시간');
            $table->string('password')->nullable()->comment('비밀번호');

            $table->string('username', 20)->comment('이름');
            $table->string('phone', 15)->unique()->nullable()->comment('전화번호');

            $table->string('provider')->nullable()->comment('소셜 로그인');
            $table->string('provider_id')->nullable()->comment('소셜 로그인 ID');
            $table->string('refresh_token')->nullable();

            $table->boolean('is_admin')->default(false)->comment('관리자 여부');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
};
