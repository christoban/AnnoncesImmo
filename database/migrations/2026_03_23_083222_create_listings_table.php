<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['vente', 'location']);
            $table->enum('property_type', ['appartement', 'maison', 'terrain', 'bureau', 'commerce']);
            $table->decimal('price', 15, 2);
            $table->string('location');
            $table->string('city');
            $table->integer('rooms')->nullable();
            $table->decimal('surface', 8, 2)->nullable(); // en m²
            $table->boolean('is_active')->default(true);
            $table->boolean('is_flagged')->default(false); // pour admin
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
