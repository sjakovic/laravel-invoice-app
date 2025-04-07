<?php

namespace App\Livewire;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Company;
use App\Models\InvoiceItem;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceComponent extends Component
{
    use WithPagination;

    public $invoice_number;
    public $issue_date;
    public $due_date;
    public $client_id;
    public $company_id;
    public $items = [];
    public $invoice_id;
    public $isEditing = false;
    public $subtotal = 0;
    public $tax_rate = 20;
    public $tax_amount = 0;
    public $total = 0;

    protected function rules()
    {
        return [
            'invoice_number' => 'required|unique:invoices,invoice_number,' . $this->invoice_id,
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after:issue_date',
            'client_id' => 'required|exists:clients,id',
            'company_id' => 'required|exists:companies,id',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ];
    }

    public function mount()
    {
        $this->items = [
            ['description' => '', 'quantity' => 1, 'unit_price' => 0]
        ];
    }

    public function create()
    {
        $this->resetInputFields();
        $company = Company::where('user_id', auth()->id())->first();
        if ($company) {
            $this->company_id = $company->id;
        }
        $this->items = [
            ['description' => '', 'quantity' => 1, 'unit_price' => 0]
        ];
        $this->isEditing = true;
    }

    public function render()
    {
        return view('livewire.invoice', [
            'invoices' => Invoice::where('user_id', auth()->id())->paginate(10),
            'clients' => Client::where('user_id', auth()->id())->get(),
            'company' => Company::where('user_id', auth()->id())->first(),
        ])->layout('layouts.app');
    }

    public function addItem()
    {
        $this->items[] = ['description' => '', 'quantity' => 1, 'unit_price' => 0];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = 0;
        foreach ($this->items as $item) {
            $this->subtotal += $item['quantity'] * $item['unit_price'];
        }
        $this->tax_amount = $this->subtotal * ($this->tax_rate / 100);
        $this->total = $this->subtotal + $this->tax_amount;
    }

    public function store()
    {
        $this->validate();
        $this->calculateTotals();

        $company = Company::where('user_id', auth()->id())->first();
        if (!$company) {
            session()->flash('error', 'Please create a company profile first.');
            return;
        }

        $invoice = Invoice::create([
            'user_id' => auth()->id(),
            'invoice_number' => $this->invoice_number,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'client_id' => $this->client_id,
            'company_id' => $company->id,
            'subtotal' => $this->subtotal,
            'tax' => $this->tax_amount,
            'total' => $this->total,
        ]);

        foreach ($this->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        session()->flash('message', 'Invoice created successfully.');
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        $this->invoice_id = $id;
        $this->invoice_number = $invoice->invoice_number;
        $this->issue_date = $invoice->issue_date;
        $this->due_date = $invoice->due_date;
        $this->client_id = $invoice->client_id;
        $this->company_id = $invoice->company_id;
        $this->items = $invoice->items->map(function($item) {
            return [
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
            ];
        })->toArray();
        $this->isEditing = true;
        $this->calculateTotals();
    }

    public function update()
    {
        $this->validate();
        $this->calculateTotals();

        $invoice = Invoice::find($this->invoice_id);
        $invoice->update([
            'invoice_number' => $this->invoice_number,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'client_id' => $this->client_id,
            'company_id' => $this->company_id,
            'subtotal' => $this->subtotal,
            'tax' => $this->tax_amount,
            'total' => $this->total,
        ]);

        // Delete existing items
        $invoice->items()->delete();

        // Create new items
        foreach ($this->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        session()->flash('message', 'Invoice updated successfully.');
        $this->resetInputFields();
    }

    public function delete($id)
    {
        Invoice::find($id)->delete();
        session()->flash('message', 'Invoice deleted successfully.');
    }

    public function downloadPdf($id)
    {
        $invoice = Invoice::with(['client', 'company', 'items'])->findOrFail($id);
        $pdf = PDF::loadView('pdf.invoice', compact('invoice'));
        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, "invoice-{$invoice->invoice_number}.pdf");
    }

    public function previewPdf($id)
    {
        $invoice = Invoice::with(['client', 'company', 'items'])->findOrFail($id);
        $pdf = PDF::loadView('pdf.invoice', compact('invoice'));
        return response($pdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="invoice-'.$invoice->invoice_number.'.pdf"');
    }

    private function resetInputFields()
    {
        $this->invoice_number = '';
        $this->issue_date = '';
        $this->due_date = '';
        $this->client_id = '';
        $this->company_id = '';
        $this->items = [['description' => '', 'quantity' => 1, 'unit_price' => 0]];
        $this->invoice_id = '';
        $this->isEditing = false;
        $this->subtotal = 0;
        $this->tax_amount = 0;
        $this->total = 0;
    }
}
