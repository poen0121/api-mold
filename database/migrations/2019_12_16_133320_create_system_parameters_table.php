<?php

use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateSystemParametersTable.
 */
class CreateSystemParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_parameters', function (Blueprint $table) {
            $table->smallIncrements('id')->comment('流水號');
            $table->string('slug')->unique()->comment('參數');
            $table->string('value')->comment('參數值');
            $table->timestamp('updated_at')->nullable()->comment('更新時間');
            $table->timestamp('created_at')->useCurrent()->comment('新增時間');
        });

        \DB::statement("ALTER TABLE `system_parameters` COMMENT '系統參數配置表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_parameters');
    }
}
