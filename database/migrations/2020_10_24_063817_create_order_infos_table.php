<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOrderInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'order_info';

        Schema::create($tableName, function (Blueprint $table) {
            $table->integerIncrements('id')
                ->nullable(false)->unsigned()
                ->comment('主键');

            $table->integer('orderid')
                ->nullable(false)->unsigned()
                ->comment('订单id');

            $table->integer('productid')
                ->nullable(false)->unsigned()
                ->comment('商品id');

            $table->integer('total_num')
                ->nullable(false)->unsigned()
                ->comment('购买商品数量');

            $table->integer('discount_price')
                ->nullable(false)->unsigned()
                ->comment('折扣价格 单位分');

            $table->timestamps();

            $table->index(['productid'],'INDEX_PRODUCTID');
        });

        DB::statement("ALTER TABLE `${tableName}` comment '订单详细表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_info');
    }
}
