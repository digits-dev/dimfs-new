<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('item_master_approvals', function (Blueprint $table) {
            $table->string('action')->nullable()->after('item_values');
            $table->integer('item_master_id')->nullable()->after('action');
            $table->integer('approved_by')->nullable()->after('status');
            $table->integer('rejected_by')->nullable()->after('approved_by');
            $table->timestamp('approved_at')->nullable()->after('updated_by');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
        });

        Schema::table('gashapon_item_master_approvals', function (Blueprint $table) {
            $table->string('action')->nullable()->after('item_values');
            $table->integer('gashapon_item_master_id')->nullable()->after('action');
            $table->integer('approved_by')->nullable()->after('status');
            $table->integer('rejected_by')->nullable()->after('approved_by');
            $table->timestamp('approved_at')->nullable()->after('updated_by');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_master_approvals', function (Blueprint $table) {
            $table->dropColumn('action');
            $table->dropColumn('item_master_id');
            $table->dropColumn('approved_by');
            $table->dropColumn('rejected_by');
            $table->dropColumn('approved_at');
            $table->dropColumn('rejected_at');
        });

        Schema::table('gashapon_item_master_approvals', function (Blueprint $table) {
            $table->dropColumn('action');
            $table->dropColumn('gashapon_item_master_id');
            $table->dropColumn('approved_by');
            $table->dropColumn('rejected_by');
            $table->dropColumn('approved_at');
            $table->dropColumn('rejected_at');
        });
    }
};
