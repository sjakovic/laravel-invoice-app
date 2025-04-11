<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Get the dummy user
        $user = User::where('email', 'sjakovic+invoices-user@gmail.com')->first();

        // Create company for the dummy user
        $company = Company::create([
            'user_id' => $user->id,
            'name' => 'Demo Company Ltd',
            'address' => '123 Business Street',
            'city' => 'New York',
            'postal_code' => '10001',
            'country' => 'United States',
            'phone' => '+1 555-123-4567',
            'email' => 'info@democompany.com',
            'id_number' => 'US123456789',
            'tax_number' => 'TAX123456789',
            'authorized_person' => 'John Doe',
        ]);

        // Create individual clients
        $individualClients = [
            [
                'name' => 'John Smith',
                'address' => '456 Personal Ave',
                'city' => 'Los Angeles',
                'postal_code' => '90001',
                'country' => 'United States',
                'phone' => '+1 555-987-6543',
                'email' => 'john.smith@example.com',
                'id_number' => 'ID123456',
                'type' => 'individual',
            ],
            [
                'name' => 'Sarah Johnson',
                'address' => '789 Home Street',
                'city' => 'Chicago',
                'postal_code' => '60601',
                'country' => 'United States',
                'phone' => '+1 555-456-7890',
                'email' => 'sarah.j@example.com',
                'id_number' => 'ID789012',
                'type' => 'individual',
            ],
        ];

        // Create business clients
        $businessClients = [
            [
                'name' => 'Tech Solutions Inc',
                'address' => '321 Tech Park',
                'city' => 'San Francisco',
                'postal_code' => '94101',
                'country' => 'United States',
                'phone' => '+1 555-321-6547',
                'email' => 'contact@techsolutions.com',
                'id_number' => 'BUS123456',
                'tax_number' => 'TAX987654321',
                'type' => 'business',
            ],
            [
                'name' => 'Global Services Ltd',
                'address' => '654 Corporate Blvd',
                'city' => 'Boston',
                'postal_code' => '02101',
                'country' => 'United States',
                'phone' => '+1 555-789-1234',
                'email' => 'info@globalservices.com',
                'id_number' => 'BUS789012',
                'tax_number' => 'TAX123987456',
                'type' => 'business',
            ],
            [
                'name' => 'Innovative Solutions LLC',
                'address' => '987 Innovation Drive',
                'city' => 'Seattle',
                'postal_code' => '98101',
                'country' => 'United States',
                'phone' => '+1 555-456-1237',
                'email' => 'contact@innovativesolutions.com',
                'id_number' => 'BUS456789',
                'tax_number' => 'TAX456123789',
                'type' => 'business',
            ],
        ];

        // Create all clients
        $clients = [];
        foreach ($individualClients as $clientData) {
            $clients[] = Client::create(array_merge($clientData, ['user_id' => $user->id]));
        }
        foreach ($businessClients as $clientData) {
            $clients[] = Client::create(array_merge($clientData, ['user_id' => $user->id]));
        }

        // Create invoices for business clients (17 invoices)
        $businessClients = array_slice($clients, 2); // Get only business clients
        for ($i = 0; $i < 17; $i++) {
            $client = $businessClients[array_rand($businessClients)];
            $issueDate = Carbon::now()->subDays(rand(0, 90));
            $dueDate = $issueDate->copy()->addDays(30);
            
            Invoice::create([
                'user_id' => $user->id,
                'company_id' => $company->id,
                'client_id' => $client->id,
                'invoice_number' => 'INV-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'issue_date' => $issueDate,
                'due_date' => $dueDate,
                'subtotal' => $subtotal = rand(1000, 10000) / 100,
                'tax' => $taxRate = rand(5, 20) / 100,
                'tax_amount' => $taxAmount = $subtotal * $taxRate,
                'discount' => $discountRate = rand(0, 10) / 100,
                'discount_amount' => $discountAmount = $subtotal * $discountRate,
                'total' => $subtotal + $taxAmount - $discountAmount,
                'total_no_tax' => $subtotal - $discountAmount,
                'total_with_discount' => $subtotal - $discountAmount,
                'currency' => 'USD',
                'language' => 'en',
                'notes' => 'Sample invoice for ' . $client->name,
                'status' => ['unpaid', 'sent', 'paid', 'cancelled'][array_rand(['unpaid', 'sent', 'paid', 'cancelled'])],
                // Issuer (Company) details
                'issuer_name' => $company->name,
                'issuer_address' => $company->address,
                'issuer_city' => $company->city,
                'issuer_postal_code' => $company->postal_code,
                'issuer_country' => $company->country,
                'issuer_phone' => $company->phone,
                'issuer_email' => $company->email,
                'issuer_id_number' => $company->id_number,
                'issuer_tax_number' => $company->tax_number,
                'issuer_authorized_person' => $company->authorized_person,
                'issuer_logo' => $company->logo,
                // Client details
                'client_name' => $client->name,
                'client_address' => $client->address,
                'client_city' => $client->city,
                'client_postal_code' => $client->postal_code,
                'client_country' => $client->country,
                'client_phone' => $client->phone,
                'client_email' => $client->email,
                'client_id_number' => $client->id_number,
                'client_tax_number' => $client->type === 'business' ? $client->tax_number : 'N/A',
                'client_type' => $client->type,
            ]);

            // Create invoice items for each invoice
            $invoice = Invoice::latest()->first();
            $itemsCount = rand(1, 5);
            $totalSubtotal = 0;

            for ($j = 0; $j < $itemsCount; $j++) {
                $quantity = rand(1, 10);
                $unitPrice = rand(10, 100) / 10;
                $total = $quantity * $unitPrice;
                $totalSubtotal += $total;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => 'Item ' . ($j + 1),
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total' => $total,
                    'total_no_tax' => $total,
                    'tax' => $invoice->tax,
                    'tax_amount' => $total * $invoice->tax,
                    'discount' => $invoice->discount,
                    'discount_amount' => $total * $invoice->discount,
                ]);
            }

            // Update invoice totals based on items
            $invoice->update([
                'subtotal' => $totalSubtotal,
                'tax_amount' => $totalSubtotal * $invoice->tax,
                'discount_amount' => $totalSubtotal * $invoice->discount,
                'total' => $totalSubtotal + ($totalSubtotal * $invoice->tax) - ($totalSubtotal * $invoice->discount),
                'total_no_tax' => $totalSubtotal - ($totalSubtotal * $invoice->discount),
                'total_with_discount' => $totalSubtotal - ($totalSubtotal * $invoice->discount),
            ]);
        }

        // Create invoices for individual clients (4 invoices)
        $individualClients = array_slice($clients, 0, 2); // Get only individual clients
        for ($i = 17; $i < 21; $i++) {
            $client = $individualClients[array_rand($individualClients)];
            $issueDate = Carbon::now()->subDays(rand(0, 90));
            $dueDate = $issueDate->copy()->addDays(30);
            
            Invoice::create([
                'user_id' => $user->id,
                'company_id' => $company->id,
                'client_id' => $client->id,
                'invoice_number' => 'INV-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'issue_date' => $issueDate,
                'due_date' => $dueDate,
                'subtotal' => $subtotal = rand(100, 1000) / 100,
                'tax' => $taxRate = rand(5, 20) / 100,
                'tax_amount' => $taxAmount = $subtotal * $taxRate,
                'discount' => $discountRate = rand(0, 10) / 100,
                'discount_amount' => $discountAmount = $subtotal * $discountRate,
                'total' => $subtotal + $taxAmount - $discountAmount,
                'total_no_tax' => $subtotal - $discountAmount,
                'total_with_discount' => $subtotal - $discountAmount,
                'currency' => 'USD',
                'language' => 'en',
                'notes' => 'Sample invoice for ' . $client->name,
                'status' => ['unpaid', 'sent', 'paid', 'cancelled'][array_rand(['unpaid', 'sent', 'paid', 'cancelled'])],
                // Issuer (Company) details
                'issuer_name' => $company->name,
                'issuer_address' => $company->address,
                'issuer_city' => $company->city,
                'issuer_postal_code' => $company->postal_code,
                'issuer_country' => $company->country,
                'issuer_phone' => $company->phone,
                'issuer_email' => $company->email,
                'issuer_id_number' => $company->id_number,
                'issuer_tax_number' => $company->tax_number,
                'issuer_authorized_person' => $company->authorized_person,
                'issuer_logo' => $company->logo,
                // Client details
                'client_name' => $client->name,
                'client_address' => $client->address,
                'client_city' => $client->city,
                'client_postal_code' => $client->postal_code,
                'client_country' => $client->country,
                'client_phone' => $client->phone,
                'client_email' => $client->email,
                'client_id_number' => $client->id_number,
                'client_tax_number' => $client->type === 'business' ? $client->tax_number : 'N/A',
                'client_type' => $client->type,
            ]);

            // Create invoice items for each invoice
            $invoice = Invoice::latest()->first();
            $itemsCount = rand(1, 5);
            $totalSubtotal = 0;

            for ($j = 0; $j < $itemsCount; $j++) {
                $quantity = rand(1, 10);
                $unitPrice = rand(10, 100) / 10;
                $total = $quantity * $unitPrice;
                $totalSubtotal += $total;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => 'Item ' . ($j + 1),
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total' => $total,
                    'total_no_tax' => $total,
                    'tax' => $invoice->tax,
                    'tax_amount' => $total * $invoice->tax,
                    'discount' => $invoice->discount,
                    'discount_amount' => $total * $invoice->discount,
                ]);
            }

            // Update invoice totals based on items
            $invoice->update([
                'subtotal' => $totalSubtotal,
                'tax_amount' => $totalSubtotal * $invoice->tax,
                'discount_amount' => $totalSubtotal * $invoice->discount,
                'total' => $totalSubtotal + ($totalSubtotal * $invoice->tax) - ($totalSubtotal * $invoice->discount),
                'total_no_tax' => $totalSubtotal - ($totalSubtotal * $invoice->discount),
                'total_with_discount' => $totalSubtotal - ($totalSubtotal * $invoice->discount),
            ]);
        }
    }
} 