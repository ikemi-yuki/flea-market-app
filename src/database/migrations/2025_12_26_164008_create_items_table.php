<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('image_path');
            $table->tinyInteger('condition')->comment('1:良好, 2:目立った傷や汚れなし, 3:やや傷や汚れあり, 4:状態が悪い');
            $table->string('brand')->nullable();
            $table->text('description');
            $table->integer('price');
            $table->tinyInteger('status')->comment('1:出品中, 2:購入処理中, 3:購入済み(Sold)');
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
        Schema::dropIfExists('items');
    }
}
