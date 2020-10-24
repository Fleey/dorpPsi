<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'orders';

        Schema::create($tableName, function (Blueprint $table) {
            $table->integerIncrements('orderid')
                ->nullable(false)->unsigned()
                ->comment('订单id');

            $table->integer('userid')
                ->nullable(false)->unsigned()
                ->comment('用户id');

            $table->integer('customerid')
                ->nullable(false)->unsigned()
                ->comment('客户id');

            $table->tinyInteger('status')
                ->nullable(false)->unsigned()
                ->comment('订单状态：0 初始化，1 已经付款 ，2 尚未付款');

            $table->integer('total_amount')
                ->nullable(false)->unsigned()
                ->comment('总共订单金额（可自定义） 单位分');

            $table->string('remark', 500)
                ->default('')
                ->comment('备注信息');

            $table->index(['userid'],'INDEX_USERID','btree');
            $table->index(['customerid'],'INDEX_CUSTOMERID','btree');
            $table->index(['status'],'INDEX_STATUS','btree');

            $table->timestamps();
        });

        DB::statement("ALTER TABLE `${tableName}` comment '订单列表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
