<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateSystemAuthoritySnapshotsTable.
 */
class CreateSystemAuthoritySnapshotsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('authority_snapshots', function(Blueprint $table) {
			$table->char('id', 32)->primary()->comment('快照 ID');
            $table->string('name')->unique()->comment('快照名稱');
            $table->json('authority')->nullable()->comment('API 訪問權快照配置內容');
            $table->timestamp('updated_at')->nullable()->comment('更新時間');
			$table->timestamp('created_at')->useCurrent()->comment('新增時間');

			$table->index('created_at');
		});

		\DB::statement("ALTER TABLE `authority_snapshots` COMMENT '權限快照配置表'");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('authority_snapshots');
	}
}
