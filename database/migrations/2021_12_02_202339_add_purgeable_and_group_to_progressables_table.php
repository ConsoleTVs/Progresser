<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPurgeableAndGroupToProgressablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('progressables', function (Blueprint $table) {
            $table->boolean('purgeable')->default(false);
            $table->boolean('purge_when_completed')->default(false);
            $table->boolean('purge_when_failed')->default(false);
            $table->string('group')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('progressables', function (Blueprint $table) {
            $table->dropColumn([
                'purgeable',
                'purge_when_completed',
                'purge_when_failed',
                'group',
            ]);
        });
    }
}
