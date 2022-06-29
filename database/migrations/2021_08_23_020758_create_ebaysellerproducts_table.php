<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEbaysellerproductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebaysellerproducts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->string('itemid', 30)->nullable();
            $table->string('uuid', 255)->nullable();
            $table->double('startprice')->nullable();
            $table->string('currency', 10)->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('quantitysold')->nullable();
            $table->string('autopay', 10)->nullable();
            $table->string('country', 10)->nullable();
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
        Schema::dropIfExists('ebaysellerproducts');
    }
}
