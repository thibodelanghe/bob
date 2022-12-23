<?php

namespace App\Toggl\Repositories;

use App\Toggl\Clients\Toggl;
use App\Toggl\Collections\TimeEntryCollection;
use App\Toggl\Entities\TimeEntry;
use App\Toggl\Exceptions\NonValidDateRangeException;
use Carbon\Carbon;
use Illuminate\Support\Carbon as CarbonFacade;

class TimeEntryRepository
{
    public function __construct(
        protected Toggl $toggl,
    ){}

    public function betweenDate(Carbon $start, Carbon $end): TimeEntryCollection
    {
        if (! $start->isBefore($end)) {
            throw new NonValidDateRangeException();
        }

        $data = $this->toggl->get('me/time_entries', [
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
        ]);

        return (new TimeEntryCollection($data))->map(fn ($entry) => new TimeEntry(
            $entry['id'],
            CarbonFacade::parse($entry['start']),
            CarbonFacade::parse($entry['stop']),
            $entry['description']
        ));
    }
}
