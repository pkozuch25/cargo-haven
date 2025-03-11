@extends('layouts.mainLayout')

@section('title', __('Storage yards'))

@section('content')

<x-layout-component size=8>
  <div class="row">
      <div class="col-12">
            <div style="display: flex; align-items: center; gap: 10px;">
                <x-page-title>
                    <i data-feather="box"></i>{{ __('Storage yards') }}
                </x-page-title>
                @can('edit_storage_yards')
                    <x-button-add onclick="Livewire.dispatch('openAddEditYardModal')" modal="add-edit-storage-yard-modal"></x-button-add>
                @endcan
            </div>
            <x-panel>
                @livewire('storage-yards.storage-yards-table')
            </x-panel>
      </div>
  </div>
</x-layout-component>
@livewire('storage-yards.add-edit-storage-yard-modal')
@stop
