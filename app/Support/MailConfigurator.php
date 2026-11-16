<?php

namespace App\Support;

/**
 * Applies the mail settings saved in the admin panel (Gmail SMTP with an
 * app password) to the mailer at runtime.
 */
final class MailConfigurator
{
    public static function configured(): bool
    {
        return filled(setting('mail.password')) || config('mail.default') !== 'smtp' && ! app()->isProduction();
    }

    public static function apply(): void
    {
        $password = setting('mail.password');
        $address = (string) setting('mail.username', setting('company.email'));

        config([
            'mail.from.address' => $address,
            'mail.from.name' => setting('mail.from_name', 'Auxtech Website'),
        ]);

        if (! filled($password)) {
            return;
        }

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => setting('mail.host', 'smtp.gmail.com'),
            'mail.mailers.smtp.port' => (int) setting('mail.port', 587),
            'mail.mailers.smtp.username' => $address,
            'mail.mailers.smtp.password' => $password,
            'mail.mailers.smtp.scheme' => null,
        ]);

        app('mail.manager')->forgetMailers();
    }
}
