@extends('layouts.mainLayout')

@section('title', __('Permissions'))

@section('content')

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

@stop
