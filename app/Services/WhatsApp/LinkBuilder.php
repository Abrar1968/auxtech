<?php

namespace App\Services\WhatsApp;

/**
 * Builds wa.me links with a message pre-filled for the page the visitor
 * is on. Templates are edited in Admin > Settings.
 */
class LinkBuilder
{
    public function number(): string
    {
        return preg_replace('/\D+/', '', (string) setting('whatsapp.number', config('auxtech.whatsapp.number'))) ?? '';
    }

    public function url(string $template = 'default', ?string $name = null, ?string $locale = null): string
    {
        return 'https://wa.me/'.$this->number().'?text='.rawurlencode($this->message($template, $name, $locale));
    }

    public function message(string $template = 'default', ?string $name = null, ?string $locale = null): string
    {
        $text = tr(setting('whatsapp.templates.'.$template), $locale)
            ?? tr(setting('whatsapp.templates.default'), $locale)
            ?? '';

        return trim(str_replace(':name', (string) $name, $text));
    }

    /** @return array<string, string> every template in the current locale, for the frontend */
    public function templates(?string $locale = null): array
    {
        $templates = [];

        foreach (['default', 'service', 'solution', 'project', 'plan', 'estimator'] as $key) {
            $templates[$key] = (string) tr(setting('whatsapp.templates.'.$key), $locale);
        }

        return $templates;
    }
}
