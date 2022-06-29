<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopifyUploadedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_uploaded_products', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('title', 255);
            $table->string('product_type', 255)->nullable();
            $table->string('tags', 255)->nullable();
            $table->string('published_scope', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('status', 255)->nullable();
            $table->string('vendor', 255)->nullable();
            $table->string('currency', 10)->nullable();
            $table->double('price')->default(0.0);
            $table->string('barcode', 255)->nullable();
            $table->string('sku', 255)->nullable();
            $table->unsignedInteger('quantity')->default(0);
            $table->timestamp('created_at', $precision = 0)->nullable();
            $table->timestamp('updated_at', $precision = 0)->nullable();
            $table->timestamp('published_at', $precision = 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shopify_uploaded_products');
    }
}
