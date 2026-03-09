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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // ชื่อสินค้า
        $table->string('sku')->nullable()->index(); // รหัสสินค้า (Stock Keeping Unit)
        $table->decimal('price', 10, 2)->default(0); // ราคาขาย
        $table->decimal('cost', 10, 2)->default(0); // ต้นทุน (เอาไว้ดูขอบเขตกำไร)
        $table->integer('stock')->default(0); // จำนวนคงเหลือ
        $table->string('image')->nullable(); // รูปภาพ
        $table->boolean('is_active')->default(true); // สถานะ เปิด/ปิด การขาย
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
