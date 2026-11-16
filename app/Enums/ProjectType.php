<?php

namespace App\Enums;

enum ProjectType: string
{
    case Live = 'live';
    case Demo = 'demo';
    case Product = 'product';
}
