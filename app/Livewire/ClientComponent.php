<?php

namespace App\Livewire;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class ClientComponent extends Component
{
    use WithPagination;

    public $name;
    public $address;
    public $city;
    public $state;
    public $postal_code;
    public $phone;
    public $email;
    public $is_business;
    public $id_number;
    public $tax_number;
    public $client_id;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|min:3',
        'address' => 'required',
        'city' => 'required',
        'state' => 'required',
        'postal_code' => 'required',
        'phone' => 'required',
        'email' => 'required|email',
        'is_business' => 'boolean',
        'id_number' => 'required_if:is_business,true',
        'tax_number' => 'required_if:is_business,true',
    ];

    public function render()
    {
        return view('livewire.client', [
            'clients' => Client::where('user_id', auth()->id())->paginate(10)
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isEditing = true;
    }

    public function store()
    {
        $this->validate();

        Client::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'phone' => $this->phone,
            'email' => $this->email,
            'is_business' => $this->is_business,
            'id_number' => $this->id_number,
            'tax_number' => $this->tax_number,
            'type' => $this->is_business ? 'business' : 'individual',
        ]);

        session()->flash('message', 'Client created successfully.');
        $this->resetInputFields();
        $this->isEditing = false;
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        $this->client_id = $id;
        $this->name = $client->name;
        $this->address = $client->address;
        $this->city = $client->city;
        $this->state = $client->state;
        $this->postal_code = $client->postal_code;
        $this->phone = $client->phone;
        $this->email = $client->email;
        $this->is_business = $client->is_business;
        $this->id_number = $client->id_number;
        $this->tax_number = $client->tax_number;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        $client = Client::find($this->client_id);
        $client->update([
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'phone' => $this->phone,
            'email' => $this->email,
            'is_business' => $this->is_business,
            'id_number' => $this->id_number,
            'tax_number' => $this->tax_number,
            'type' => $this->is_business ? 'business' : 'individual',
        ]);

        session()->flash('message', 'Client updated successfully.');
        $this->resetInputFields();
        $this->isEditing = false;
    }

    public function delete($id)
    {
        Client::find($id)->delete();
        session()->flash('message', 'Client deleted successfully.');
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->address = '';
        $this->city = '';
        $this->state = '';
        $this->postal_code = '';
        $this->phone = '';
        $this->email = '';
        $this->is_business = false;
        $this->id_number = '';
        $this->tax_number = '';
        $this->client_id = '';
        $this->isEditing = false;
    }
}
