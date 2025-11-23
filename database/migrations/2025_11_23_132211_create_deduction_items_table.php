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
        Schema::create('deduction_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('deduction_file_id');
            $table->string('national_code');
            $table->string('personnel_code')->nullable();
            $table->bigInteger('amount');
            $table->timestamps();

            $table->foreign('deduction_file_id')
                ->references('id')->on('deduction_files')
                ->onDelete('cascade');

            $table->index('national_code');
            $table->index('personnel_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deduction_items');
    }
};
