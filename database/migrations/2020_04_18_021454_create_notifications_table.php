<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(" CREATE TABLE `notifications` (
            `id` CHAR(36) NOT NULL COLLATE 'utf8mb4_unicode_ci',
            `type` VARCHAR(191) NOT NULL COLLATE 'utf8mb4_unicode_ci',
            `notifiable_type` VARCHAR(191) NOT NULL COLLATE 'utf8mb4_unicode_ci',
            `notifiable_id` BIGINT(20) UNSIGNED NOT NULL,
            `data` JSON NOT NULL,
            `read_at` TIMESTAMP NULL DEFAULT NULL,
            `created_at` TIMESTAMP NULL DEFAULT NULL,
            `updated_at` TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (`notifiable_type`, `notifiable_id`, `id`)
            )
            COMMENT='通知訊息表'
            COLLATE='utf8mb4_unicode_ci'
            ENGINE=InnoDB
            PARTITION BY KEY (`notifiable_id`) 
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
        Schema::dropIfExists('notifications');
    }
}
