@extends('layouts.mainLayout')

@section('title', __('Permissions'))

@section('content')

<x-layout-component size=8>
  <div class="row">
      <div class="col-12">
            <x-page-title>
                <i data-feather="user"></i>{{ __('Permissions') }}
            </x-page-title>
            <x-panel>
                @livewire('permissions.permissions-table')
            </x-panel>
      </div>
  </div>
</x-layout-component>

@stop
