<x-modal id="add-edit-disposition-modal" size="fullscreen" title="{{ $title }}" icon="fa fa-file-text-o">
    <x-slot name="modalBody">
        <div class="row">
            <div class="col-lg-2">
                <x-text-input-full :label="__('Yard')" wire:model='disposition.dis_yard_id' placeholder="{{ __('Yard') }}"></x-text-input-full>
                <x-input-error :messages="$errors->get('disposition.dis_yard_id')" class="mt-2" />
            </div>
            <div class="col-lg-2">
                <x-form-select :label="__('Relation from')" wire:model.live='disposition.dis_relation_from'>
                    <option value="">{{ __("Select") }}</option>
                    @foreach ($relationFromFormAvailableRelations as $relation)
                        <option value="{{$relation}}">{{ $relation->name() }}</option>
                    @endforeach
                </x-form-select>
                <x-input-error :messages="$errors->get('disposition.dis_relation_from')" class="mt-2" />
            </div>
            <div class="col-lg-2">
                <x-form-select :label="__('Relation to')" wire:model.live='disposition.dis_relation_to'>
                    <option value="">{{ __("Select") }}</option>
                    @foreach ($relationToFormAvailableRelations as $relation)
                        <option value="{{$relation}}">{{ $relation->name() }}</option>
                    @endforeach
                </x-form-select>
                <x-input-error :messages="$errors->get('disposition.dis_relation_to')" class="mt-2" />
            </div>
        </div>

        {{-- 
        plac
        relacja z
        relacja do
        uwagi
        sugerowana data 
        --}}
    </x-slot>
    <x-slot name="modalFooter">
        <x-button class="btn-success" wire:click='save'>
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-modal>
