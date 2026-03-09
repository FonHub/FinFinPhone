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
    Schema::create('shipment_batches', function (Blueprint $table) {
        $table->id();
        $table->string('status')->default('processing'); // processing, completed, closed
        $table->text('remark')->nullable();
        $table->timestamps();
    });
    
    // สำคัญ: ต้องเพิ่ม batch_id ในตาราง orders ด้วย (ถ้ายังไม่มี)
    if (!Schema::hasColumn('orders', 'batch_id')) {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('batch_id')->nullable()->after('status');
            $table->index('batch_id');
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_batches');
    }
};
