<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('phpsecuritychecker_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_request_id');
            $table->foreign('test_request_id')->references('id')->on('test_requests');
            $table->unsignedBigInteger('result_status_id');
            $table->foreign('result_status_id')->references('id')->on('result_status');
            $table->string('path_result');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phpstan_results');
    }
};
