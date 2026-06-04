<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->string('category'); // sedan, suv, truck, van, luxury, economy
            $table->enum('transmission', ['automatic', 'manual']);
            $table->enum('fuel_type', ['petrol', 'diesel', 'electric', 'hybrid']);
            $table->decimal('price_per_day', 10, 2);
            $table->text('description')->nullable();
            $table->boolean('is_available')->default(true);
            $table->integer('year')->nullable();
            $table->integer('seats')->default(5);
            $table->string('color')->nullable();
            $table->string('license_plate')->unique()->nullable();
            $table->string('image')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['is_available', 'category']);
            $table->index('brand');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
