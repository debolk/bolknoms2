<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid;

class GenerateMissingUUIDs extends Command
{
    protected $signature = 'uuids:generate';
    protected $description = 'Generates missing UUIDs for existing models';

    public function handle(): int
    {
        $models = [
            \App\Models\Meal::class,
            \App\Models\Registration::class,
        ];

        foreach ($models as $model) {
            foreach ($model::whereNull('uuid')->lazy() as $instance) {
                $instance->uuid = Uuid::uuid4();
                $instance->save();
            }
        }

        return Command::SUCCESS;
    }
}
