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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->date('issue_date');
            $table->date('due_date');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->decimal('total_no_tax', 10, 2);
            $table->decimal('total_with_discount', 10, 2);
            $table->string('currency')->default('USD');
            $table->string('language')->default('en');
            $table->text('notes')->nullable();
            $table->enum('status', ['unpaid', 'sent', 'paid', 'cancelled'])->default('draft');

            // Issuer (Company) details
            $table->string('issuer_name');
            $table->string('issuer_address');
            $table->string('issuer_city');
            $table->string('issuer_postal_code');
            $table->string('issuer_country');
            $table->string('issuer_phone');
            $table->string('issuer_email');
            $table->string('issuer_id_number');
            $table->string('issuer_tax_number');
            $table->string('issuer_authorized_person');
            $table->string('issuer_logo')->nullable();

            // Client details
            $table->string('client_name');
            $table->string('client_address');
            $table->string('client_city');
            $table->string('client_postal_code');
            $table->string('client_country');
            $table->string('client_phone');
            $table->string('client_email');
            $table->string('client_id_number');
            $table->string('client_tax_number');
            $table->string('client_type');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
