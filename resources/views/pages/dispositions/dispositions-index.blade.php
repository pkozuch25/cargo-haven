@extends('layouts.mainLayout')

@section('title', __('Dispositions'))

@section('content')

  <div class="row">
      <div class="col-12">
            <x-page-title>
                <i data-feather="box"></i>{{ __('Dispositions') }}
            </x-page-title>
            <x-panel>
                @livewire('dispositions.dispositions-table')
            </x-panel>
      </div>
  </div>

@stop
