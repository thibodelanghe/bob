<?php

use App\Toggl\Clients\Toggl;
use App\Toggl\Collections\TimeEntryCollection;
use App\Toggl\Exceptions\NonValidDateRangeException;
use App\Toggl\Repositories\TimeEntryRepository;
use Illuminate\Support\Carbon;
use Mockery\MockInterface;
use Pest\Expectation;
use Tests\TestCase;

uses(TestCase::class);

it('returns time entries between date', function () {
    $this->app->instance(Toggl::class, Mockery::mock(Toggl::class, function (MockInterface $m) {
        $m->shouldReceive('get')
            ->withArgs(function ($url, $dates) {
                return $url === 'me/time_entries' &&
                    $dates['start_date'] ===  '2022-12-01' &&
                    $dates['end_date'] ===  '2022-12-04';
            })
            ->andReturn([
                [
                    'id' => 1,
                    'start' => '2022-12-01T06:57:57+00:00',
                    'stop' => '2022-12-01T06:57:57+00:00',
                    'description' => 'test',
                ],
            ]);
    }));

    /** @var TimeEntryRepository $repository */
    $repository = app(TimeEntryRepository::class);

    $start = Carbon::parse('2022-12-01');
    $end = Carbon::parse('2022-12-04');

    expect($repository->betweenDate($start, $end))
        ->toBeInstanceOf(TimeEntryCollection::class)
        ->toHaveCount(1)
        ->sequence(fn (Expectation $expect) => $expect
            ->id->toBe(1)
            ->start->toBeInstanceOf(Carbon::class)
            ->end->toBeInstanceOf(Carbon::class)
            ->description->toBe('test')
        );
});

it('throws exception when start date is after end date', function () {
    /** @var TimeEntryRepository $repository */
    $repository = app(TimeEntryRepository::class);

    $start = Carbon::parse('2022-12-04');
    $end = Carbon::parse('2022-12-01');

    $repository->betweenDate($start, $end);
})->expectException(NonValidDateRangeException::class);
