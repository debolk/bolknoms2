<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Handicap extends Component
{
    public User $user;
    public string $handicap;
    public bool $saved = false;

    public function mount(): void
    {
        $this->user = Auth::user();
        $this->handicap = Auth::user()->handicap ?? '';
    }

    public function render()
    {
        return view('livewire.handicap');
    }

    public function store(): void
    {
        $this->user->handicap = $this->handicap;
        $this->user->save();
        $this->saved = true;
    }
}
