<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monthly_reports', function (Blueprint $table) {
            $table->text('reject_comment')->nullable()->after('status'); // 差し戻しコメント
        });
    }

    public function down()
    {
        Schema::table('monthly_reports', function (Blueprint $table) {
            $table->dropColumn('reject_comment');
        });
    }

};
