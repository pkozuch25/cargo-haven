<div class="space-y-4">
    <div class="flex justify-between items-center mb-2">
        @include('livewire.partial.table-perpage')
    </div>
    <div style="position: relative">
        @include('livewire.partial.loader')
        <div class="table-responsive">
            <table style="width: 100%">
                <x-thead-table>
                    <x-table-row>
                        <x-th-table wire:click="sort('email')"
                            class="{{ $sortColumn == 'email' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Email') }}
                        </x-th-table>
                        <x-th-table wire:click="sort('name')"
                            class="{{ $sortColumn == 'name' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Name') }}
                        </x-th-table>
                        <x-th-table wire:click="sort('created_at')"
                            class="{{ $sortColumn == 'created_at' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Date of request') }}
                        </x-th-table>
                        <x-th-table wire:click="sort('rr_status')"
                            class="{{ $sortColumn == 'rr_status' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Status') }}
                        </x-th-table>
                    </x-table-row>
                    <x-table-row>
                        <x-th-table-search>
                            <x-search-text-input wire:model.live.debounce.500ms="searchTerm.text.email"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <x-search-text-input wire:model.live.debounce.500ms="searchTerm.text.name"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <x-search-text-input wire:model.live.debounce.500ms="searchTerm.text.created_at"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <div wire:ignore>
                                <select style="height: 30px;" id="rr-status-select" multiple>
                                    @foreach (\App\Enums\RegistrationRequestStatusEnum::cases() as $status)
                                        <option value="{{ $status->value }}" @if(array_key_exists($status->value, $selectedStatus)) selected @endif>{{ $status->name() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </x-th-table-search>
                    </x-table-row>
                </x-thead-table>
                <tbody>
                    @forelse($data as $request)
                        <x-tr-hover>
                            <x-td-table>{{ $request->email }}</x-td-table>
                            <x-td-table class="text-center">{{ $request->name }}</x-td-table>
                            <x-td-table class="text-center">{{ $request->created_at }}</x-td-table>
                            <x-td-table class="text-center">
                                <x-pill wire:click="$dispatch('openChangeStatusModal', {rr: {{ $request }}})" data-bs-toggle="modal" data-bs-target="#change-request-status-modal"
                                    class="{{ $request->rr_status->color() }} cursor-pointer">
                                    {{ $request->rr_status->name() }}
                                </x-pill>
                            </x-td-table>
                        </x-tr-hover>
                    @empty
                        <x-tr-hover>
                            <td colspan="4" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">{{ __('No results') }}</td>
                        </x-tr-hover>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @livewire('registration-requests.change-request-status-modal')
    @include('livewire.partial.table-pagination')

    @push('javascript')
        <script>
            window.addEventListener('refreshSelect2', function(event) {
                $(function() {
                    initSelect2();
                })
            })

            initSelect2();

            function initSelect2() {
                $('#rr-status-select').select2();
                $('#rr-status-select').on('change', function (e) {
                    var data = $('#rr-status-select').select2("val");
                    @this.set('searchTerm.selectMultiple.rr_status', data);
                });
            };

        </script>
    @endpush
</div>
