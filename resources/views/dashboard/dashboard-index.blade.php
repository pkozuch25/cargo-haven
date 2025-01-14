@extends('layouts.mainLayout')

@section('title', __('Dashboard'))

@section('content')

  <div class="row">
      <div class="col-12">
            <x-page-title>
                <i data-feather="box"></i>{{ __('Dashboard') }}
            </x-page-title>
            <x-panel>
                @livewire('dashboard.dashboard-main')
            </x-panel>
      </div>
  </div>

@stop
