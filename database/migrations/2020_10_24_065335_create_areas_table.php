<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = 'areas';

        Schema::create($tableName, function (Blueprint $table) {
            $table->integerIncrements('areaid')
                ->nullable(false)->unsigned()
                ->comment('地区id');

            $table->integer('parentid')
                ->nullable(false)->unsigned()
                ->comment('父级id');

            $table->integer('userid')
                ->nullable(false)->unsigned()
                ->comment('用户id');

            $table->tinyInteger('sort')
                ->nullable(false)->unsigned()
                ->comment('排序字段');

            $table->string('name', 64)
                ->nullable(false)
                ->comment('地区名称');

            $table->tinyInteger('status')
                ->nullable(false)->unsigned()
                ->comment('状态，0 初始值，1 正常，2 软删除');

            $table->timestamps();

            $table->unique(['userid', 'name'], 'UNIQUE_USERID_NAME');
            $table->index(['userid'], 'INDEX_USERID');
            $table->index(['sort'], 'INDEX_SORT');
        });

        DB::statement("ALTER TABLE `${tableName}` comment '地区表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('areas');
    }
}
