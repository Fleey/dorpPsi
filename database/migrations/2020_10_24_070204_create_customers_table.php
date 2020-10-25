<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'customers';

        Schema::create('customers', function (Blueprint $table) {
            $table->integerIncrements('customerid')
                ->nullable(false)->unsigned()
                ->comment('客户id');

            $table->integer('userid')
                ->nullable(false)->unsigned()
                ->comment('用户id');

            $table->string('name', 32)
                ->nullable(false)
                ->comment('客户名称');

            $table->string('phone', 16)
                ->nullable(false)
                ->comment('客户电话');

            $table->integer('areaid')
                ->nullable(false)->unsigned()
                ->comment('地区id');

            $table->string('address', 500)
                ->nullable(false)
                ->comment('客户地址');

            $table->tinyInteger('status')
                ->nullable(false)->unsigned()
                ->comment('状态 0 初始化，1 正常，2 软删除');

            $table->timestamps();

            $table->index(['areaid'], 'INDEX_AREAID');
            $table->index(['name'], 'INDEX_NAME');
            $table->index(['phone'], 'INDEX_PHONE');
            $table->index(['userid'], 'INDEX_USERID');

        });

        DB::statement("ALTER TABLE `${tableName}` comment '客户表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
