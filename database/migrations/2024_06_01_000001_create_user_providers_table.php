<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_apples', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('name', 200)->nullable();
            $table->string('email', 200)->nullable();
            $table->string('sub', 200)->comment('user unique id');
            $table->string('at_hash', 200)->nullable()->comment('access_token hash');
            $table->text('token')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('user_kakaos', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('name', 200)->nullable();
            $table->string('email', 200)->nullable();
            $table->string('nickname', 200)->nullable();
            $table->string('avatar', 200)->nullable()->comment('profile image path');
            $table->string('gender', 20)->nullable();
            $table->string('birthday', 20)->nullable();
            $table->string('calendar', 20)->nullable();
            $table->string('age_range', 20)->nullable();
            $table->string('sub', 200)->comment('user unique id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_apples');
        Schema::dropIfExists('user_kakaos');
    }
};
