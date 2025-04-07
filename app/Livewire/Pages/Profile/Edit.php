<?php

namespace App\Livewire\Pages\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    public $name;
    public $email;
    public $current_password;
    public $password;
    public $password_confirmation;
    public $delete_password;

    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore(Auth::id())],
        ]);

        Auth::user()->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->dispatch('profile-updated', name: $this->name);
        session()->flash('message', 'Profile updated successfully.');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->password)
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');
        session()->flash('status', 'password-updated');
    }

    public function deleteUser()
    {
        $this->validate([
            'delete_password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();
        Auth::logout();
        $user->delete();

        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.pages.profile.edit')
            ->layout('layouts.app');
    }
} 