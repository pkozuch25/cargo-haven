@extends('layouts.mainLayout')

@section('title', __('Users'))

@section('content')

<x-layout-component size=8>
    <div class="row">
        <div class="col-12">
            <div style="display: flex; align-items: center; gap: 10px;">
                <x-page-title>
                    <i data-feather="user"></i>{{ __('Users') }}
                </x-page-title>
            </div>
            <x-panel>
                @livewire('users.users-table')
            </x-panel>
        </div>
    </div>
</x-layout-component>
@livewire('users.manage-user-modal')
@stop
