<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use SoftDeletes;
    use HasFactory;
    use HasApiTokens;

    protected $fillable = [
        'username',
        'name',
        'email',
        'is_board',
    ];

    protected $casts = [
        'authorizations' => 'array',
        'blocked' => 'boolean',
        'is_board' => 'boolean',
    ];

    /**
     * @return HasMany<Registration, $this>
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(\App\Models\Registration::class)->orderBy('name');
    }

    /**
     * Return whether the user is registered to the meal
     */
    public function registeredFor(Meal $meal): bool
    {
        return $this->registrations()->where(['meal_id' => $meal->id])->count() > 0;
    }

    /**
     * Return the Registration for this user and meal
     */
    public function registrationFor(Meal $meal): ?Registration
    {
        return $this->registrations()->where(['meal_id' => $meal->id])->first();
    }

    /**
     * Returns a list of dates on which you've joined a meal
     */
    public function dateList(): Collection
    {
        return DB::table('registrations')
            ->select('meals.meal_timestamp')
            ->leftJoin('meals', 'registrations.meal_id', '=', 'meals.id')
            ->where('user_id', '=', $this->id)
            ->whereNull('registrations.deleted_at')
            ->whereNull('meals.deleted_at')
            ->get();
    }
}
