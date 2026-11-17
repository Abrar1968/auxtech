<?php

namespace App\Admin;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

/**
 * One form field of an admin module. It describes itself to the admin UI
 * (toArray) and produces its own validation rules.
 */
final class Field
{
    public bool $translatable = false;

    public bool $required = false;

    public ?int $max = null;

    public ?string $help = null;

    public mixed $default = null;

    /** @var array<int|string, string>|Closure|null */
    public array|Closure|null $options = null;

    /** @var class-string<Model>|null */
    public ?string $related = null;

    public string $relatedLabel = 'name';

    /** @var list<Field> */
    public array $children = [];

    /** @var list<mixed> */
    public array $extraRules = [];

    public string $group = 'main';

    /** Not a column: validated and passed to the module's hooks only. */
    public bool $virtual = false;

    private function __construct(public string $name, public string $type, public string $label) {}

    public static function make(string $type, string $name, ?string $label = null): self
    {
        return new self($name, $type, $label ?? str($name)->replace('_id', '')->headline()->toString());
    }

    public static function text(string $name, ?string $label = null): self
    {
        return self::make('text', $name, $label)->max(255);
    }

    public static function textarea(string $name, ?string $label = null): self
    {
        return self::make('textarea', $name, $label)->max(5000);
    }

    public static function richtext(string $name, ?string $label = null): self
    {
        return self::make('richtext', $name, $label)->max(100000);
    }

    public static function slug(string $name = 'slug'): self
    {
        return self::make('slug', $name)->help('Leave empty to generate it from the title.')->side();
    }

    public static function number(string $name, ?string $label = null): self
    {
        return self::make('number', $name, $label);
    }

    public static function toggle(string $name, ?string $label = null): self
    {
        return self::make('toggle', $name, $label)->side();
    }

    public static function url(string $name, ?string $label = null): self
    {
        return self::make('url', $name, $label)->max(255);
    }

    public static function email(string $name, ?string $label = null): self
    {
        return self::make('email', $name, $label)->max(255);
    }

    public static function password(string $name = 'password'): self
    {
        return self::make('password', $name);
    }

    public static function date(string $name, ?string $label = null): self
    {
        return self::make('date', $name, $label);
    }

    public static function datetime(string $name, ?string $label = null): self
    {
        return self::make('datetime', $name, $label);
    }

    /** @param array<int|string, string>|Closure $options */
    public static function select(string $name, array|Closure $options, ?string $label = null): self
    {
        return self::make('select', $name, $label)->options($options);
    }

    /** @param class-string<Model> $related */
    public static function belongsTo(string $name, string $related, string $labelColumn = 'name', ?string $label = null): self
    {
        $field = self::make('select', $name, $label);
        $field->related = $related;
        $field->relatedLabel = $labelColumn;

        return $field->side();
    }

    /** @param class-string<Model> $related */
    public static function belongsToMany(string $relation, string $related, string $labelColumn = 'name', ?string $label = null): self
    {
        $field = self::make('multiselect', $relation, $label);
        $field->related = $related;
        $field->relatedLabel = $labelColumn;

        return $field;
    }

    /** A single image stored in the named media collection. */
    public static function image(string $collection, ?string $label = null): self
    {
        return self::make('image', $collection, $label)->side();
    }

    /** Several images stored in the named media collection. */
    public static function images(string $collection, ?string $label = null): self
    {
        return self::make('images', $collection, $label);
    }

    /** @param list<Field> $children rows of sub-fields stored as JSON */
    public static function repeater(string $name, array $children, ?string $label = null): self
    {
        $field = self::make('repeater', $name, $label);
        $field->children = $children;

        return $field;
    }

    /**
     * Draft / published + optional publish time, for Publishable models.
     *
     * @return list<Field>
     */
    public static function publishing(): array
    {
        return [
            self::select('status', ['draft' => 'Draft', 'published' => 'Published'])->required()->default('draft')->side(),
            self::datetime('published_at', 'Publish at')->help('Leave empty to publish immediately.')->side(),
        ];
    }

    public function translatable(): self
    {
        $this->translatable = true;

        return $this;
    }

    public function required(): self
    {
        $this->required = true;

        return $this;
    }

    public function max(int $max): self
    {
        $this->max = $max;

        return $this;
    }

    public function help(string $help): self
    {
        $this->help = $help;

        return $this;
    }

    public function default(mixed $value): self
    {
        $this->default = $value;

        return $this;
    }

