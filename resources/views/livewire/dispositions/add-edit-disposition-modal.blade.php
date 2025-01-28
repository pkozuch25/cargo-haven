<x-modal id="add-edit-disposition-modal" size="fullscreen" title="{{ __('Add disposition') }}" icon="fa fa-file">
    <x-slot name="modalBody">
        <form>
            <div class="row">
                <div class="col-lg-2 col-md-4 col-12">
                    <x-input-label for="password" value="{{ __('Password') }}" />
                    <x-text-input wire:model='disposition.dis_relation_from'
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('Password') }}"
                    />
                </div>
            </div>
        </form>
    </x-slot>
    <x-slot name="modalFooter">
    </x-slot>
</x-modal>