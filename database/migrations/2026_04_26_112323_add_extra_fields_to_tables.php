<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('categories', 'image_url')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('image_url')->nullable();
            });
        }

        if (!Schema::hasColumn('transactions', 'reference_number')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('reference_number')->nullable()->unique();
            });
        }

        if (!Schema::hasColumn('deposits', 'reference_number')) {
            Schema::table('deposits', function (Blueprint $table) {
                $table->string('reference_number')->nullable()->unique();
            });
        }
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('reference_number');
        });
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropColumn('reference_number');
        });
    }
};
