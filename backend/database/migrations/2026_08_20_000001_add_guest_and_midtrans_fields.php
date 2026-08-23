<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // --- orders: user_id dibuat opsional karena checkout tanpa login ---
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();

            $table->string('guest_name')->nullable()->after('user_id');
            $table->string('guest_email')->nullable()->after('guest_name');
            $table->string('guest_phone')->nullable()->after('guest_email');

            $table->integer('total_amount')->default(0)->after('status');
        });

        // --- payments: field-field yang dibutuhkan untuk integrasi Midtrans ---
        Schema::table('payments', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('amount');
            $table->string('transaction_id')->nullable()->after('status');
            $table->string('snap_token')->nullable()->after('transaction_id');
            $table->json('payload')->nullable()->after('snap_token');
            $table->timestamp('paid_at')->nullable()->after('payload');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['guest_name', 'guest_email', 'guest_phone', 'total_amount']);
            $table->foreignId('user_id')->nullable(false)->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['status', 'transaction_id', 'snap_token', 'payload', 'paid_at']);
        });
    }
};
