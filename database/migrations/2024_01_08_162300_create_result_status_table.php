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
        Schema::create('result_status', function (Blueprint $table) {
            $table->id();
            $table->enum('status', $this->visibilityOptions());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result_status');
    }

    private function visibilityOptions(): array
    {
        $options = [];
        foreach (config('result-status.status') as $option){
            $options[] = $option;
        }
        return $options;
    }

};
