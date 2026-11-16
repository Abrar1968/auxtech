<?php

namespace App\Admin;

use App\Models\Concerns\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Describes one admin CRUD screen: its model, form fields, list columns
 * and hooks. One CrudController serves every module.
 */
abstract class Module
{
    /** URL segment and permission prefix, e.g. "projects". */
    abstract public function key(): string;

    /** @return class-string<Model> */
    abstract public function model(): string;

    /** @return list<Field> */
    abstract public function fields(): array;

    public function label(): string
    {
        return str($this->key())->replace('-', ' ')->title()->toString();
    }

    public function singular(): string
    {
        return str($this->label())->singular()->toString();
    }

    public function group(): string
    {
        return 'Content';
    }

    /** Permission prefix; several modules may share one. */
    public function permission(): string
    {
        return $this->key();
    }

    /** @return array<string, string> attribute (dot path allowed) => column heading */
    public function columns(): array
    {
        return ['id' => 'ID'];
    }

    /** @return list<string> columns searched with LIKE */
    public function searchable(): array
    {
        return [];
    }

    /** @return list<string> */
    public function with(): array
    {
        return [];
    }

    /** @return array<string, array<int|string, string>> filter name => options */
    public function filters(): array
    {
        return [];
    }

    /** @return array{0: string, 1: string} */
    public function defaultSort(): array
    {
        return $this->isSortable() ? ['sort_order', 'asc'] : ['id', 'desc'];
    }

    public function query(): Builder
    {
        return $this->model()::query();
    }

    public function isSortable(): bool
    {
        return in_array(Sortable::class, class_uses_recursive($this->model()), true);
    }

    public function hasSeo(): bool
    {
        return method_exists($this->model(), 'seo');
    }

    public function softDeletes(): bool
    {
        return in_array(SoftDeletes::class, class_uses_recursive($this->model()), true);
    }

    /**
     * Adjust validated data before it is written.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function beforeSave(array $data, ?Model $record): array
    {
        return $data;
    }

    /** @param array<string, mixed> $data */
    public function afterSave(Model $record, array $data): void {}

    /** @return array<string, mixed> form values for virtual fields */
    public function extraValues(Model $record): array
    {
        return [];
    }

    /** A reason the record cannot be deleted, or null when it can. */
    public function deletionBlocker(Model $record): ?string
    {
        return null;
    }

    /** @return array<string, list<mixed>> extra validation rules */
    public function rules(?Model $record): array
    {
        return [];
    }

    public function field(string $name): ?Field
    {
        foreach ($this->fields() as $field) {
            if ($field->name === $name) {
                return $field;
            }
        }

        return null;
    }
}
