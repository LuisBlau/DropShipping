<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDownloadeditemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('downloadeditems', function (Blueprint $table) {
            $table->id();
            $table->string('stock_code', 50)->unique();
            $table->string('itemid', 50)->nullable();
            $table->string('full_name')->nullable();
            $table->double('price')->nullable();
            $table->string('collection')->nullable();
            $table->string('high_res_image_url')->nullable();
            $table->string('brand', 50)->nullable();
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
        Schema::dropIfExists('downloadeditems');
    }
}
