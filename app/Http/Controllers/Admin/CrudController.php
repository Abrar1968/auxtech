<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Content\SaveRecord;
use App\Admin\Field;
use App\Admin\Module;
use App\Admin\ModuleRegistry;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CrudRequest;
use App\Support\SiteCache;
use BackedEnum;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * One controller for every admin CRUD module (see App\Admin\Module).
 */
class CrudController extends Controller
{
    public function __construct(private readonly ModuleRegistry $modules) {}

    public function index(Request $request, string $module): Response
    {
        $definition = $this->module($request, $module, 'view');
        [$sortColumn, $sortDirection] = $definition->defaultSort();

        $query = $definition->query()->with($definition->with());

        if ($definition->softDeletes() && $request->boolean('trashed')) {
            $query->onlyTrashed();
        }

        if (($search = trim((string) $request->query('search'))) !== '' && $definition->searchable() !== []) {
            $query->where(function (Builder $q) use ($definition, $search): void {
                foreach ($definition->searchable() as $column) {
                    $q->orWhere($column, 'like', '%'.addcslashes($search, '%_').'%');
                }
            });
        }

        foreach ($definition->filters() as $name => $options) {
            $value = $request->query($name);

            if (is_string($value) && array_key_exists($value, $options)) {
                $query->where($name, $value);
            }
        }

        $records = $query->orderBy($sortColumn, $sortDirection)->paginate(25)->withQueryString();

        return Inertia::render('Admin/Crud/Index', [
            'module' => $this->describe($request, $definition),
            'columns' => collect($definition->columns())->map(fn ($label, $name) => ['name' => $name, 'label' => $label])->values(),
            'filters' => $definition->filters(),
            'query' => $request->only(['search', 'trashed', ...array_keys($definition->filters())]),
            'records' => $records->through(fn (Model $record) => $this->row($definition, $record)),
        ]);
    }

    public function create(Request $request, string $module): Response
    {
        $definition = $this->module($request, $module, 'create');

        return $this->form($request, $definition, null);
    }

    public function store(CrudRequest $request, SaveRecord $save): RedirectResponse
    {
        $record = $save->handle($request->module(), $request->validated(), null, $request->user());

        return redirect()
            ->route('admin.crud.edit', [$request->module()->key(), $record->getKey()])
            ->with('success', $request->module()->singular().' created.');
    }

    public function edit(Request $request, string $module, int $record): Response
    {
        $definition = $this->module($request, $module, 'view');

        return $this->form($request, $definition, $this->find($definition, $record));
    }

    public function update(CrudRequest $request, SaveRecord $save): RedirectResponse
    {
        $save->handle($request->module(), $request->validated(), $request->record(), $request->user());

        return back()->with('success', $request->module()->singular().' saved.');
    }

    public function destroy(Request $request, string $module, int $record): RedirectResponse
    {
        $definition = $this->module($request, $module, 'delete');
        $model = $this->find($definition, $record);

        if ($reason = $definition->deletionBlocker($model)) {
            return back()->with('error', $reason);
        }

        $model->delete();

        activity()->causedBy($request->user())->performedOn($model)->event('deleted')
            ->withProperties(['module' => $definition->key()])->log('Deleted '.$definition->singular());

        SiteCache::flush();

        return redirect()->route('admin.crud.index', $definition->key())->with('success', $definition->singular().' deleted.');
    }

    public function restore(Request $request, string $module, int $record): RedirectResponse
    {
        $definition = $this->module($request, $module, 'delete');
        abort_unless($definition->softDeletes(), 404);

        $this->find($definition, $record)->restore();
        SiteCache::flush();

        return back()->with('success', $definition->singular().' restored.');
    }

    public function reorder(Request $request, string $module): RedirectResponse
    {
        $definition = $this->module($request, $module, 'update');
        abort_unless($definition->isSortable(), 404);

        $ids = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']])['ids'];
        $definition->model()::reorder($ids);
        SiteCache::flush();

        return back()->with('success', 'Order saved.');
    }

    private function module(Request $request, string $key, string $ability): Module
    {
        abort_unless($this->modules->has($key), 404);
        $module = $this->modules->get($key);
        abort_unless($request->user()->can($module->permission().'.'.$ability), 403);

        return $module;
    }

    private function find(Module $module, int $id): Model
    {
        return $module->query()
            ->when($module->softDeletes(), fn ($q) => $q->withTrashed())
            ->with($module->with())
            ->findOrFail($id);
    }

    private function form(Request $request, Module $module, ?Model $record): Response
    {
        return Inertia::render('Admin/Crud/Form', [
            'module' => $this->describe($request, $module),
            'fields' => array_map(fn (Field $f) => $f->toArray(), $module->fields()),
            'record' => $record ? $this->values($module, $record) : null,
            'defaults' => collect($module->fields())->mapWithKeys(fn (Field $f) => [$f->name => $f->default])->all(),
        ]);
    }

    /** @return array<string, mixed> */
    private function describe(Request $request, Module $module): array
    {
        $user = $request->user();

        return [
            'key' => $module->key(),
            'label' => $module->label(),
            'singular' => $module->singular(),
            'sortable' => $module->isSortable(),
            'softDeletes' => $module->softDeletes(),
            'hasSeo' => $module->hasSeo(),
            'can' => [
                'create' => $user->can($module->permission().'.create'),
                'update' => $user->can($module->permission().'.update'),
                'delete' => $user->can($module->permission().'.delete'),
            ],
        ];
    }

    /** @return array<string, mixed> one table row */
    private function row(Module $module, Model $record): array
    {
        $row = ['id' => $record->getKey(), 'trashed' => $module->softDeletes() && $record->trashed()];

        foreach (array_keys($module->columns()) as $column) {
            $row[$column] = $this->scalar(data_get($record, $column));
        }

        return $row;
    }

    /** @return array<string, mixed> form values for an existing record */
    private function values(Module $module, Model $record): array
    {
        $values = ['id' => $record->getKey()];

        foreach ($module->fields() as $field) {
            if ($field->virtual) {
                continue;
            }

            $values[$field->name] = match (true) {
                $field->type === 'password' => null,
                $field->type === 'date' => $record->getAttribute($field->name)?->format('Y-m-d'),
                $field->type === 'image' => $record->image($field->name),
                $field->type === 'images' => $record->images($field->name),
                $field->isRelationSync() => $record->{$field->name}()->pluck($record->{$field->name}()->getRelated()->getQualifiedKeyName())->all(),
                $field->translatable => $record->getTranslations($field->name) + ['en' => null, 'bn' => null],
                default => $this->scalar($record->getAttribute($field->name), false),
            };
        }

        $values = array_merge($values, $module->extraValues($record));

        if ($module->hasSeo()) {
            $seo = $record->seo;
            $values['seo'] = [
                'title' => ($seo?->getTranslations('title') ?? []) + ['en' => null, 'bn' => null],
                'description' => ($seo?->getTranslations('description') ?? []) + ['en' => null, 'bn' => null],
                'canonical' => $seo?->canonical,
                'robots' => $seo?->robots,
            ];
        }

        if (method_exists($record, 'publicPath')) {
            $values['public_url'] = url($record->publicPath());
        }

        return $values;
    }

    private function scalar(mixed $value, bool $forTable = true): mixed
    {
        return match (true) {
            $value instanceof BackedEnum => $value->value,
            $value instanceof DateTimeInterface => $value->format($forTable ? 'Y-m-d H:i' : 'Y-m-d\TH:i'),
            is_array($value) && $forTable => tr($value) ?? '',
            default => $value,
        };
    }
}
