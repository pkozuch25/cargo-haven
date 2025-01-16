@extends('layouts.mainLayout')

@section('title', __('Registration requests'))

@section('content')

  <div class="row">
      <div class="col-12">
            <x-page-title>
                <i data-feather="box"></i>{{ __('Registration requests') }}
            </x-page-title>
            <x-panel>
                @livewire('registration-requests.registration-requests-table')
            </x-panel>
      </div>
  </div>

@stop
