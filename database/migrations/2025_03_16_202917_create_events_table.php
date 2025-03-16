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
        Schema::create('events', function (Blueprint $table) {
            $table->id('events_ID');
            $table->unsignedBigInteger('franchisee_id');
            $table->string('title');
            $table->date('date');
            $table->time('time');
            $table->string('location');
            $table->json('inventory_allocated')->nullable(); // List of case flavors and quantities
            $table->timestamps();
        
            // Foreign Key
            $table->foreign('franchisee_id')->references('franchisee_id')->on('franchisees')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
