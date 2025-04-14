<?php

namespace App\Livewire;

use App\Models\Invoice;
use App\Models\InvoiceSpecification;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceSpecificationComponent extends Component
{
    public $invoice_id;
    public $specifications = [];
    public $editingId = null;
    public $editingSpecification = [
        'date' => '',
        'description' => '',
        'timespent' => '',
        'price_per_hour' => '',
        'total' => '',
        'type' => 'regular'
    ];

    protected $rules = [
        'editingSpecification.date' => 'required|date',
        'editingSpecification.description' => 'required',
        'editingSpecification.timespent' => 'required|integer|min:1',
        'editingSpecification.price_per_hour' => 'required|numeric|min:0',
        'editingSpecification.type' => 'required|in:regular,overtime,holiday',
    ];

    public function mount(Invoice $invoice)
    {
        $this->invoice_id = $invoice->id;
        $this->loadSpecifications();
    }

    public function loadSpecifications()
    {
        $this->specifications = InvoiceSpecification::where('invoice_id', $this->invoice_id)
            ->orderBy('date', 'desc')
            ->get()
            ->toArray();
    }

    public function startEditing()
    {
        $this->editingId = 'new';
        $this->editingSpecification = [
            'date' => now()->format('Y-m-d'),
            'description' => '',
            'timespent' => '',
            'price_per_hour' => '',
            'total' => '',
            'type' => 'regular'
        ];
    }

    public function calculateTotal()
    {
        if ($this->editingSpecification['timespent'] && $this->editingSpecification['price_per_hour']) {
            $hours = $this->editingSpecification['timespent'] / 60;
            $this->editingSpecification['total'] = round($hours * $this->editingSpecification['price_per_hour'], 2);
        }
    }

    public function save()
    {
        $this->validate();
        $this->calculateTotal();

        \Log::info('Editing Specification Data:', $this->editingSpecification);

        $data = [
            'invoice_id' => $this->invoice_id,
            'date' => $this->editingSpecification['date'],
            'description' => $this->editingSpecification['description'],
            'timespent' => $this->editingSpecification['timespent'],
            'price_per_hour' => $this->editingSpecification['price_per_hour'],
            'total' => $this->editingSpecification['total'],
            'type' => $this->editingSpecification['type']
        ];

        \Log::info('Data being saved:', $data);

        InvoiceSpecification::create($data);

        $this->cancelEdit();
        $this->loadSpecifications();
    }

    public function cancelEdit()
    {
        $this->editingId = null;
        $this->editingSpecification = [
            'date' => '',
            'description' => '',
            'timespent' => '',
            'price_per_hour' => '',
            'total' => '',
            'type' => 'regular'
        ];
    }

    public function delete($id)
    {
        InvoiceSpecification::find($id)->delete();
        $this->loadSpecifications();
    }

    public function previewPdf(Invoice $invoice)
    {
        $invoice->load(['company', 'client', 'specifications']);
        
        $pdf = Pdf::loadView('pdf.specifications', [
            'invoice' => $invoice,
            'company' => $invoice->company,
            'client' => $invoice->client,
            'specifications' => $invoice->specifications
        ]);

        return response($pdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="specifications.pdf"');
    }

    public function render()
    {
        return view('livewire.invoice-specification', [
            'invoice' => Invoice::find($this->invoice_id)
        ]);
    }
} 