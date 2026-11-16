<?php

namespace App\Http\Controllers\Admin;

use App\Admin\ModuleRegistry;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;

class ReportController extends Controller
{
    public function activity(Request $request): Response
    {
        abort_unless($request->user()->can('activity.view'), 403);

        return Inertia::render('Admin/Activity', [
            'entries' => Activity::query()->with('causer')->latest('id')->paginate(40)->through(fn (Activity $a) => [
                'id' => $a->id,
                'description' => $a->description,
                'event' => $a->event,
                'subject' => $a->subject_type ? class_basename($a->subject_type).' #'.$a->subject_id : null,
                'user' => $a->causer?->name,
                'properties' => $a->properties,
                'created_at' => $a->created_at?->toIso8601String(),
            ]),
        ]);
    }

    /** Published records that are missing search metadata or image alt text. */
    public function seo(Request $request, ModuleRegistry $modules): Response
    {
        abort_unless($request->user()->can('landing-pages.view'), 403);

        $issues = [];

        foreach ($modules->all() as $module) {
            if (! $module->hasSeo() || ! method_exists($module->model(), 'scopePublished')) {
                continue;
            }

            foreach ($module->query()->published()->with('seo')->get() as $record) {
                $problems = [];
                $descriptionSource = collect(['excerpt', 'summary', 'tagline', 'intro'])
                    ->first(fn (string $attribute) => in_array($attribute, $record->translatable ?? [], true) && filled($record->getTranslation($attribute, 'en', false)));

                if (! $record->seo?->getTranslation('description', 'en', false) && ! $descriptionSource) {
                    $problems[] = 'No meta description';
                }

                if (method_exists($record, 'getFirstMedia') && $record->getFirstMedia('cover') && blank($record->getFirstMedia('cover')->getCustomProperty('alt.en'))) {
                    $problems[] = 'Cover image has no alt text';
                }

                if ($problems !== []) {
                    $issues[] = [
                        'module' => $module->key(),
                        'id' => $record->getKey(),
                        'title' => tr($record->getTranslations($record->translatable[0])),
                        'problems' => $problems,
                    ];
                }
            }
        }

        return Inertia::render('Admin/SeoReport', ['issues' => $issues]);
    }
}
