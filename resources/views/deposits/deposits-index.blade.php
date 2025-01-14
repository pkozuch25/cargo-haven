@extends('layouts.mainLayout')

@section('title', __('Deposits'))

@section('content')

  <div class="row">
      <div class="col-12">
            <x-page-title>
                <i data-feather="box"></i>{{ __('Deposits') }}
            </x-page-title>
            <x-panel>
                @livewire('deposits.deposits-table')
            </x-panel>
      </div>
  </div>

@stop
