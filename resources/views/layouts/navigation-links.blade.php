<!-- Navigation Links -->

{{-- DISPOSITIONS --}}
@if(can('view_dispositions'))
    <x-nav-link-wrapper>
        <x-nav-link :href="route('dispositions.index')" :active="request()->routeIs('dispositions.index')">
            {{ __('Dispositions') }}
        </x-nav-link>
    </x-nav-link-wrapper>
@endif

{{-- OPERATIONS --}}
@if(can('view_operations'))
    <x-nav-link-wrapper>
        @livewire('operations.operations-for-authenticated-user-counter')
    </x-nav-link-wrapper>
@endif

{{-- DEPOSITS --}}
    <x-nav-link-wrapper>
        <x-nav-link :href="route('deposits.index')" :active="request()->routeIs('deposits.index')">
            {{ __('Deposits') }}
        </x-nav-link>
    </x-nav-link-wrapper>

{{-- TRANSSSHIPMENT CARDS --}}
@if(can('view_transshipment_cards'))
    <x-nav-link-wrapper>
        <x-nav-link :href="route('transshipment-cards.index')" :active="request()->routeIs('transshipment-cards.index')">
            {{ __('Transshipment cards') }}
        </x-nav-link>
    </x-nav-link-wrapper>
@endif

{{-- STORAGE YARDS --}}
@if(can('view_storage_yards'))
    <x-nav-link-wrapper>
        <x-nav-link :href="route('storage-yards.index')" :active="request()->routeIs('storage-yards.index')">
            {{ __('Storage yards') }}
        </x-nav-link>
    </x-nav-link-wrapper>
@endif

{{-- REGISTRATION REQUESTS FOR ADMIN --}}
@if (getAuthenticatedUserModel()->isAdmin())
    <x-nav-link-wrapper>
        @livewire('registration-requests.pending-registration-requests-counter')
    </x-nav-link-wrapper>
@endif
