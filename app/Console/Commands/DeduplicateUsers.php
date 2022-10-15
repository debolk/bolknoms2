<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeduplicateUsers extends Command
{
    protected $signature = 'users:deduplicate {username}';
    protected $description = 'Deduplicate a user by their username, merging registrations';

    public function handle(): int
    {
        $duplicates = User::where('username', $this->argument('username'))->orderBy('created_at', 'asc')->get();
        if ($duplicates->count() < 2) {
            $this->info('Found no duplicates');
            return 1;
        }

        $original = $duplicates->shift();

        foreach ($duplicates as $dup) {
            $registrations = $dup->registrations;
            foreach ($registrations as $reg) {
                $reg->update(['user_id' => $original->id]);
            }
            $this->info("Reassigned {$registrations->count()} registrations to user id {$original->id}");
            $dup->delete();
            $this->info("Deleted duplicate user {$dup->id}");
        }

        return 0;
    }
}
