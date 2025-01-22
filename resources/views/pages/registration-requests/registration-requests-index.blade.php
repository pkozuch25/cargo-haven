@extends('layouts.mainLayout')

@section('title', __('Registration requests'))

@section('content')

  <div class="row">
      <div class="col-12">
            <x-page-title>
                <i data-feather="file-plus"></i>{{ __('Registration requests') }}
            </x-page-title>
            <x-panel>
                @livewire('registration-requests.registration-requests-table')
            </x-panel>
      </div>
      @livewire('registration-requests.change-status-modal')
  </div>

@stop
