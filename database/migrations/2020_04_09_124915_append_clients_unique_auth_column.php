<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AppendClientsUniqueAuthColumn.
 */
class AppendClientsUniqueAuthColumn extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('clients', function(Blueprint $table) {
            $table->string('unique_auth')->default('')->comment('唯一身份授權碼');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('unique_auth');
        });
	}
}
