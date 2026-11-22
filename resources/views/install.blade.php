<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Install Auxtech</title>
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <style>
        :root { --brand: #ffc93c; --ink: #232428; --muted: #5c5f66; --line: #d9dadc; --ok: #15803d; --bad: #b91c1c; }
        * { box-sizing: border-box; }
        body { margin: 0; font: 16px/1.55 system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; color: var(--ink); background: #f3f3f4; }
        main { max-width: 680px; margin: 0 auto; padding: 32px 16px 64px; }
        img { height: 30px; width: auto; }
        h1 { font-size: 26px; margin: 28px 0 6px; }
        h2 { font-size: 17px; margin: 0 0 14px; }
        p { margin: 0 0 12px; color: var(--muted); }
        section { background: #fff; border: 1px solid var(--line); border-radius: 16px; padding: 22px; margin-top: 18px; }
        ul { list-style: none; margin: 0; padding: 0; }
        li { padding: 6px 0; border-bottom: 1px solid #eef1f6; font-size: 14px; }
        li:last-child { border: 0; }
        .ok::before { content: "\2713  "; color: var(--ok); font-weight: 700; }
        .bad { color: var(--bad); }
        .bad::before { content: "\2717  "; font-weight: 700; }
        .bad small { display: block; color: var(--muted); margin-left: 20px; }
        label { display: block; font-size: 14px; font-weight: 600; margin: 14px 0 4px; }
        input { width: 100%; min-height: 44px; padding: 10px 12px; border: 1px solid #c5ccda; border-radius: 10px; font: inherit; }
        input:focus { outline: 2px solid var(--ink); outline-offset: 1px; border-color: var(--ink); }
        .row { display: grid; grid-template-columns: 1fr 120px; gap: 12px; }
        .hint { font-size: 13px; color: var(--muted); margin-top: 4px; }
        button, .button { display: inline-block; margin-top: 22px; min-height: 48px; padding: 12px 26px; border: 0; border-radius: 999px; background: var(--brand); color: var(--ink); font: inherit; font-weight: 600; cursor: pointer; text-decoration: none; }
        button[disabled] { opacity: .5; cursor: not-allowed; }
        .errors { background: #fef2f2; border: 1px solid #fecaca; color: var(--bad); border-radius: 12px; padding: 12px 16px; margin-top: 18px; }
        .errors p { color: inherit; margin: 4px 0; }
        @media (max-width: 480px) { .row { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<main>
    <img src="/brand/auxtech-logo.svg" alt="Auxtech" width="1125" height="174">

    @if ($done)
        <h1>Your website is installed</h1>
        <p>The database is ready and your admin account ({{ $adminEmail }}) has been created.</p>
        <section>
            <h2>What to do next</h2>
            <ul>
                <li class="ok">Sign in to the admin panel and review Settings.</li>
                <li class="ok">Add your statistics, testimonials and prices: those sections stay hidden until you do.</li>
                <li class="ok">This installer has now switched itself off.</li>
            </ul>
            <a class="button" href="{{ $loginUrl }}">Open the admin panel</a>
        </section>
    @else
        <h1>Install your website</h1>
        <p>This takes about two minutes. You need a MySQL database and user, created in cPanel &rarr; MySQL Databases.</p>

        @if ($errors)
            <div class="errors" role="alert">
                @foreach ($errors as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <section>
            <h2>1. Server check</h2>
            <ul>
                @foreach ($checks as $check)
                    <li class="{{ $check['ok'] ? 'ok' : 'bad' }}">{{ $check['label'] }}@unless ($check['ok'])<small>{{ $check['hint'] }}</small>@endunless</li>
                @endforeach
            </ul>
        </section>

        <form method="post" action="/install" autocomplete="off">
            <section>
                <h2>2. Website address</h2>
                <label for="app_url">Address of this website</label>
                <input id="app_url" name="app_url" type="url" required value="{{ $old['app_url'] ?? '' }}">
                <div class="hint">Use https:// once your SSL certificate is active.</div>
            </section>

            <section>
                <h2>3. Database</h2>
                <div class="row">
                    <div>
                        <label for="db_host">Database host</label>
                        <input id="db_host" name="db_host" required value="{{ $old['db_host'] ?? 'localhost' }}">
                    </div>
                    <div>
                        <label for="db_port">Port</label>
                        <input id="db_port" name="db_port" type="number" required value="{{ $old['db_port'] ?? 3306 }}">
                    </div>
                </div>
                <label for="db_name">Database name</label>
                <input id="db_name" name="db_name" required value="{{ $old['db_name'] ?? '' }}">
                <label for="db_user">Database user</label>
                <input id="db_user" name="db_user" required value="{{ $old['db_user'] ?? '' }}">
                <label for="db_password">Database password</label>
                <input id="db_password" name="db_password" type="password">
            </section>

            <section>
                <h2>4. Your admin account</h2>
                <label for="admin_name">Your name</label>
                <input id="admin_name" name="admin_name" required value="{{ $old['admin_name'] ?? '' }}">
                <label for="admin_email">Email (used to sign in)</label>
                <input id="admin_email" name="admin_email" type="email" required value="{{ $old['admin_email'] ?? '' }}">
                <label for="admin_password">Password</label>
                <input id="admin_password" name="admin_password" type="password" required minlength="10">
                <div class="hint">At least 10 characters.</div>
                <label for="admin_password_confirmation">Repeat password</label>
                <input id="admin_password_confirmation" name="admin_password_confirmation" type="password" required minlength="10">
            </section>

            <section>
                <h2>5. Email notifications (optional)</h2>
                <p>To receive each new lead at {{ config('auxtech.company.email') }}, paste a Google App Password for that Gmail account. You can also add it later in Admin &rarr; Settings.</p>
                <label for="mail_password">Google App Password</label>
                <input id="mail_password" name="mail_password" type="password">
                <div class="hint">Google Account &rarr; Security &rarr; 2-Step Verification &rarr; App passwords.</div>
            </section>

            <button type="submit" @disabled(! $ready)>Install</button>
        </form>
    @endif
</main>
</body>
</html>
