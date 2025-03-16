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
        Schema::create('fpg_orders', function (Blueprint $table) {
            $table->id('fgp_ordersID');
            $table->unsignedBigInteger('user_ID');
            $table->unsignedBigInteger('fgp_item_id');
            $table->decimal('unit_cost', 10, 2);
            $table->integer('unit_number');
            $table->dateTime('date_transaction');
            $table->json('ACH_data')->nullable();
            $table->enum('status', ['Pending', 'Delivered']);
            $table->timestamps();
        
            // Foreign Keys
            $table->foreign('user_ID')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('fgp_item_id')->references('fgp_item_id')->on('fpg_items')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fpg_orders');
    }
};
