<?php

namespace App\Actions\Content;

use App\Admin\Field;
use App\Admin\Module;
use App\Models\User;
use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Mews\Purifier\Facades\Purifier;

/**
 * Creates or updates a record of any admin module from validated input:
 * attributes, translations, relations, images and SEO overrides.
 */
class SaveRecord
{
    /** @param array<string, mixed> $input validated request data */
    public function handle(Module $module, array $input, ?Model $record = null, ?User $actor = null): Model
    {
        return DB::transaction(function () use ($module, $input, $record, $actor): Model {
            $input = $module->beforeSave($input, $record);
            $creating = $record === null;
            $record ??= new ($module->model());

            foreach ($module->fields() as $field) {
                if ($field->virtual || $field->isMedia() || $field->isRelationSync() || ! array_key_exists($field->name, $input)) {
                    continue;
                }

                $this->fill($record, $field, $input[$field->name]);
            }

            $changes = Arr::except($record->getDirty(), ['password']);
            $record->save();

            foreach ($module->fields() as $field) {
                if ($field->isRelationSync() && array_key_exists($field->name, $input)) {
                    $record->{$field->name}()->sync($input[$field->name] ?? []);
                }

                if ($field->isMedia()) {
                    $this->media($record, $field, $input);
                }
            }

            if ($module->hasSeo() && array_key_exists('seo', $input)) {
                $this->seo($record, (array) $input['seo']);
            }

            $module->afterSave($record, $input);

            activity()
                ->causedBy($actor)
                ->performedOn($record)
                ->event($creating ? 'created' : 'updated')
                ->withProperties(['module' => $module->key(), 'changed' => array_keys($changes)])
                ->log(($creating ? 'Created ' : 'Updated ').$module->singular());

            SiteCache::flush();

            return $record;
        });
    }

    private function fill(Model $record, Field $field, mixed $value): void
    {
        if ($field->type === 'password') {
            if (filled($value)) {
                $record->setAttribute($field->name, $value);
            }

            return;
        }

        if ($field->translatable) {
            $translations = [];

            foreach ((array) $value as $locale => $text) {
                if (in_array($locale, config('auxtech.locales.supported'), true) && filled($text)) {
                    $translations[$locale] = $this->clean($field, (string) $text);
                }
            }

            $record->setTranslations($field->name, $translations);

            return;
        }

        $record->setAttribute($field->name, match ($field->type) {
            'toggle' => (bool) $value,
            'repeater' => array_values(array_filter((array) $value, 'is_array')),
            'text', 'textarea', 'richtext' => $value === null ? null : $this->clean($field, (string) $value),
            'slug' => $value ?: null,
            default => $value === '' ? null : $value,
        });
    }

    private function clean(Field $field, string $text): string
    {
        return $field->type === 'richtext'
            ? Purifier::clean($text)
            : trim(strip_tags($text));
    }

    /** @param array<string, mixed> $input */
    private function media(Model $record, Field $field, array $input): void
    {
        $collection = $field->name;
        $files = Arr::wrap($input[$collection] ?? []);

        if ($field->type === 'image' && ! empty($input[$collection.'_remove']) && $files === []) {
            $record->clearMediaCollection($collection);
        }

        if ($field->type === 'images') {
            $record->media()->where('collection_name', $collection)
                ->whereIn('id', (array) ($input[$collection.'_remove'] ?? []))
                ->get()->each->delete();
        }

        foreach ($files as $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            [$width, $height] = @getimagesize($file->getRealPath()) ?: [null, null];

            $record->addMedia($file)
                ->withCustomProperties(['width' => $width, 'height' => $height, 'alt' => (array) ($input[$collection.'_alt'] ?? [])])
                ->toMediaCollection($collection);
        }

        // Alt text can be edited without uploading a new file.
        if ($field->type === 'image' && $files === [] && array_key_exists($collection.'_alt', $input)) {
            $media = $record->getFirstMedia($collection);
            $media?->setCustomProperty('alt', (array) $input[$collection.'_alt'])->save();
        }

        $record->unsetRelation('media');
    }

    /** @param array<string, mixed> $seo */
    private function seo(Model $record, array $seo): void
    {
        $title = array_filter((array) ($seo['title'] ?? []), 'filled');
        $description = array_filter((array) ($seo['description'] ?? []), 'filled');

        if ($title === [] && $description === [] && empty($seo['canonical']) && empty($seo['robots'])) {
            $record->seo()->delete();
            $record->unsetRelation('seo');

            return;
        }

        $meta = $record->seo()->firstOrNew();
        $meta->setTranslations('title', array_map('strip_tags', $title));
        $meta->setTranslations('description', array_map('strip_tags', $description));
        $meta->canonical = $seo['canonical'] ?? null;
        $meta->robots = $seo['robots'] ?? null;
        $meta->save();
        $record->unsetRelation('seo');
    }
}
