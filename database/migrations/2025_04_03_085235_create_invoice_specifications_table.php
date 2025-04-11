<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoice_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->text('description');
            $table->integer('timespent'); // in minutes
            $table->decimal('price_per_hour', 10, 2);
            $table->enum('type', ['regular', 'overtime', 'holiday'])->default('regular');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_specifications');
    }
}; 