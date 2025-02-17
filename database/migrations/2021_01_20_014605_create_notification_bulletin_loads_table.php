<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateNotificationBulletinLoadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE TABLE `notification_bulletin_loads` (
            `id` BIGINT(20) UNSIGNED NOT NULL COMMENT '持有人 ID',
            `type` VARCHAR(191) NOT NULL COMMENT '持有人 Model' COLLATE 'utf8mb4_unicode_ci',
            `record` JSON NULL DEFAULT NULL COMMENT '記錄',
            PRIMARY KEY (`id`, `type`)
            )
            COMMENT='通知公告的用戶載入記錄表'
            COLLATE='utf8mb4_unicode_ci'
            ENGINE=InnoDB
            PARTITION BY KEY (`id`) 
			PARTITIONS 71
			;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_bulletin_loads');
    }
}
