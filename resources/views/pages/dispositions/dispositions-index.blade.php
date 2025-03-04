@extends('layouts.mainLayout')

@section('title', __('Dispositions'))

@section('content')

<x-layout-component size=11>
  <div class="row">
      <div class="col-12">
        <div style="display: flex; align-items: center; gap: 10px;">
            <x-page-title>
                <i data-feather="file-text"></i>{{ __('Dispositions') }}
            </x-page-title>
            <x-button-add onclick="Livewire.dispatch('openAddEditDispositionModal')" modal="add-edit-disposition-modal"></x-button-add>
        </div>
            <x-panel>
                @livewire('dispositions.dispositions-table')
            </x-panel>
      </div>
    </div>
</x-layout-component>
@livewire('dispositions.add-edit-disposition-modal')

@stop
