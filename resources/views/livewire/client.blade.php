<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Clients</h2>
                <button wire:click="create" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Add New Client
                </button>
            </div>

            @if($isEditing)
                <div class="bg-gray-50 p-6 rounded-lg mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $client_id ? 'Edit Client' : 'Add New Client' }}</h3>
                        <button wire:click="$set('isEditing', false)" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form wire:submit="{{ $client_id ? 'update' : 'store' }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('name') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" wire:model="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('email') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="text" wire:model="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('phone') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Address</label>
                                <input type="text" wire:model="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('address') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text" wire:model="city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('city') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">State</label>
                                <input type="text" wire:model="state" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('state') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Postal Code</label>
                                <input type="text" wire:model="postal_code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('postal_code') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Client Type</label>
                                <select wire:model.live="is_business" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select Type</option>
                                    <option value="0">Individual</option>
                                    <option value="1">Business</option>
                                </select>
                                @error('is_business') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            @if($is_business)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">ID Number</label>
                                    <input type="text" wire:model="id_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('id_number') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tax Number</label>
                                    <input type="text" wire:model="tax_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('tax_number') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            @endif
                        </div>

                        <div class="mt-6">
                            <x-primary-button>
                                {{ $client_id ? 'Update Client' : 'Create Client' }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($clients as $client)
                        <li>
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-blue-600 truncate">
                                            {{ $client->name }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $client->email }}
                                        </p>
                                    </div>
                                    <div class="ml-4 flex-shrink-0 flex space-x-2">
                                        <button wire:click="edit({{ $client->id }})" class="text-indigo-600 hover:text-indigo-900" title="Edit Client">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button wire:click="delete({{ $client->id }})" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this client?')" title="Delete Client">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="mt-4">
                {{ $clients->links() }}
            </div>
        </div>
    </div>
</div>
