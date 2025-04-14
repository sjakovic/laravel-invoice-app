<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Specifications for Invoice #{{ $invoice->invoice_number }}</h2>
                        <button wire:click="startEditing" class="bg-gray-800 text-white px-4 py-2 rounded">
                            Add Specification
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Spent</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price/Hour</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if($editingId === 'new')
                                    <tr class="bg-gray-50">
                                        <td class="px-6 py-4">
                                            <input type="date" wire:model="editingSpecification.date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            @error('editingSpecification.date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea wire:model="editingSpecification.description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                            @error('editingSpecification.description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </td>
                                        <td class="px-6 py-4">
                                            <select wire:model="editingSpecification.type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <option value="regular">Regular</option>
                                                <option value="overtime">Overtime</option>
                                                <option value="holiday">Holiday</option>
                                            </select>
                                            @error('editingSpecification.type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="number" wire:model="editingSpecification.timespent" wire:change="calculateTotal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            @error('editingSpecification.timespent') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="number" step="0.01" wire:model="editingSpecification.price_per_hour" wire:change="calculateTotal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            @error('editingSpecification.price_per_hour') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" wire:model="editingSpecification.total" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex space-x-2">
                                                <button wire:click="save" class="bg-gray-800 text-white px-3 py-1 rounded">
                                                    Save
                                                </button>
                                                <button wire:click="cancelEdit" class="bg-gray-800 text-white px-3 py-1 rounded">
                                                    Cancel
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                                @forelse($specifications as $specification)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($specification['date'])->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4">{{ $specification['description'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($specification['type']) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $specification['timespent'] }} minutes</td>
                                        <td class="px-6 py-4 whitespace-nowrap">${{ number_format($specification['price_per_hour'], 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">${{ number_format($specification['total'], 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button wire:click="delete({{ $specification['id'] }})" class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No specifications found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 