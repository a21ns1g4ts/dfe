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
        Schema::create('dfes', function (Blueprint $table) {
            $table->id();
            $table->integer('tp_amb');
            $table->string('ver_aplic');
            $table->integer('c_stat');
            $table->string('x_motivo');
            $table->timestamp('dh_resp');
            $table->string('ult_nsu');
            $table->string('max_nsu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dfes');
    }
};
