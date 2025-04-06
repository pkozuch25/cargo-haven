@if(!$responsive)
    <x-nav-link :href="route('registration-requests.index')" :active="request()->routeIs('registration-requests.index')" :badge="$this->getRegistrationRequestsBadgeCount()">
        {{ __('Registration requests') }}
    </x-nav-link>
@else
    <x-responsive-nav-link :href="route('registration-requests.index')" :active="request()->routeIs('registration-requests.index')" :badge="$this->getRegistrationRequestsBadgeCount()">
        {{ __('Registration requests') }}
    </x-responsive-nav-link>
@endif