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
        Schema::create('maxminrin', function (Blueprint $table) {
            $table->id();
            $table->string('marca');
            $table->decimal('rin', $precision = 8, $scale = 2);
            $table->string('articulo');
            $table->string('descripcion');
            $table->integer('stock')->default(0)->nullable();
            $table->integer('m1')->default(0)->nullable();
            $table->integer('m2')->default(0)->nullable();
            $table->integer('m3')->default(0)->nullable();
            $table->integer('m4')->default(0)->nullable();
            $table->integer('total')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maxminrin');
    }
};
