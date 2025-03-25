<?php

use App\Livewire\Dispositions\AddEditDispositionModal;
use App\Models\Disposition;
use App\Models\StorageYard;
use App\Models\User;
use App\Enums\DispositionStatusEnum;
use App\Enums\OperationRelationEnum;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    
    $this->storageYard = StorageYard::factory()->create();
});

test('can mount component', function () {
    Livewire::test(AddEditDispositionModal::class)
        ->assertOk()
        ->assertViewIs('livewire.dispositions.add-edit-disposition-modal');
});

test('can open modal with new disposition', function () {
    Livewire::test(AddEditDispositionModal::class)
        ->call('openAddEditDispositionModal', new Disposition())
        ->assertSet('edit', false)
        ->assertSet('title', __('Add disposition'));
});

test('can open modal with existing disposition', function () {
    $disposition = Disposition::factory()->create([
        'dis_status' => DispositionStatusEnum::NOT_CONFIRMED->value,
        'dis_yard_id' => $this->storageYard->sy_id,
        'dis_relation_from' => OperationRelationEnum::YARD->value,
        'dis_relation_to' => OperationRelationEnum::CARRIAGE->value,
    ]);

    Livewire::test(AddEditDispositionModal::class)
        ->call('openAddEditDispositionModal', $disposition)
        ->assertSet('edit', true)
        ->assertSet('disposition.dis_id', $disposition->dis_id);
});

test('can create new disposition', function () {
    $operators = User::factory(2)->create()->pluck('id')->toArray();
    $suggestedDate = now()->format('Y-m-d');

    $component = Livewire::test(AddEditDispositionModal::class)
        ->call('openAddEditDispositionModal', new Disposition());
    
    $component->set('disposition.dis_yard_id', $this->storageYard->sy_id)
        ->set('disposition.dis_relation_from', OperationRelationEnum::YARD->value)
        ->set('disposition.dis_relation_to', OperationRelationEnum::CARRIAGE->value)
        ->set('disposition.dis_notes', 'Test notes')
        ->set('disposition.dis_suggested_date', $suggestedDate)
        ->set('dispositionOperators', $operators);
    
    $component->call('save');
    
    $this->assertDatabaseHas('dispositions', [
        'dis_yard_id' => $this->storageYard->sy_id,
        'dis_relation_from' => OperationRelationEnum::YARD->value,
        'dis_relation_to' => OperationRelationEnum::CARRIAGE->value,
        'dis_notes' => 'Test notes',
    ]);
});

test('can edit existing disposition', function () {
    $disposition = Disposition::factory()->create([
        'dis_status' => DispositionStatusEnum::NOT_CONFIRMED->value,
        'dis_yard_id' => $this->storageYard->sy_id,
        'dis_relation_from' => OperationRelationEnum::YARD->value,
        'dis_relation_to' => OperationRelationEnum::CARRIAGE->value,
        'dis_notes' => 'Original notes',
        'dis_suggested_date' => now()->format('Y-m-d'),
    ]);
    
    $operators = User::factory(2)->create();
    $disposition->operators()->attach($operators->pluck('id'));

    $component = Livewire::test(AddEditDispositionModal::class)
        ->call('openAddEditDispositionModal', $disposition);
    
    $component->set('disposition.dis_notes', 'Updated notes')
        ->call('save');
    
    $this->assertDatabaseHas('dispositions', [
        'dis_id' => $disposition->dis_id,
        'dis_notes' => 'Updated notes',
    ]);
});

test('validates required fields', function () {
    $component = Livewire::test(AddEditDispositionModal::class)
        ->call('openAddEditDispositionModal', new Disposition());
    
    $component->call('save')
        ->assertHasErrors([
            'disposition.dis_yard_id',
            'disposition.dis_relation_from', 
            'disposition.dis_relation_to',
            'disposition.dis_suggested_date',
            'dispositionOperators',
        ]);
});

test('can change disposition status', function () {
    $disposition = Disposition::factory()->create([
        'dis_status' => DispositionStatusEnum::NOT_CONFIRMED->value,
        'dis_yard_id' => $this->storageYard->sy_id,
    ]);

    Livewire::test(AddEditDispositionModal::class)
        ->call('openAddEditDispositionModal', $disposition)
        ->call('changeStatus');

    $this->assertDatabaseHas('dispositions', [
        'dis_id' => $disposition->dis_id,
        'dis_status' => DispositionStatusEnum::CONFIRMED->value,
    ]);
});

test('can cancel disposition', function () {
    $disposition = Disposition::factory()->create([
        'dis_status' => DispositionStatusEnum::NOT_CONFIRMED->value,
        'dis_yard_id' => $this->storageYard->sy_id,
    ]);

    Livewire::test(AddEditDispositionModal::class)
        ->call('openAddEditDispositionModal', $disposition)
        ->call('cancelDisposition');

    $this->assertDatabaseHas('dispositions', [
        'dis_id' => $disposition->dis_id,
        'dis_status' => DispositionStatusEnum::CANCELLED->value,
    ]);
});
