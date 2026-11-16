<?php

namespace App\Enums;

enum LeadSource: string
{
    case ContactForm = 'contact_form';
    case Estimator = 'estimator';
    case DemoRequest = 'demo_request';
    case WhatsApp = 'whatsapp';
    case Email = 'email';

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
