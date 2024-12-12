@extends('layouts.mainLayoutWithPanel')

@section('title', __('Deposits'))

@section('content')

  <div class="row">
      <div class="col-12">
            <x-page-title>
                <i data-feather="box"></i>{{ __('Deposits') }}
            </x-page-title>
            @livewire('deposits.deposits-table')
      </div>
  </div>

@stop
