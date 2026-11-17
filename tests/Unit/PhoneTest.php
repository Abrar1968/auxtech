<?php

use App\Support\Phone;

it('normalises Bangladeshi mobile numbers to E.164', function (string $input) {
    expect(Phone::normalize($input))->toBe('+8801805019415');
})->with(['01805019415', '+8801805019415', '8801805019415', '+880 1805-019415', '01805 019 415', '1805019415', '008801805019415']);

it('rejects numbers that are not Bangladeshi mobiles', function (?string $input) {
    expect(Phone::normalize($input))->toBeNull();
})->with(['0180501941', '018050194155', '01205019415', '02-9876543', 'abc', '', null]);
