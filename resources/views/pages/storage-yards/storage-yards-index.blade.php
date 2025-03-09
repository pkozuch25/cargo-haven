@extends('layouts.mainLayout')

@section('title', __('Storage yards'))

@section('content')

<x-layout-component size=8>
  <div class="row">
      <div class="col-12">
            <x-page-title>
                <i data-feather="box"></i>{{ __('Storage yards') }}
            </x-page-title>
            <x-panel>
                @livewire('storage-yards.storage-yards-table')
            </x-panel>
      </div>
  </div>
</x-layout-component>
@stop
