<?php

use App\Http\Controllers\Site\HomeController;

/* The hero headline: words in *stars* are yellow; without stars, the first two words. */
it('colours the starred words', function () {
    expect(HomeController::titleParts('*We engineer* software that runs real businesses.'))->toBe([
        ['text' => 'We engineer', 'accent' => true],
        ['text' => 'software that runs real businesses.', 'accent' => false],
    ])->and(HomeController::plainTitle('*We engineer* software'))->toBe('We engineer software');
});

it('colours the first two words when nothing is starred', function () {
    expect(HomeController::titleParts('We engineer software'))->toBe([
        ['text' => 'We engineer', 'accent' => true],
        ['text' => 'software', 'accent' => false],
    ])->and(HomeController::titleParts(''))->toBe([]);
});
