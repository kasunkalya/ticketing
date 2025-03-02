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
       Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->string('customer_name');
            $table->string('email');
            $table->string('phone');
            $table->text('problem_description');
            $table->enum('status', ['New', 'Opened', 'Closed'])->default('New');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
