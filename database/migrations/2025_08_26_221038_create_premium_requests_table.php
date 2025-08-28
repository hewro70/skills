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
       // database/migrations/xxxx_xx_xx_create_premium_requests_table.php
Schema::create('premium_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('provider');         // click, paypal, wise...
    $table->string('txid');             // رقم التحويل
    $table->string('email');            // ايميل المستخدم
    $table->text('note')->nullable();   // ملاحظات
    $table->string('reference');        // مرجع داخلي
    $table->enum('status',['pending','approved','rejected'])->default('pending');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('premium_requests');
    }
};
