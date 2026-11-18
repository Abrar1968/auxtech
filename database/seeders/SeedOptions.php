<?php

namespace Database\Seeders;

/** Options for re-running the content seeders on an existing site. */
final class SeedOptions
{
    /** Replace seeded text that was edited in the admin panel (off by default). */
    public static bool $overwrite = false;
}
