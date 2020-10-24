<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $productsName = 'products';

        Schema::create($productsName, function (Blueprint $table) {
            $table->increments('productid')
                ->nullable(false)->unsigned()
                ->comment('商品id');
            $table->integer('userid')
                ->nullable(false)->unsigned()
                ->comment('userid用户id');
            $table->string('name', 128)
                ->nullable(false)->unique()
                ->comment('商品名称');
            $table->string('unit', 8)
                ->nullable(false)
                ->comment('计量单位（个）');
            $table->integer('price')
                ->nullable(false)->unsigned()
                ->comment('单个商品价格 （分）');
            $table->tinyInteger('status')
                ->nullable(false)->unsigned()
                ->comment('商品状态');

            $table->timestamps();

            $table->index(['userid'], 'INDEX_USERID', 'BTREE');
            $table->index(['status'], 'INDEX_STATUS', 'BTREE');
            $table->unique(['name'], 'UNIQUE_NAME', 'BTREE');
        });

        DB::statement("ALTER TABLE ${productsName} comment '商品列表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
