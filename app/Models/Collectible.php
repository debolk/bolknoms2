<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collectible extends Model
{
    use HasFactory;

    public $fillable = [
        'uuid'
    ];

    public function awards(): HasMany
    {
        return $this->hasMany(Award::class);
    }

    public function awardTo(User $user): void
    {
        $award = $this->awards()->firstOrCreate(
            ['user_id' => $user->id],
            ['awarded' => 0]
        );
        $award->increment('awarded');
    }

    public function stripFrom(User $user): void
    {
        $award = $this->awards()->where('user_id', $user->id)->first();
        if ($award->awarded === 1) {
            $award->delete();
        } else {
            $award->decrement('awarded');
        }
    }
}
