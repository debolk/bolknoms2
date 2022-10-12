<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Collectible extends Model
{
    use HasFactory;

    public $fillable = [
        'uuid'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function awardTo(User $user): void
    {
        $this->users()->save($user);
    }
}
