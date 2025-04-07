<?php

namespace App\Livewire;

use App\Models\Company;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class CompanyComponent extends Component
{
    use WithFileUploads;

    public $user_id;
    public $company_id;
    public $name;
    public $address;
    public $city;
    public $zip_code;
    public $country;
    public $phone;
    public $email;
    public $id_number;
    public $tax_number;
    public $logo;
    public $logo_url;
    public $isEditing = false;
    public $company;

    protected $rules = [
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'zip_code' => 'required|string|max:20',
        'country' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'required|email|max:255',
        'id_number' => 'nullable|string|max:255',
        'tax_number' => 'nullable|string|max:255',
        'logo' => 'nullable|image|max:2048', // 2MB max
    ];

    public function mount()
    {
        $this->user_id = auth()->id();
        $company = Company::where('user_id', $this->user_id)->first();
        if ($company) {
            $this->edit($company->id);
        }
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->address = '';
        $this->city = '';
        $this->zip_code = '';
        $this->country = '';
        $this->phone = '';
        $this->email = '';
        $this->id_number = '';
        $this->tax_number = '';
        $this->logo = null;
        $this->logo_url = null;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'user_id' => $this->user_id,
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'zip_code' => $this->zip_code,
            'country' => $this->country,
            'phone' => $this->phone,
            'email' => $this->email,
            'id_number' => $this->id_number,
            'tax_number' => $this->tax_number,
        ];

        if ($this->logo) {
            $path = $this->logo->store('company-logos', 'public');
            $data['logo'] = $path;
            $this->logo_url = asset('storage/' . $path);
        }

        $company = Company::create($data);
        $this->company_id = $company->id;
        $this->isEditing = true;

        session()->flash('message', 'Company created successfully.');
    }

    public function edit($id)
    {
        $company = Company::findOrFail($id);
        $this->company_id = $id;
        $this->name = $company->name;
        $this->address = $company->address;
        $this->city = $company->city;
        $this->zip_code = $company->zip_code;
        $this->country = $company->country;
        $this->phone = $company->phone;
        $this->email = $company->email;
        $this->id_number = $company->id_number;
        $this->tax_number = $company->tax_number;
        $this->logo_url = $company->logo_url;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate();

        $company = Company::findOrFail($this->company_id);
        
        $data = [
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'zip_code' => $this->zip_code,
            'country' => $this->country,
            'phone' => $this->phone,
            'email' => $this->email,
            'id_number' => $this->id_number,
            'tax_number' => $this->tax_number,
        ];

        if ($this->logo) {
            // Delete old logo if exists
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $path = $this->logo->store('company-logos', 'public');
            $data['logo'] = $path;
            $this->logo_url = asset('storage/' . $path);
        }

        $company->update($data);

        session()->flash('message', 'Company updated successfully.');
    }

    public function removeLogo()
    {
        $company = Company::findOrFail($this->company_id);
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
            $company->update(['logo' => null]);
            $this->logo_url = null;
            $this->logo = null;
            session()->flash('message', 'Logo removed successfully.');
        }
    }

    public function render()
    {
        return view('livewire.company')
            ->layout('layouts.app');
    }
}
