<?php

use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Livewire\Attributes\Rule;
use App\Models\User;
use App\Models\Country;
use Livewire\WithFileUploads;
use App\Models\Language;

new class extends Component {
    use Toast, WithFileUploads;

    public User $user;

    #[Rule('required')]
    public string $name = '';

    #[Rule('required')]
    public string $email = '';

    // Optional
    #[Rule('sometimes')]
    public ?int $country_id = null;

    #[Rule('nullable|image|max:1024')]
    public $photo;

    // Selected languages
    #[Rule('required')]
    public array $my_languages = [];

    // Optional
    #[Rule('sometimes')]
    public ?string $bio = null;

    public function mount()
    {
        $this->fill($this->user);

        // Fill the selected languages property
        $this->my_languages = $this->user->languages->pluck('id')->all();
    }

    public function save(): void
    {
        //validate
        $data = $this->validate();

        //update
        $this->user->update($data);

        // Sync selection
        $this->user->languages()->sync($this->my_languages);

        // Upload file and save the avatar `url` on User model
        if ($this->photo) {
            $url = $this->photo->store('users', 'public');
            $this->user->update(['avatar' => "/storage/$url"]);
        }

        $this->success('User updated.', redirectTo: '/users');
    }
    protected $listeners = ['trixUpdated' => 'updateBio'];

    public function updateBio($value)
    {
        $this->bio = $value;
    }

    // We also need this to fill Countries combobox on upcoming form
    public function with(): array
    {
        return [
            'countries' => Country::all(),
            'languages' => Language::all(),
        ];
    }
};
?>

<div>
    <x-header title="Update {{ $user->name }}" separator>
    </x-header>
    <div class="grid gap-5 lg:grid-cols-2">
        <div>
            <x-form wire:submit="save">
                {{-- Basic section --}}
                <div class="lg:grid grid-cols-5">
                    <div class="col-span-2">
                        <x-header title="Basic" subtitle="Basic info from user" size="text-2xl" />
                    </div>
                    <div class="col-span-3 grid gap-3">
                         {{-- some fields here --}}
                        <x-file label="Avatar" wire:model="photo" accept="image/png, image/jpeg" crop-after-change>
                            <img src="{{ $user->avatar ?? '/empty-user.jpg' }}" class="h-40 rounded-lg" />
                        </x-file>
        
                        <x-input label="Name" wire:model="name" />
                        <x-input label="Email" wire:model="email" />
                        <x-select label="Country" wire:model="country_id" :options="$countries" placeholder="---" />
                    </div>
                </div>
                {{--  Details section --}}
        <hr class="my-5" />
 
        <div class="lg:grid grid-cols-5">
            <div class="col-span-2">
                <x-header title="Details" subtitle="More about the user" size="text-2xl" />
            </div>
            <div class="col-span-3 grid gap-3">
                {{-- another fields here --}}
                  {{-- Multi selection --}}
                  <x-choices-offline label="My languages" wire:model="my_languages" :options="$languages" searchable />

                  <livewire:trix-editor :content="$bio" label="Bio" hint="The great biography" />
            </div>
        </div>
               

              

                <x-slot:actions>
                    <x-button label="Cancel" link="/users" />
                    {{-- The important thing here is `type="submit"` --}}
                    {{-- The spinner property is nice! --}}
                    <x-button label="Save" icon="o-paper-airplane" spinner="save" type="submit"
                        class="btn-primary" />
                </x-slot:actions>
            </x-form>
        </div>
        {{-- <div>
            
            <img src="/04-b.jpg" width="300" class="mx-auto" />
        </div> --}}
    </div>

</div>
