@extends('layouts.mainLayoutWithPanel')

@section('title', __('Dashboard'))

@section('content')

  <div class="row">
      <div class="col-12">
            <x-page-title>
                <i data-feather="box"></i>{{ __('Dashboard') }}
            </x-page-title>
            @livewire('dashboard.dashboard-main')
      </div>
  </div>

@stop
