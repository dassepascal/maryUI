<?php

use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\Attributes\Rule;
use App\Models\User;
use App\Models\Country;


new class extends Component {

    use Toast;
    
    public User $user;

   #[Rule('required')] 
   public string $name = '';

   #[Rule('required')] 
   public string $email = '';

   // Optional
    #[Rule('sometimes')]
    public ?int $country_id = null;


    public function mount()
    {
        $this->fill($this->user);
    }

    public function save(): void
    {
        //validate
        $data = $this->validate();

        //update
        $this->user->update($data);

        $this->success('User updated.', redirectTo: '/users');
    }
 
    // We also need this to fill Countries combobox on upcoming form
    public function with(): array 
    {
        return [
            'countries' => Country::all()
        ];
    }
};
 ?>

<div>
   <x-header title="Update {{ $user->name }}" separator>
   </x-header>

    <x-form wire:submit="save"> 
        <x-input label="Name" wire:model="name" />
        <x-input label="Email" wire:model="email" />
        <x-select label="Country" wire:model="country_id" :options="$countries" placeholder="---" />
 
        <x-slot:actions>
            <x-button label="Cancel" link="/users" />
            {{-- The important thing here is `type="submit"` --}}
            {{-- The spinner property is nice! --}}
            <x-button label="Save" icon="o-paper-airplane" spinner="save" type="submit" class="btn-primary" />
        </x-slot:actions>
    </x-form>
</div>
