<?php

namespace App\Http\Requests;

use App\Enums\LeadSource;
use App\Support\Phone;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, list<mixed>> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'phone' => ['required', 'string', 'max:25', function (string $attribute, mixed $value, Closure $fail): void {
                if (! Phone::isValid(is_string($value) ? $value : null)) {
                    $fail(__('Enter a valid Bangladeshi mobile number, e.g. 01805019415.'));
                }
            }],
            'email' => ['nullable', 'email', 'max:255'],
            'company' => ['nullable', 'string', 'max:160'],
            'message' => ['nullable', 'string', 'max:3000'],
            'budget_range' => ['nullable', 'string', 'max:60'],
            'source' => ['nullable', Rule::in([LeadSource::ContactForm->value, LeadSource::Estimator->value, LeadSource::DemoRequest->value])],
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'solution_id' => ['nullable', 'integer', 'exists:solutions,id'],
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'meta' => ['nullable', 'array', 'max:30'],
            'meta.*' => ['nullable', 'max:500'],
            'page_url' => ['nullable', 'string', 'max:500'],
            'locale' => ['nullable', 'string', 'max:5'],
            // Spam traps: a hidden field bots fill in, and the time the form was opened.
            'website' => ['nullable', 'string', 'max:255'],
            'started_at' => ['nullable', 'integer'],
        ];
    }

    /** Bots fill the hidden field or submit faster than a person can type. */
    public function looksLikeSpam(): bool
    {
        if (filled($this->input('website'))) {
            return true;
        }

        $started = (int) $this->input('started_at', 0);

        return $started > 0 && (now()->getTimestampMs() - $started) < 2000;
    }
}
