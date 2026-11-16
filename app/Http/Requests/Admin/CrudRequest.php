<?php

namespace App\Http\Requests\Admin;

use App\Admin\Module;
use App\Admin\ModuleRegistry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates create/update for any admin module. Rules come from the
 * module's field definitions.
 */
class CrudRequest extends FormRequest
{
    private ?Model $resolved = null;

    public function module(): Module
    {
        return app(ModuleRegistry::class)->get((string) $this->route('module'));
    }

    public function record(): ?Model
    {
        $id = $this->route('record');

        if ($id === null) {
            return null;
        }

        return $this->resolved ??= $this->module()->query()
            ->when($this->module()->softDeletes(), fn ($q) => $q->withTrashed())
            ->findOrFail($id);
    }

    public function authorize(): bool
    {
        $ability = $this->module()->permission().($this->route('record') === null ? '.create' : '.update');

        return (bool) $this->user()?->can($ability);
    }

    /** @return array<string, list<mixed>> */
    public function rules(): array
    {
        $module = $this->module();
        $record = $this->record();
        $rules = [];

        foreach ($module->fields() as $field) {
            $rules += $field->validationRules($record);

            if ($field->type === 'password') {
                $rules[$field->name][0] = $record === null ? 'required' : 'nullable';
            }

            if ($field->type === 'image') {
                $rules[$field->name.'_remove'] = ['nullable', 'boolean'];
                $rules[$field->name.'_alt'] = ['nullable', 'array'];
                $rules[$field->name.'_alt.*'] = ['nullable', 'string', 'max:255'];
            }

            if ($field->type === 'images') {
                $rules[$field->name.'_remove'] = ['nullable', 'array'];
                $rules[$field->name.'_remove.*'] = ['integer'];
            }
        }

        if ($module->hasSeo()) {
            $rules += [
                'seo' => ['nullable', 'array'],
                'seo.title' => ['nullable', 'array'],
                'seo.title.*' => ['nullable', 'string', 'max:70'],
                'seo.description' => ['nullable', 'array'],
                'seo.description.*' => ['nullable', 'string', 'max:170'],
                'seo.canonical' => ['nullable', 'url:http,https', 'max:255'],
                'seo.robots' => ['nullable', 'in:index\, follow,noindex\, follow,noindex\, nofollow'],
            ];
        }

        return array_merge($rules, $module->rules($record));
    }
}
