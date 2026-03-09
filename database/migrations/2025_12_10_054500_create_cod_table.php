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
    Schema::create('cods', function (Blueprint $table) {
        $table->id();
        
        // ผูกกับตาราง Orders (1 ออเดอร์ มี 1 รายการ COD)
        $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
        
        // ยอดเงินที่ต้องเก็บปลายทาง
        $table->decimal('amount', 10, 2);
        
        // สถานะของเงิน COD (เช่น: รอเก็บเงิน, เก็บเงินแล้ว, โอนคืนร้านค้าแล้ว)
        $table->enum('status', ['pending', 'collected', 'remitted', 'cancelled'])->default('pending');
        
        // วันที่ได้รับเงินจากลูกค้า
        $table->timestamp('collected_at')->nullable();
        
        // วันที่โอนเงินคืนร้านค้า
        $table->timestamp('remitted_at')->nullable();
        
        // หลักฐานการโอนเงิน (ถ้ามี)
        $table->string('proof_image')->nullable();

        $table->timestamps(); // created_at, updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cod');
    }
};
