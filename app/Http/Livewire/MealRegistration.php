<?php

namespace App\Http\Livewire;

use App\Models\Meal;
use App\Models\User;
use Livewire\Component;

class MealRegistration extends Component
{
    public Meal $meal;
    public User $user;

    public function render()
    {
        return view('livewire.meal-registration', [
            'meal' => $this->meal,
            'uuser' => $this->user,
        ]);
    }
}
