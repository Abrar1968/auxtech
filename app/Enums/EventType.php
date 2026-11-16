<?php

namespace App\Enums;

enum EventType: string
{
    case WhatsAppClick = 'whatsapp_click';
    case EmailClick = 'email_click';
    case DemoClick = 'demo_click';
    case FormStart = 'form_start';
    case FormSubmit = 'form_submit';
    case EstimatorStep = 'estimator_step';

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
