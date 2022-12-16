<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('reply_id');
            $table->unique(['user_id', 'reply_id']);
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references("id")
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('reply_id')
                ->references("id")
                ->on('replies')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
}
