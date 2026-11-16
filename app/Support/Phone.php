<?php

namespace App\Support;

/**
 * Bangladeshi mobile numbers: 01XXXXXXXXX with operator digit 3-9,
 * stored in E.164 (+8801XXXXXXXXX).
 */
final class Phone
{
    public static function normalize(?string $input): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $input) ?? '';

        if (str_starts_with($digits, '00880')) {
            $digits = substr($digits, 2);
        }

        if (str_starts_with($digits, '880')) {
            $digits = '0'.substr($digits, 3);
        }

        if (strlen($digits) === 10 && str_starts_with($digits, '1')) {
            $digits = '0'.$digits;
        }

        return preg_match('/^01[3-9]\d{8}$/', $digits) === 1 ? '+88'.$digits : null;
    }

    public static function isValid(?string $input): bool
    {
        return self::normalize($input) !== null;
    }
}
