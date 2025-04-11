<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Company Information') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session()->has('message'))
                        <div class="bg-green-500 border border-green-600 text-white px-4 py-3 rounded relative mb-4 shadow-lg z-50" role="alert" style="background-color: #22c55e !important;">
                            <span class="block sm:inline font-medium">{{ session('message') }}</span>
                        </div>
                    @endif

                    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
                        @if($logo_url)
                            <div class="mb-4">
                                <img src="{{ $logo_url }}" alt="Company Logo" class="h-20 w-auto">
                                <button type="button" wire:click="removeLogo" class="mt-2 text-red-600 hover:text-red-900">
                                    Remove Logo
                                </button>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label for="logo" class="block text-sm font-medium text-gray-700">Company Logo</label>
                            <input type="file" wire:model="logo" id="logo" class="mt-1 block w-full">
                            @error('logo') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Company Name')" />
                                <x-text-input wire:model="name" id="name" type="text" class="mt-1 block w-full" />
                                @error('name') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input wire:model="email" id="email" type="email" class="mt-1 block w-full" />
                                @error('email') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <x-input-label for="phone" :value="__('Phone')" />
                                <x-text-input wire:model="phone" id="phone" type="text" class="mt-1 block w-full" />
                                @error('phone') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <x-input-label for="address" :value="__('Address')" />
                                <x-text-input wire:model="address" id="address" type="text" class="mt-1 block w-full" />
                                @error('address') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <x-input-label for="city" :value="__('City')" />
                                <x-text-input wire:model="city" id="city" type="text" class="mt-1 block w-full" />
                                @error('city') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <x-input-label for="postal_code" :value="__('Postal code')" />
                                <x-text-input wire:model="postal_code" id="postal_code" type="text" class="mt-1 block w-full" />
                                @error('postal_code') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <x-input-label for="country" :value="__('Country')" />
                                <x-text-input wire:model="country" id="country" type="text" class="mt-1 block w-full" />
                                @error('country') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <x-input-label for="id_number" :value="__('ID Number')" />
                                <x-text-input wire:model="id_number" id="id_number" type="text" class="mt-1 block w-full" />
                                @error('id_number') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <x-input-label for="tax_number" :value="__('Tax Number')" />
                                <x-text-input wire:model="tax_number" id="tax_number" type="text" class="mt-1 block w-full" />
                                @error('tax_number') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-primary-button>
                                {{ __('Save Company Information') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
