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
    Schema::table('orders', function (Blueprint $table) {
        // เช็คและเพิ่มคอลัมน์ทีละตัว
        if (!Schema::hasColumn('orders', 'ref_code')) {
            $table->string('ref_code')->nullable()->after('id')->index();
        }
        if (!Schema::hasColumn('orders', 'sender_name')) {
            $table->string('sender_name')->nullable()->after('ref_code');
        }
        if (!Schema::hasColumn('orders', 'sender_phone')) {
            $table->string('sender_phone')->nullable()->after('sender_name');
        }
        if (!Schema::hasColumn('orders', 'receiver_name')) {
            $table->string('receiver_name')->nullable()->after('sender_phone');
        }
        if (!Schema::hasColumn('orders', 'receiver_phone')) {
            $table->string('receiver_phone')->nullable()->after('receiver_name');
        }
        if (!Schema::hasColumn('orders', 'receiver_address')) {
            $table->text('receiver_address')->nullable()->after('receiver_phone');
        }
        // เพิ่มคอลัมน์ยอดเงิน
        if (!Schema::hasColumn('orders', 'cod_amount')) {
            $table->decimal('cod_amount', 10, 2)->default(0);
        }
        if (!Schema::hasColumn('orders', 'grand_total')) {
            $table->decimal('grand_total', 10, 2)->default(0);
        }
        // เพิ่มสถานะ (ถ้ายังไม่มี)
        if (!Schema::hasColumn('orders', 'status')) {
            $table->string('status')->default('pending'); 
        }
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn([
            'ref_code', 
            'sender_name', 'sender_phone', 
            'receiver_name', 'receiver_phone', 'receiver_address',
            'cod_amount', 'grand_total', 'status'
        ]);
    });
}
};
