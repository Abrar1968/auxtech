<?php

namespace App\Models\Concerns;

use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Image handling shared by every model with uploads. Conversions run
 * during the upload request because the host has no queue worker.
 */
trait HasImages
{
    use InteractsWithMedia;

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(480)->format('webp')->quality(78)->nonQueued();
        $this->addMediaConversion('medium')->width(960)->format('webp')->quality(78)->nonQueued();
        $this->addMediaConversion('large')->width(1600)->format('webp')->quality(80)->nonQueued();
    }

    /** @return array{url: string, thumb: string, medium: string, large: string, alt: string, width: int|null, height: int|null}|null */
    public function image(string $collection): ?array
    {
        $media = $this->getFirstMedia($collection);

        return $media ? self::mediaToArray($media) : null;
    }

    /** @return list<array<string, mixed>> */
    public function images(string $collection): array
    {
        return $this->getMedia($collection)->map(fn (Media $m) => self::mediaToArray($m))->values()->all();
    }

    /** @return array<string, mixed> */
    public static function mediaToArray(Media $media): array
    {
        $alt = $media->getCustomProperty('alt', []);
        $conversion = fn (string $name): string => $media->hasGeneratedConversion($name) ? $media->getUrl($name) : $media->getUrl();

        return [
            'id' => $media->id,
            'url' => url($media->getUrl()),
            'thumb' => url($conversion('thumb')),
            'medium' => url($conversion('medium')),
            'large' => url($conversion('large')),
            'alt' => is_array($alt) ? (string) ($alt[app()->getLocale()] ?? $alt['en'] ?? '') : (string) $alt,
            'width' => $media->getCustomProperty('width'),
            'height' => $media->getCustomProperty('height'),
        ];
    }
}
