<!-- Navigation Links -->

{{-- DISPOSITIONS --}}
@if(can('view_dispositions'))
    <x-responsive-nav-link :href="route('dispositions.index')" :active="request()->routeIs('dispositions.index')">
        {{ __('Dispositions') }}
    </x-responsive-nav-link>
@endif

{{-- OPERATIONS --}}
@if(can('view_operations'))
    @livewire('operations.operations-for-authenticated-user-counter', ['responsive' => true])
@endif

{{-- DEPOSITS --}}
<x-responsive-nav-link :href="route('deposits.index')" :active="request()->routeIs('deposits.index')">
    {{ __('Deposits') }}
</x-responsive-nav-link>

{{-- TRANSSSHIPMENT CARDS --}}
@if(can('view_transshipment_cards'))
    <x-responsive-nav-link :href="route('transshipment-cards.index')" :active="request()->routeIs('transshipment-cards.index')">
        {{ __('Transshipment cards') }}
    </x-responsive-nav-link>
@endif

{{-- STORAGE YARDS --}}
@if(can('view_storage_yards'))
    <x-responsive-nav-link :href="route('storage-yards.index')" :active="request()->routeIs('storage-yards.index')">
        {{ __('Storage yards') }}
    </x-responsive-nav-link>
@endif

{{-- REGISTRATION REQUESTS FOR ADMIN --}}
@if (getAuthenticatedUserModel()->isAdmin())
    @livewire('registration-requests.pending-registration-requests-counter', ['responsive' => true])
@endif
