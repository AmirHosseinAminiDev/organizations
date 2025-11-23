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
        Schema::table('deduction_files', function (Blueprint $table) {

            if (!Schema::hasColumn('deduction_files', 'organization_id')) {
                $table->unsignedBigInteger('organization_id')->after('id')->nullable();

                $table->foreign('organization_id')
                    ->references('id')->on('organizations')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deduction_files', function (Blueprint $table) {

            if (Schema::hasColumn('deduction_files', 'organization_id')) {
                $table->dropForeign(['organization_id']);
                $table->dropColumn('organization_id');
            }

        });
    }
};