    /** @param array<int|string, string>|Closure $options */
    public function options(array|Closure $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function rules(mixed ...$rules): self
    {
        $this->extraRules = [...$this->extraRules, ...$rules];

        return $this;
    }

    public function virtual(): self
    {
        $this->virtual = true;

        return $this;
    }

    public function side(): self
    {
        $this->group = 'side';

        return $this;
    }

    public function isMedia(): bool
    {
        return in_array($this->type, ['image', 'images'], true);
    }

    public function isRelationSync(): bool
    {
        return $this->type === 'multiselect' && $this->related !== null;
    }

    /** @return array<int|string, string> */
    public function resolveOptions(): array
    {
        if ($this->related !== null) {
            return $this->related::query()->get()->mapWithKeys(fn (Model $m) => [
                $m->getKey() => (string) (tr($this->rawLabel($m)) ?? '#'.$m->getKey()),
            ])->all();
        }

        return $this->options instanceof Closure ? ($this->options)() : ($this->options ?? []);
    }

    private function rawLabel(Model $model): mixed
    {
        return method_exists($model, 'getTranslations') && in_array($this->relatedLabel, $model->translatable ?? [], true)
            ? $model->getTranslations($this->relatedLabel)
            : $model->getAttribute($this->relatedLabel);
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function validationRules(?Model $record, string $prefix = ''): array
    {
        $key = $prefix.$this->name;
        $presence = $this->required ? 'required' : 'nullable';
        $string = array_filter(['string', $this->max ? 'max:'.$this->max : null]);

        $scalar = match ($this->type) {
            'text', 'textarea', 'richtext' => $string,
            'url' => ['url:http,https', ...$string],
            'email' => ['email', ...$string],
            'number' => ['numeric'],
            'toggle' => ['boolean'],
            'date', 'datetime' => ['date'],
            'password' => ['string', 'min:10', 'max:200'],
            'slug' => ['string', 'max:191', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'select' => $this->related
                ? ['integer', Rule::exists((new $this->related)->getTable(), 'id')]
                : [Rule::in(array_keys($this->resolveOptions()))],
            default => [],
        };

        if ($this->type === 'image') {
            return [$key => ['nullable', ...self::imageRules()]];
        }

        if ($this->type === 'images') {
            return [$key => ['nullable', 'array', 'max:20'], $key.'.*' => self::imageRules()];
        }

        if ($this->type === 'multiselect') {
            return [
                $key => ['nullable', 'array'],
                $key.'.*' => $this->related
                    ? ['integer', Rule::exists((new $this->related)->getTable(), 'id')]
                    : [Rule::in(array_keys($this->resolveOptions()))],
            ];
        }

        if ($this->type === 'repeater') {
            $rules = [$key => [$presence, 'array', 'max:60']];

            foreach ($this->children as $child) {
                $rules += $child->validationRules(null, $key.'.*.');
            }

            return $rules;
        }

        if ($this->type === 'toggle') {
            return [$key => ['boolean', ...$this->extraRules]];
        }

        if ($this->translatable) {
            return [
                $key => [$presence, 'array'],
                $key.'.en' => [$presence, ...$scalar, ...$this->extraRules],
                $key.'.bn' => ['nullable', ...$scalar],
            ];
        }

        return [$key => [$presence, ...$scalar, ...$this->extraRules]];
    }

    /**
     * Raster images only. The last rule opens the file, so a script renamed
     * to .jpg is rejected whatever its reported type.
     *
     * @return list<mixed>
     */
    public static function imageRules(): array
    {
        return ['file', 'mimes:jpg,jpeg,png,webp', 'max:6144', function (string $attribute, mixed $value, Closure $fail): void {
            if (! $value instanceof UploadedFile || @getimagesize($value->getRealPath()) === false) {
                $fail('The file must be a valid JPG, PNG or WebP image.');
            }
        }];
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'label' => $this->label,
            'translatable' => $this->translatable,
            'required' => $this->required,
            'max' => $this->max,
            'help' => $this->help,
            'default' => $this->default,
            'group' => $this->group,
            'options' => in_array($this->type, ['select', 'multiselect'], true)
                ? collect($this->resolveOptions())->map(fn ($label, $value) => ['value' => $value, 'label' => $label])->values()->all()
                : null,
            'children' => array_map(fn (Field $f) => $f->toArray(), $this->children),
        ];
    }
}
