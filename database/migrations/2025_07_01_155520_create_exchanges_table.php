<?php
// database/migrations/2025_08_23_000000_create_exchanges_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('exchanges', function (Blueprint $t) {
            $t->id();
            $t->foreignId('conversation_id')->nullable()->constrained()->cascadeOnDelete();
            $t->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $t->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();

            $t->foreignId('sender_skill_id')->constrained('skills')->cascadeOnDelete();
            $t->foreignId('receiver_skill_id')->constrained('skills')->cascadeOnDelete();

            $t->enum('status', ['pending','accepted','rejected','cancelled'])->default('pending');
            $t->text('message_for_receiver')->nullable();

            $t->timestamps();

            $t->index(['conversation_id','status']);
        });
    }
    public function down(): void { Schema::dropIfExists('exchanges'); }
};

