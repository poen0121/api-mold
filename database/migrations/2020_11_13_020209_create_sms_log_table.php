<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateSmsLogTable.
 */
class CreateSmsLogTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/* System engineer need maintain PARTITION */
        DB::unprepared("CREATE TABLE `sms_log` (
			`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '流水號',
    	    `sourceable_type` VARCHAR(191) NOT NULL COMMENT '操作源 Model' COLLATE 'utf8mb4_unicode_ci',
    	    `sourceable_id` BIGINT(20) UNSIGNED NOT NULL COMMENT '操作源 ID',
			`via` VARCHAR(191) NOT NULL COMMENT '發送簡訊 Notification 類' COLLATE 'utf8mb4_unicode_ci',
			`notify_phone` MEDIUMTEXT NOT NULL COMMENT '發送號碼' COLLATE 'utf8mb4_unicode_ci',
			`notify_message` JSON NULL DEFAULT NULL COMMENT '發送訊息記錄',
			`notify_result` JSON NULL DEFAULT NULL COMMENT '發送結果記錄',
			`operate` ENUM('success','failure') NOT NULL COMMENT '操作狀態' COLLATE 'utf8mb4_unicode_ci',
			`updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT '更新時間',
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '新增時間',
			`month` TINYINT(2) UNSIGNED NOT NULL COMMENT '分表月份',
    	    PRIMARY KEY (`id`, `month`),
			INDEX `sms_log_sourceable_type_sourceable_id_index` (`sourceable_type`, `sourceable_id`),
            INDEX `sms_log_created_at_index` (`created_at`)
    	    )
            COMMENT='簡訊日誌表'
    	    COLLATE='utf8mb4_unicode_ci'
    	    ENGINE=InnoDB
            PARTITION BY LIST (`month`) (
                PARTITION p1 VALUES IN (1) ENGINE = InnoDB,
                PARTITION p2 VALUES IN (2) ENGINE = InnoDB,
                PARTITION p3 VALUES IN (3) ENGINE = InnoDB,
                PARTITION p4 VALUES IN (4) ENGINE = InnoDB,
                PARTITION p5 VALUES IN (5) ENGINE = InnoDB,
                PARTITION p6 VALUES IN (6) ENGINE = InnoDB,
                PARTITION p7 VALUES IN (7) ENGINE = InnoDB,
                PARTITION p8 VALUES IN (8) ENGINE = InnoDB,
                PARTITION p9 VALUES IN (9) ENGINE = InnoDB,
                PARTITION p10 VALUES IN (10) ENGINE = InnoDB,
                PARTITION p11 VALUES IN (11) ENGINE = InnoDB,
                PARTITION p12 VALUES IN (12) ENGINE = InnoDB
			 );");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('sms_log');
	}
}