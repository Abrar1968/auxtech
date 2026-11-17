<?php

namespace App\Support;

/**
 * Files earlier releases shipped that must not survive an update. A new zip is
 * extracted over the old site, which adds and replaces files but never deletes
 * them, so the database update in Admin > System removes these.
 */
class ObsoleteFiles
{
    /** Paths relative to the project root. */
    public const PATHS = [
        // Laravel's stock file; the web server served it instead of the generated robots.txt.
        'public/robots.txt',
    ];

    /** @return list<string> the paths that were removed */
    public static function remove(): array
    {
        $removed = [];

        foreach (self::PATHS as $path) {
            $file = base_path($path);

            if (is_file($file) && @unlink($file)) {
                $removed[] = $path;
            }
        }

        return $removed;
    }
}
