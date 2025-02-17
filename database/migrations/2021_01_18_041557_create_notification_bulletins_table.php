<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateNotificationBulletinsTable.
 */
class CreateNotificationBulletinsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notification_bulletins', function(Blueprint $table) {
			$table->uuid('id')->comment('流水 ID');
			$table->string('subject')->comment('訊息標題');
			$table->json('content')->comment('訊息內容');
			$table->string('label')->default('normal')->comment('標籤類型');
			$table->string('notifiable_type')->comment('通知對象類型');
			$table->timestamp('released_at')->nullable()->comment('預計發佈時間');
			$table->timestamp('expired_at')->nullable()->comment('預計下檔時間');
			$table->tinyInteger('status')->unsigned()->default(0)->comment('狀態0:停權1:授權');
            $table->timestamp('updated_at')->nullable()->comment('更新時間');
			$table->timestamp('created_at')->useCurrent()->comment('新增時間');
			
			$table->primary(['id', 'notifiable_type']);
			$table->index('notifiable_type');
			$table->index('released_at');
			$table->index('expired_at');
			$table->index('created_at');
		});

		\DB::statement("ALTER TABLE `notification_bulletins` COMMENT '通知公告表'");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('notification_bulletins');
	}
}
