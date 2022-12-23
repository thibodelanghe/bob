<?php


use App\Toggl\Clients\Toggl;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class);

it('GET returns the given response as an array', function () {
    Http::fake([
        '*/test-path?filter=test-filter' => Http::response([
            'test-response'
        ]),
    ]);

    /** @var Toggl $toggl */
    $toggl = app(Toggl::class);

    $response = $toggl->get('test-path', [
        'filter' => 'test-filter',
    ]);

    expect($response)
        ->toBeArray()
        ->toHaveCount(1)
        ->toContain('test-response');
});

it('GET throws an exception when Toggl api throws an error', function () {
    Http::fake([
        '*/test-path?filter=test-filter' => Http::response([], 401),
    ]);

    /** @var Toggl $toggl */
    $toggl = app(Toggl::class);

    $toggl->get('test-path', [
        'filter' => 'test-filter',
    ]);
})->expectException(RequestException::class);

it('POST returns the given response as an array', function () {
    Http::fake([
        '*/test-path' => Http::response([
            'test-response'
        ], 201),
    ]);

    /** @var Toggl $toggl */
    $toggl = app(Toggl::class);

    $response = $toggl->post('test-path', [
        'name' => 'John Doe',
    ]);

    Http::assertSent(function (Request $request) {
        return $request->data()['name'] === 'John Doe';
    });

    expect($response)
        ->toBeArray()
        ->toHaveCount(1)
        ->toContain('test-response');
});

it('POST throws an exception when Toggl api throws an error', function () {
    Http::fake([
        '*/test-path?filter=test-filter' => Http::response([], 400),
    ]);

    /** @var Toggl $toggl */
    $toggl = app(Toggl::class);

    $toggl->get('test-path', [
        'filter' => 'test-filter',
    ]);
})->expectException(RequestException::class);
