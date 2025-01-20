<div class="space-y-4">
    {{-- Table Settings --}}
    <div class="flex justify-between items-center mb-2">
        @include('livewire.partial.table-perpage')
    </div>
    {{-- Table --}}
    <div style="position: relative">
        @include('livewire.partial.loader')
        <table style="width: 100%">
            <thead class="bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                {{-- Table Headers --}}
                <tr class="dark:bg-gray-900 border-b dark:border-gray-700">
                    <th wire:click="sort('email')"
                        class="cursor-pointer px-4 py-2 text-left {{ $sortColumn == 'email' ? ($sortDirection == 'desc' ? 'dark:bg-gray-700 sorting sorting_desc' : 'dark:bg-gray-600 sorting sorting_asc') : 'sorting' }}">
                        {{ __('Email') }}
                    </th>
                    <th wire:click="sort('name')"
                        class="cursor-pointer px-4 py-2 text-left {{ $sortColumn == 'name' ? ($sortDirection == 'desc' ? 'dark:bg-gray-700 sorting sorting_desc' : 'dark:bg-gray-600 sorting sorting_asc') : 'sorting' }}">
                        {{ __('Name') }}
                    </th>
                    <th wire:click="sort('created_at')"
                        class="cursor-pointer px-4 py-2 text-left {{ $sortColumn == 'created_at' ? ($sortDirection == 'desc' ? 'dark:bg-gray-700 sorting sorting_desc' : 'dark:bg-gray-600 sorting sorting_asc') : 'sorting' }}">
                        {{ __('Date of request') }}
                    </th>
                    <th wire:click="sort('rr_status')"
                        class="cursor-pointer px-4 py-2 text-left {{ $sortColumn == 'rr_status' ? ($sortDirection == 'desc' ? 'dark:bg-gray-700 sorting sorting_desc' : 'dark:bg-gray-600 sorting sorting_asc') : 'sorting' }}">
                        {{ __('Status') }}
                    </th>
                </tr>
                {{-- Search Inputs --}}
                <tr class="dark:bg-gray-900">
                    <th class="px-4 py-2">
                        <x-text-input wire:model.live.debounce.500ms="searchTerm.text.email" class="block mt-1 w-full" placeholder="{{ __('Search...') }}"/>
                    </th>
                    <th class="px-4 py-2">
                        <x-text-input wire:model.live.debounce.500ms="searchTerm.text.name" class="block mt-1 w-full" placeholder="{{ __('Search...') }}"/>

                    </th>
                    <th class="px-4 py-2">
                        <x-text-input wire:model.live.debounce.500ms="searchTerm.text.created_at" class="block mt-1 w-full" placeholder="{{ __('Search...') }}"/>
                    </th>
                    <th class="px-4 py-2">
                        <select multiple
                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring-lime-500 focus:border-lime-500 focus:ring-2 dark:focus:border-lime-500 dark:focus:ring-lime-500 rounded-md shadow-sm !important"
                                wire:model.live.debounce.500ms="searchTerm.selectMultiple.rr_status">
                            @foreach (\App\Enums\RegistrationRequestStatusEnum::cases() as $status)
                                <option value="{{ $status }}">{{ $status->name() }}</option>
                            @endforeach
                        </select>
                    </th>
                </tr>
            </thead>
            {{-- Table Body --}}
            <tbody>
                @forelse($this->queryRefresh() as $request)
                    <tr>
                        <td class="px-4 py-2 border-t dark:bg-gray-900 border-gray-300 dark:border-gray-700" style="text-align: center">{{ $request->email }}</td>
                        <td class="px-4 py-2 border-t dark:bg-gray-900 border-gray-300 dark:border-gray-700" style="text-align: center">{{ $request->name }}</td>
                        <td class="px-4 py-2 border-t dark:bg-gray-900 border-gray-300 dark:border-gray-700" style="text-align: center">{{ $request->created_at }}</td>
                        <td class="px-4 py-2 border-t dark:bg-gray-900 border-gray-300 dark:border-gray-700" style="text-align: center">
                            <span class="px-2 py-1 rounded text-sm bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                {{ $request->rr_status->name() }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">{{ __('No results') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @include('livewire.partial.table-pagination')
</div>
