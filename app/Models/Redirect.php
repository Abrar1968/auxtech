<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Redirect extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public const CACHE_KEY = 'redirects.map';

    protected function casts(): array
    {
        return ['last_hit_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /** Create a 301 from an old path and collapse chains that pointed at it. */
    public static function remember(string $from, string $to): void
    {
        $from = '/'.ltrim($from, '/');
        $to = '/'.ltrim($to, '/');

        if ($from === $to) {
            return;
        }

        static::query()->where('to_url', $from)->update(['to_url' => $to]);
        static::query()->where('from_path', $to)->delete();
        static::query()->updateOrCreate(['from_path' => $from], ['to_url' => $to, 'status_code' => 301]);
        Cache::forget(self::CACHE_KEY);
    }

    /** @return array<string, array{0: string, 1: int}> */
    public static function map(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => static::query()
            ->get(['from_path', 'to_url', 'status_code'])
            ->mapWithKeys(fn (self $r) => [$r->from_path => [$r->to_url, (int) $r->status_code]])
            ->all());
    }
}
