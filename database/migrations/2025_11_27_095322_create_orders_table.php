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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // ข้อมูลลูกค้า
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('address')->nullable();
            
            // ข้อมูลการเงิน & ขนส่ง
            $table->string('cod_account')->nullable(); // ธนาคาร
            $table->decimal('cod_amount', 10, 2)->default(0); // ยอดเก็บปลายทาง
            $table->decimal('grand_total', 10, 2)->default(0); // ยอดรวมทั้งบิล
            
            // ข้อมูลอื่นๆ
            $table->string('ref_1')->nullable();
            $table->string('ref_2')->nullable();
            $table->text('remark')->nullable();
            $table->string('status')->default('pending'); // สถานะ: pending, shipping, completed
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};