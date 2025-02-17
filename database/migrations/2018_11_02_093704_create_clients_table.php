<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateClientsTable.
 */
class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('客戶端 ID');
            $table->string('name')->unique()->comment('客戶端服務名稱');
            $table->char('client_id', 32)->unique()->comment('客戶端帳戶');
            $table->string('client_secret')->comment('客戶端密碼');
            $table->char('key', 32)->comment('密鑰');
            $table->tinyInteger('ban')->unsigned()->default(1)->comment('客戶端禁令型號');
            $table->tinyInteger('status')->unsigned()->default(1)->comment('客戶端狀態0:停權1:授權');
            $table->timestamp('expired_at')->nullable()->comment('到期時間');
            $table->timestamp('updated_at')->nullable()->comment('更新時間');
            $table->timestamp('created_at')->useCurrent()->comment('新增時間');
            
            $table->index('created_at');
        });

        \DB::statement("ALTER TABLE `clients` COMMENT '客戶端應用授權表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
