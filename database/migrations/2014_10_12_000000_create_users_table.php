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
        Schema::create('users', static function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('nickname', 50)->unique();
            $table->string('email', 200)->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 100)->nullable();
            $table->string('phone', 20)->nullable();

            $table->string('provider', 20)->nullable();
            $table->string('provider_id', 100)->nullable();
            $table->string('provider_token', 100)->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->string('deleted_reason', 200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
