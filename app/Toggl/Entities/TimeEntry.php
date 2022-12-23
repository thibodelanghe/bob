<?php

namespace App\Toggl\Entities;

use Carbon\Carbon;

class TimeEntry
{
    public function __construct(
        public readonly int $id,
        public readonly Carbon $start,
        public readonly Carbon $end,
        public readonly string $description,
    ){}
}
