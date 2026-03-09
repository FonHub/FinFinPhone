<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
   {
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        
        // เชื่อมโยงกับตาราง orders
        // ถ้าลบ Order หลัก รายการสินค้าจะหายไปด้วย (onDelete cascade)
        $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
        
        // รายละเอียดสินค้า
        $table->string('product_name');
        $table->integer('quantity')->default(1);
        $table->decimal('unit_price', 10, 2)->default(0);
        $table->decimal('total_price', 10, 2)->default(0); // (จำนวน x ราคา)
        
        $table->timestamps();
    });
  }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
