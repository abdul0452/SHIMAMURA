<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom baru langsung di products & employees
        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->nullable()->after('image');
            $table->integer('weight')->nullable()->after('description');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->string('employee_number')->nullable()->after('position');
            $table->date('date_of_joining')->nullable()->after('employee_number');
        });

        // 2. Pindahkan data lama dari product_details -> products
        if (Schema::hasTable('product_details')) {
            DB::table('product_details')->orderBy('id')->chunk(100, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('products')->where('id', $row->product_id)->update([
                        'description' => $row->description,
                        'weight' => $row->weight,
                    ]);
                }
            });
        }

        // 3. Pindahkan data lama dari employee_details -> employees
        if (Schema::hasTable('employee_details')) {
            DB::table('employee_details')->orderBy('id')->chunk(100, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('employees')->where('id', $row->employee_id)->update([
                        'employee_number' => $row->employee_number,
                        'date_of_joining' => $row->date_of_joining,
                    ]);
                }
            });
        }

        // 4. Hapus tabel lama, sudah tidak dipakai
        Schema::dropIfExists('product_details');
        Schema::dropIfExists('employee_details');
    }

    public function down(): void
    {
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained()->onDelete('cascade');
            $table->text('description');
            $table->integer('weight');
            $table->timestamps();
        });

        Schema::create('employee_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->unique()->constrained()->onDelete('cascade');
            $table->string('employee_number');
            $table->date('date_of_joining');
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['description', 'weight']);
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['employee_number', 'date_of_joining']);
        });
    }
};