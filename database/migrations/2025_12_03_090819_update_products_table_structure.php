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
    Schema::table('products', function (Blueprint $table) {
        // 1. ลบ Column เก่าที่ไม่ใช้
        $table->dropColumn(['image', 'cost', 'stock']);
        
        // 2. เพิ่ม Column ใหม่
        $table->string('parcel_type')->default('tree')->after('sku');
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->string('image')->nullable();
        $table->decimal('cost', 10, 2)->default(0);
        $table->integer('stock')->default(0);
        $table->dropColumn('parcel_type');
    });
}
};
