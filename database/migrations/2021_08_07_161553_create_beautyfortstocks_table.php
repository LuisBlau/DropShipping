<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeautyfortstocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beautyfortnewstocks', function (Blueprint $table) {
            $table->id();
            $table->string('stock_code', 50)->unique();
            $table->string('full_name')->nullable();
            $table->integer('stock_level')->nullable();
            $table->double('rrp')->nullable();
            $table->double('price')->nullable();
            $table->double('last_purchased_price')->nullable();
            $table->string('barcode')->nullable();
            $table->string('collection')->nullable();
            $table->string('high_res_image_url')->nullable();
            $table->string('brand', 50)->nullable();
            $table->integer('quantity')->nullable();
            $table->string('type', 100)->nullable();
            $table->integer('size')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('category', 100)->nullable();
            $table->timestamps();
        });
        Schema::create('beautyfortremovedstocks', function (Blueprint $table) {
            $table->id();
            $table->string('stock_code', 50)->unique();
            $table->string('full_name')->nullable();
            $table->integer('stock_level')->nullable();
            $table->double('rrp')->nullable();
            $table->double('price')->nullable();
            $table->double('last_purchased_price')->nullable();
            $table->string('barcode')->nullable();
            $table->string('collection')->nullable();
            $table->string('high_res_image_url')->nullable();
            $table->string('brand', 50)->nullable();
            $table->integer('quantity')->nullable();
            $table->string('type', 100)->nullable();
            $table->integer('size')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('category', 100)->nullable();
            $table->timestamps();
        });
        Schema::create('beautyfortstocks_old', function (Blueprint $table) {
            $table->id();
            $table->string('stock_code', 50)->unique();
            $table->string('full_name')->nullable();
            $table->integer('stock_level')->nullable();
            $table->double('rrp')->nullable();
            $table->double('price')->nullable();
            $table->double('last_purchased_price')->nullable();
            $table->string('barcode')->nullable();
            $table->string('collection')->nullable();
            $table->string('high_res_image_url')->nullable();
            $table->string('brand', 50)->nullable();
            $table->integer('quantity')->nullable();
            $table->string('type', 100)->nullable();
            $table->integer('size')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('category', 100)->nullable();
            $table->timestamps();
        });
        Schema::create('beautyfortstocks_new', function (Blueprint $table) {
            $table->id();
            $table->string('stock_code', 50)->unique();
            $table->string('full_name')->nullable();
            $table->integer('stock_level')->nullable();
            $table->double('rrp')->nullable();
            $table->double('price')->nullable();
            $table->double('last_purchased_price')->nullable();
            $table->string('barcode')->nullable();
            $table->string('collection')->nullable();
            $table->string('high_res_image_url')->nullable();
            $table->string('brand', 50)->nullable();
            $table->integer('quantity')->nullable();
            $table->string('type', 100)->nullable();
            $table->integer('size')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('category', 100)->nullable();
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
        Schema::dropIfExists('beautyfortstocks_old');
        Schema::dropIfExists('beautyfortstocks_new');
    }
}
