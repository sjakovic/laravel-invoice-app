<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            @if (session()->has('message'))
                <div class="bg-green-500 border border-green-600 text-white px-4 py-3 rounded relative mb-4 shadow-lg z-50" role="alert" style="background-color: #22c55e !important;">
                    <span class="block sm:inline font-medium">{{ session('message') }}</span>
                </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Invoices</h2>
                <button wire:click="create" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Create New Invoice
                </button>
            </div>

            @if($isEditing)
                <div class="bg-gray-50 p-6 rounded-lg mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $invoice_id ? 'Edit Invoice' : 'Create New Invoice' }}</h3>
                        <button wire:click="$set('isEditing', false)" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form wire:submit="{{ $invoice_id ? 'update' : 'store' }}">
                        <div class="flex flex-col space-y-6">
                            <div class="flex flex-row space-x-4">
                                <div class="flex-1">
                                    <label for="invoice_number" class="block text-sm font-medium text-gray-700">Invoice Number</label>
                                    <input type="text" wire:model="invoice_number" id="invoice_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-2 py-1 text-sm">
                                    @error('invoice_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="flex-1">
                                    <label for="issue_date" class="block text-sm font-medium text-gray-700">Issue Date</label>
                                    <input type="date" wire:model="issue_date" id="issue_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-2 py-1 text-sm">
                                    @error('issue_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="flex-1">
                                    <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                                    <input type="date" wire:model="due_date" id="due_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-2 py-1 text-sm">
                                    @error('due_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="flex-1">
                                    <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                                    <select wire:model="currency" id="currency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-2 py-1 text-sm">
                                        <option value="USD">USD</option>
                                        <option value="EUR">EUR</option>
                                        <option value="RSD">RSD</option>
                                    </select>
                                    @error('currency') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="flex-1">
                                    <label for="language" class="block text-sm font-medium text-gray-700">Language</label>
                                    <select wire:model="language" id="language" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-2 py-1 text-sm">
                                        <option value="en">English</option>
                                        <option value="sr">Serbian</option>
                                    </select>
                                    @error('language') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea wire:model="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                @error('notes') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Client</label>
                                <select wire:model="client_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select a client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                                @error('client_id') <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="mt-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Invoice Items</h4>
                                <div class="flex flex-row space-x-4 mb-2">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700">Description</label>
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700">Unit Price</label>
                                    </div>
                                    <div class="w-20"></div>
                                </div>
                                @foreach($items as $index => $item)
                                    <div class="flex flex-row space-x-4 mb-4">
                                        <div class="flex-1">
                                            <input type="text" wire:model="items.{{ $index }}.description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            @error("items.{$index}.description") <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="flex-1 px-2">
                                            <input type="text" wire:model="items.{{ $index }}.quantity" wire:change="calculateTotals" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            @error("items.{$index}.quantity") <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="flex-1">
                                            <input type="text" wire:model="items.{{ $index }}.unit_price" wire:change="calculateTotals" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            @error("items.{$index}.unit_price") <span class="text-red-600 text-sm font-bold mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="flex items-end w-20 px-2">
                                            @if(count($items) > 1)
                                                <button type="button" wire:click="removeItem({{ $index }})" class="text-red-600 hover:text-red-900">
                                                    Remove
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                <button type="button" wire:click="addItem" class="mt-4 text-blue-600 hover:text-blue-900">
                                    + Add Item
                                </button>
                            </div>

                            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                                    <div class="mt-1 text-lg font-semibold">${{ number_format($subtotal, 2) }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tax Rate (%)</label>
                                    <input type="text" wire:model="tax_rate" wire:change="calculateTotals" class="mt-1 block w-[100px] rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tax Amount</label>
                                    <div class="mt-1 text-lg font-semibold">${{ number_format($tax_amount, 2) }}</div>
                                </div>

                                <div class="md:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Total</label>
                                    <div class="mt-1 text-2xl font-bold text-blue-600">${{ number_format($total, 2) }}</div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <x-primary-button>
                                    {{ $invoice_id ? 'Update Invoice' : 'Create Invoice' }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                    <ul class="divide-y divide-gray-200">
                        @foreach($invoices as $invoice)
                            <li>
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-blue-600 truncate">
                                                Invoice #{{ $invoice->invoice_number }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{ $invoice->client->name }} - Due: {{ $invoice->due_date->format('M d, Y') }}
                                            </p>
                                            <p class="text-sm font-semibold text-gray-900">
                                                ${{ number_format($invoice->total, 2) }}
                                            </p>
                                        </div>
                                        <div class="flex flex-col" style="margin-left: -100px;">
                                            <div class="flex space-x-2">
                                                <span style="margin-right: 40px;" class="text-sm text-gray-500">Invoice</span>
                                                <div class="flex-1"></div>
                                                <button onclick="window.open('{{ route('invoices.preview', $invoice->id) }}', '_blank')" class="text-green-600 hover:text-blue-900 ml-2" title="Preview PDF">
                                                    <x-heroicon-s-document class="w-5 h-5" />
                                                </button>
                                                <button wire:click="edit({{ $invoice->id }})" class="text-indigo-600 hover:text-indigo-900 ml-2" title="Edit Invoice">
                                                    <x-heroicon-s-pencil class="w-5 h-5" />
                                                </button>
                                                <button wire:click="delete({{ $invoice->id }})" class="text-red-600 hover:text-red-900 ml-2" onclick="return confirm('Are you sure you want to delete this invoice?')" title="Delete Invoice">
                                                    <x-heroicon-s-trash class="w-5 h-5" />
                                                </button>
                                            </div>
                                            <div class="border-t border-gray-200 mt-1"></div>
                                            <div class="flex space-x-2 mt-1">
                                                <span style="margin-right: 40px;" class="text-sm text-gray-500">Specification</span>
                                                <div class="flex-1"></div>
                                                <button onclick="window.open('{{ route('invoices.specifications.preview', $invoice->id) }}', '_blank')" class="text-green-600 hover:text-blue-900 ml-2" title="View Specification">
                                                    <x-heroicon-s-document class="w-5 h-5" />
                                                </button>
                                                <button onclick="window.location.href='{{ route('invoices.specifications', $invoice->id) }}'" class="text-indigo-600 hover:text-indigo-900 ml-2" title="Edit Specification">
                                                    <x-heroicon-s-pencil class="w-5 h-5" />
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-4">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
