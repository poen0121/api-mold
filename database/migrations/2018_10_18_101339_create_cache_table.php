<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateCacheTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE TABLE `cache` (
    	    `key` VARCHAR(191) NOT NULL COMMENT '鍵' COLLATE 'utf8mb4_unicode_ci',
    	    `value` MEDIUMTEXT NOT NULL COMMENT '值' COLLATE 'utf8mb4_unicode_ci',
			`expiration` INT(11) NOT NULL COMMENT '過期時間' ,
    	    PRIMARY KEY (`key`)
    	    )
            COMMENT='緩存表'
    	    COLLATE='utf8mb4_unicode_ci'
    	    ENGINE=InnoDB
            PARTITION BY KEY (`key`) 
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
        Schema::dropIfExists('cache');
    }
}
