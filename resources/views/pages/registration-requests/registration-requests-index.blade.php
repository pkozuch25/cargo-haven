@extends('layouts.mainLayout')

@section('title', __('Registration requests'))

@section('content')

<x-layout-component size=8>
    <div class="row">
        <div class="col-12">
            <x-page-title>
                <i data-feather="file-plus"></i>{{ __('Registration requests') }}
            </x-page-title>
            <x-panel>
                @livewire('registration-requests.registration-requests-table')
            </x-panel>
        </div>
    </div>
</x-layout-component>

@stop
