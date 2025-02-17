<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::connection(config('activitylog.database_connection'))->unprepared("CREATE TABLE `" . config('activitylog.table_name') . "` (
            `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '流水號',
            `log_name` VARCHAR(191) NULL DEFAULT NULL COMMENT '日誌類型' COLLATE 'utf8mb4_unicode_ci',
            `description` TEXT NOT NULL COMMENT '日誌描述' COLLATE 'utf8mb4_unicode_ci',
            `subject_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL COMMENT '異動對象',
            `subject_type` VARCHAR(191) NULL DEFAULT NULL COMMENT '異動對象類型' COLLATE 'utf8mb4_unicode_ci',
            `causer_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL COMMENT '操作對象',
            `causer_type` VARCHAR(191) NULL DEFAULT NULL COMMENT '操作對象類型' COLLATE 'utf8mb4_unicode_ci',
            `properties` JSON NULL DEFAULT NULL COMMENT '屬性內容',
            `updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT '更新時間',
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '新增時間',
            `month` TINYINT(2) UNSIGNED NOT NULL COMMENT '分表月份',
            PRIMARY KEY (`id`, `month`),
            INDEX `" . config('activitylog.table_name') . "_log_name_index` (`log_name`),
            INDEX `subject` (`subject_id`, `subject_type`),
            INDEX `causer` (`causer_id`, `causer_type`),
            INDEX `" . config('activitylog.table_name') . "_created_at_index` (`created_at`)
            )
            COMMENT='數據活動日誌表'
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
     */
    public function down()
    {
        Schema::connection(config('activitylog.database_connection'))->dropIfExists(config('activitylog.table_name'));
    }
}
