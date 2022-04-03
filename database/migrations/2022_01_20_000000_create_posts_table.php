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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->enum('type', ['notice', 'faq', 'free'])->comment('게시글 종류');

            $table->string('name')->comment('제목');
            $table->text('content')->comment('내용');
            # $table->unsignedInteger('hit')->comment('조회수');
            $table->boolean('is_open')->default(true)->comment('공개 여부');

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
        Schema::dropIfExists('posts');
    }
};
