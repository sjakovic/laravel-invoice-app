<?php

namespace App\Livewire;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ClientComponent extends Component
{
    use WithPagination, WithFileUploads;

    public $name;
    public $address;
    public $city;
    public $postal_code;
    public $country;
    public $phone;
    public $email;
    public $type = 'individual';
    public $id_number;
    public $tax_number;
    public $client_id;
    public $isEditing = false;
    public $image;
    public $image_url;

    protected $rules = [
        'name' => 'required|min:3',
        'address' => 'required',
        'city' => 'required',
        'postal_code' => 'required',
        'country' => 'required',
        'phone' => 'required',
        'email' => 'required|email',
        'type' => 'required|in:individual,business',
        'id_number' => 'required_if:type,business',
        'tax_number' => 'required_if:type,business',
        'image' => 'nullable|image|max:2048', // 2MB max
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

        $data = [
            'user_id' => auth()->id(),
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'phone' => $this->phone,
            'email' => $this->email,
            'type' => $this->type,
            'id_number' => $this->id_number,
            'tax_number' => $this->tax_number,
        ];

        if ($this->image) {
            $path = $this->image->store('client-images', 'public');
            $data['image'] = $path;
            $this->image_url = asset('storage/' . $path);
        }

        Client::create($data);

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
        $this->postal_code = $client->postal_code;
        $this->country = $client->country;
        $this->phone = $client->phone;
        $this->email = $client->email;
        $this->type = $client->type;
        $this->id_number = $client->id_number;
        $this->tax_number = $client->tax_number;
        $this->image_url = $client->image_url;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        $client = Client::find($this->client_id);
        $data = [
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'phone' => $this->phone,
            'email' => $this->email,
            'type' => $this->type,
            'id_number' => $this->id_number,
            'tax_number' => $this->tax_number,
        ];

        if ($this->image) {
            // Delete old image if exists
            if ($client->image) {
                Storage::disk('public')->delete($client->image);
            }
            $path = $this->image->store('client-images', 'public');
            $data['image'] = $path;
            $this->image_url = asset('storage/' . $path);
        }

        $client->update($data);

        session()->flash('message', 'Client updated successfully.');
        $this->dispatch('notify', ['message' => 'Client updated successfully.']);
    }

    public function removeImage()
    {
        $client = Client::findOrFail($this->client_id);
        if ($client->image) {
            Storage::disk('public')->delete($client->image);
            $client->update(['image' => null]);
            $this->image_url = null;
            $this->image = null;
            session()->flash('message', 'Image removed successfully.');
        }
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
        $this->postal_code = '';
        $this->country = '';
        $this->phone = '';
        $this->email = '';
        $this->type = 'individual';
        $this->id_number = '';
        $this->tax_number = '';
        $this->client_id = '';
        $this->isEditing = false;
        $this->image = null;
        $this->image_url = null;
    }
}
