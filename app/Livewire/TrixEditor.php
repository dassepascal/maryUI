<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;

class TrixEditor extends Component
{

    public $content;
    public $label;
    public $hint;
    public $trixId;

    public function mount($content = '', $label = '', $hint = '')
    {
        $this->content = $content;
        $this->label = $label;
        $this->hint = $hint;
        $this->trixId = 'trix-' . Str::random(8);
    }

    public function updatedContent($value)
    {
        $this->emit('trixUpdated', $this->content);
    }
    public function render()
    {
        return view('livewire.trix-editor');
    }
}
