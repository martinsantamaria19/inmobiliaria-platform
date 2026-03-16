<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inmobiliaria_id')->constrained()->onDelete('cascade');
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('tipo'); // casa, apartamento, local, terreno, oficina
            $table->string('operacion'); // venta, alquiler, ambas
            $table->decimal('precio_venta', 12, 2)->nullable();
            $table->decimal('precio_alquiler', 10, 2)->nullable();
            $table->string('moneda')->default('USD'); // UYU, USD
            $table->string('estado')->default('disponible'); // disponible, reservado, vendido, alquilado
            $table->integer('dormitorios')->default(0);
            $table->integer('banos')->default(1);
            $table->decimal('superficie_total', 10, 2)->nullable();
            $table->decimal('superficie_construida', 10, 2)->nullable();
            $table->boolean('garage')->default(false);
            $table->boolean('piscina')->default(false);
            $table->string('direccion')->nullable();
            $table->string('barrio')->nullable();
            $table->string('ciudad')->default('Montevideo');
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->boolean('destacado')->default(false);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
