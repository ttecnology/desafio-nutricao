<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->text('code')->nullable();
            $table->text('status')->nullable();
            $table->timestamp('imported_t')->nullable();
            $table->text('url')->nullable();
            $table->text('creator')->nullable();
            $table->timestamps();
            $table->timestamp('last_modified_t')->nullable();
            $table->text('product_name')->nullable();
            $table->text('quantity')->nullable();
            $table->text('brands')->nullable();
            $table->text('categories', 600)->nullable();
            $table->text('labels')->nullable();
            $table->text('cities')->nullable();
            $table->text('purchase_places')->nullable();
            $table->text('stores')->nullable();
            $table->text('ingredients_text')->nullable();
            $table->text('traces')->nullable();
            $table->text('serving_size')->nullable();
            $table->decimal('serving_quantity', 8, 2)->nullable();
            $table->integer('nutriscore_score')->nullable();
            $table->text('nutriscore_grade')->nullable();
            $table->text('main_category')->nullable();
            $table->text('image_url')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
