<?php

use App\Enums\PublishStatus;
use App\Models\Client;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Redirect;
use App\Models\Service;
use App\Models\Stat;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Activitylog\Models\Activity;

beforeEach(function () {
    Storage::fake('public');
    $this->user = admin();
    $this->actingAs($this->user);
});

it('creates a project with translations, relations, an image and SEO overrides', function () {
    $service = service(['slug' => 'ecommerce-development']);
    $client = Client::query()->create(['name' => 'Gearzen']);

    $this->post('/admin/projects', [
        'title' => ['en' => 'Gearzen', 'bn' => 'গিয়ারজেন'],
        'tagline' => ['en' => 'Gadget store.'],
        'type' => 'live',
        'status' => 'published',
        'client_id' => $client->id,
        'industry_id' => industry()->id,
        'services' => [$service->id],
        'live_url' => 'https://gearzen.com.bd',
        'metrics' => [['value' => '12', 'label' => ['en' => 'Categories']]],
        'is_featured' => true,
        'cover' => UploadedFile::fake()->image('home.jpg', 1280, 800),
        'cover_alt' => ['en' => 'Gearzen home page', 'bn' => 'গিয়ারজেন হোম পেজ'],
        'seo' => ['title' => ['en' => 'Gearzen case study'], 'description' => ['en' => 'How Auxtech built Gearzen.']],
    ])->assertSessionHasNoErrors()->assertRedirect();

    $project = Project::query()->sole();

    expect($project->slug)->toBe('gearzen')
        ->and($project->getTranslation('title', 'bn'))->toBe('গিয়ারজেন')
        ->and($project->services->pluck('id')->all())->toBe([$service->id])
        ->and($project->metrics[0]['value'])->toBe('12')
        ->and($project->is_featured)->toBeTrue()
        ->and($project->seo->getTranslation('title', 'en'))->toBe('Gearzen case study');

    $cover = $project->image('cover');

    expect($cover['alt'])->toBe('Gearzen home page')
        ->and($cover['width'])->toBe(1280)
        ->and($cover['thumb'])->toEndWith('.webp')
        // Uploads are served from public/uploads: no storage symlink on shared hosting.
        ->and(config('filesystems.disks.public.root'))->toBe(public_path('uploads'))
        ->and(config('filesystems.disks.public.url'))->toBe('/uploads');

    // The new project is live on the public site straight away.
    $this->get('/work/gearzen')->assertOk()->assertSee('Gearzen home page');
    $this->get('/bn/work/gearzen')->assertSee('গিয়ারজেন');
});

it('shows saved values in the edit form', function () {
    $project = project(['slug' => 'gearzen']);
    $project->seo()->create(['title' => ['en' => 'Custom title']]);

    $this->get("/admin/projects/{$project->id}/edit")->assertInertia(fn (Assert $page) => $page
        ->component('Admin/Crud/Form')
        ->where('record.title.en', 'Gearzen')
        ->where('record.title.bn', null)
        ->where('record.status', 'published')
        ->where('record.seo.title.en', 'Custom title')
        ->where('record.public_url', url('/work/gearzen'))
        ->has('fields')
        ->where('module.can.delete', true));
});

it('updates a record and logs who changed what', function () {
    $project = project(['slug' => 'gearzen']);

    $this->post("/admin/projects/{$project->id}", [
        'title' => ['en' => 'Gearzen Bangladesh'], 'type' => 'demo', 'status' => 'draft',
    ])->assertSessionHasNoErrors();

    expect($project->fresh()->title)->toBe('Gearzen Bangladesh')->and($project->fresh()->status)->toBe(PublishStatus::Draft);

    $entry = Activity::query()->latest('id')->first();

    expect($entry->causer->is($this->user))->toBeTrue()
        ->and($entry->event)->toBe('updated')
        ->and($entry->properties['changed'])->toContain('title', 'status');

    $this->get('/work/gearzen')->assertNotFound();
});

it('validates required and malformed fields', function () {
    $this->post('/admin/projects', ['title' => ['bn' => 'শুধু বাংলা'], 'type' => 'nonsense', 'status' => 'published', 'live_url' => 'javascript:alert(1)', 'slug' => 'Bad Slug!'])
        ->assertSessionHasErrors(['title.en', 'type', 'live_url', 'slug']);

    expect(Project::query()->count())->toBe(0);
});

it('strips scripts and event handlers from rich text', function () {
    $this->post('/admin/posts', [
        'title' => ['en' => 'Guide <script>alert(1)</script>'],
        'body' => ['en' => '<p onclick="steal()">Safe <strong>text</strong></p><script>alert(1)</script><img src=x onerror=alert(1)>'],
        'status' => 'published',
    ])->assertSessionHasNoErrors();

    $post = Post::query()->sole();

    expect($post->body)->toContain('<strong>text</strong>')
        ->not->toContain('<script')->not->toContain('onclick')->not->toContain('onerror')
        ->and($post->title)->toBe('Guide alert(1)')
        ->and($post->author_id)->toBe($this->user->id);
});

it('rejects dangerous and oversized uploads', function (UploadedFile $file) {
    $this->post('/admin/projects', ['title' => ['en' => 'X'], 'type' => 'live', 'status' => 'draft', 'cover' => $file])
        ->assertSessionHasErrors('cover');
})->with([
    'php file' => [fn () => UploadedFile::fake()->create('shell.php', 10, 'application/x-php')],
    'php disguised as jpg' => [fn () => UploadedFile::fake()->createWithContent('shell.jpg', '<?php echo 1; ?>')],
    'svg' => [fn () => UploadedFile::fake()->createWithContent('logo.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>')],
    'too large' => [fn () => UploadedFile::fake()->image('big.jpg')->size(7000)],
]);

it('replaces, re-captions and removes an image', function () {
    $project = project();
    $this->post("/admin/projects/{$project->id}", ['title' => ['en' => 'Gearzen'], 'type' => 'live', 'status' => 'published',
        'cover' => UploadedFile::fake()->image('a.png', 600, 400)]);
    $first = $project->fresh()->getFirstMedia('cover');

    $this->post("/admin/projects/{$project->id}", ['title' => ['en' => 'Gearzen'], 'type' => 'live', 'status' => 'published', 'cover_alt' => ['en' => 'New caption']]);
    expect($project->fresh()->image('cover')['alt'])->toBe('New caption')->and($project->fresh()->getFirstMedia('cover')->id)->toBe($first->id);

    $this->post("/admin/projects/{$project->id}", ['title' => ['en' => 'Gearzen'], 'type' => 'live', 'status' => 'published', 'cover_remove' => true]);
    expect($project->fresh()->getFirstMedia('cover'))->toBeNull();
});

it('soft-deletes and restores a project', function () {
    $project = project(['slug' => 'gearzen']);

    $this->delete("/admin/projects/{$project->id}")->assertRedirect('/admin/projects');
    expect(Project::query()->count())->toBe(0);
    $this->get('/work/gearzen')->assertNotFound();
    $this->get('/admin/projects?trashed=1')->assertInertia(fn (Assert $page) => $page->has('records.data', 1)->where('records.data.0.trashed', true));

    $this->post("/admin/projects/{$project->id}/restore");
    $this->get('/work/gearzen')->assertOk();
});

it('searches, filters and reorders lists', function () {
    $a = project(['name' => 'Alpha', 'slug' => 'alpha']);
    $b = project(['name' => 'Beta', 'slug' => 'beta', 'status' => PublishStatus::Draft]);

    $this->get('/admin/projects?search=bet')->assertInertia(fn (Assert $page) => $page->has('records.data', 1)->where('records.data.0.title', 'Beta'));
    $this->get('/admin/projects?status=draft')->assertInertia(fn (Assert $page) => $page->has('records.data', 1));

    $this->post('/admin/projects/reorder', ['ids' => [$b->id, $a->id]])->assertSessionHasNoErrors();
    $this->get('/admin/projects')->assertInertia(fn (Assert $page) => $page->where('records.data.0.title', 'Beta'));
});

it('keeps slugs unique and CMS pages away from reserved URLs', function () {
    project(['slug' => 'gearzen']);
    $this->post('/admin/projects', ['title' => ['en' => 'Gearzen'], 'type' => 'live', 'status' => 'draft']);
    expect(Project::query()->latest('id')->first()->slug)->toBe('gearzen-2');

    $this->post('/admin/pages', ['title' => ['en' => 'Admin'], 'slug' => 'admin', 'template' => 'default', 'status' => 'published'])->assertSessionHasErrors('slug');
    $this->post('/admin/pages', ['title' => ['en' => 'Refund Policy'], 'template' => 'legal', 'status' => 'published'])->assertSessionHasNoErrors();
    expect(Page::query()->sole()->slug)->toBe('refund-policy');
    $this->get('/refund-policy')->assertOk();
});

it('manages the dynamic home content: stats, testimonials and FAQs', function () {
    $this->post('/admin/stats', ['value' => '30', 'suffix' => '+', 'label' => ['en' => 'Stores launched', 'bn' => 'স্টোর চালু'], 'is_active' => true])->assertSessionHasNoErrors();
    $this->post('/admin/testimonials', ['author_name' => 'Client Name', 'quote' => ['en' => 'Great work.'], 'rating' => 5, 'status' => 'published', 'is_featured' => true])->assertSessionHasNoErrors();
    $this->post('/admin/faqs', ['question' => ['en' => 'How long does a store take?'], 'answer' => ['en' => '<p>It depends on scope.</p>'], 'group' => 'general', 'is_active' => true])->assertSessionHasNoErrors();
    $this->post('/admin/testimonials', ['author_name' => 'X', 'quote' => ['en' => 'Y'], 'rating' => 9, 'status' => 'published'])->assertSessionHasErrors('rating');

    expect(Stat::query()->count())->toBe(1)->and(Testimonial::query()->count())->toBe(1)->and(Faq::query()->count())->toBe(1);

    $this->get('/')->assertInertia(fn (Assert $page) => $page
        ->where('stats.0.label', 'Stores launched')->where('testimonials.0.quote', 'Great work.')->where('faqs.0.question', 'How long does a store take?'));
    $this->get('/bn')->assertInertia(fn (Assert $page) => $page->where('stats.0.label', 'স্টোর চালু'));
});

it('attaches FAQs and pricing plans to a solution', function () {
    $solution = solution(['slug' => 'ecommerce']);

    $this->post('/admin/faqs', ['question' => ['en' => 'Is POS included?'], 'answer' => ['en' => '<p>Yes.</p>'], 'faqable_type' => 'solution', 'faqable_id' => $solution->id, 'is_active' => true]);
    $this->post('/admin/pricing-plans', ['name' => ['en' => 'Starter'], 'price' => 25000, 'currency' => 'BDT', 'period' => 'one-time',
        'features' => [['title' => ['en' => 'POS']]], 'planable_type' => 'solution', 'planable_id' => $solution->id, 'is_active' => true])->assertSessionHasNoErrors();

    $this->get('/solutions/ecommerce')->assertInertia(fn (Assert $page) => $page
        ->where('faqs.0.question', 'Is POS included?')
        ->where('plans.0.name', 'Starter')->where('plans.0.price', 25000)->where('plans.0.features', ['POS'])
        ->where('plans.0.whatsapp_url', fn (string $url) => str_contains(rawurldecode($url), 'Starter package')));
});

it('prevents a service from being its own parent', function () {
    $service = service();

    $this->post("/admin/services/{$service->id}", ['title' => ['en' => 'Website Development'], 'status' => 'published', 'parent_id' => $service->id])
        ->assertSessionHasErrors('parent_id');
});

it('manages redirects with validation', function () {
    $this->post('/admin/redirects', ['from_path' => '/old', 'to_url' => '/about', 'status_code' => 301])->assertSessionHasNoErrors();
    $this->post('/admin/redirects', ['from_path' => '/old', 'to_url' => '/contact', 'status_code' => 301])->assertSessionHasErrors('from_path');
    $this->post('/admin/redirects', ['from_path' => 'no-slash', 'to_url' => '/x', 'status_code' => 301])->assertSessionHasErrors('from_path');

    expect(Redirect::query()->count())->toBe(1);
    $this->get('/old')->assertRedirect('/about');
});

it('manages users: role, hashed password, unique email, and no self-lockout', function () {
    $this->post('/admin/users', ['name' => 'Sales Person', 'email' => 'sales@example.com', 'password' => 'a-long-password', 'role' => 'Sales', 'is_active' => true])
        ->assertSessionHasNoErrors();

    $created = User::query()->where('email', 'sales@example.com')->sole();

    expect($created->hasRole('Sales'))->toBeTrue()
        ->and($created->password)->not->toBe('a-long-password')
        ->and(Hash::check('a-long-password', $created->password))->toBeTrue();

    $this->post('/admin/users', ['name' => 'Dup', 'email' => 'sales@example.com', 'password' => 'a-long-password', 'role' => 'Sales'])->assertSessionHasErrors('email');
    $this->post('/admin/users', ['name' => 'Weak', 'email' => 'weak@example.com', 'password' => 'short', 'role' => 'Sales'])->assertSessionHasErrors('password');

    // Updating without a password keeps the old one.
    $this->post("/admin/users/{$created->id}", ['name' => 'Renamed', 'email' => 'sales@example.com', 'role' => 'Editor', 'is_active' => true])->assertSessionHasNoErrors();
    expect(Hash::check('a-long-password', $created->fresh()->password))->toBeTrue()->and($created->fresh()->hasRole('Editor'))->toBeTrue();

    // An admin cannot deactivate, demote or delete their own account.
    $this->post("/admin/users/{$this->user->id}", ['name' => 'Me', 'email' => $this->user->email, 'role' => 'Sales', 'is_active' => false]);
    expect($this->user->fresh()->is_active)->toBeTrue()->and($this->user->fresh()->hasRole('Super Admin'))->toBeTrue();
    $this->delete("/admin/users/{$this->user->id}")->assertSessionHas('error');
    expect(User::query()->whereKey($this->user->id)->exists())->toBeTrue();
});

it('never sends password hashes or secrets to the browser', function () {
    $this->get("/admin/users/{$this->user->id}/edit")->assertInertia(fn (Assert $page) => $page
        ->where('record.password', null)->where('record.role', 'Super Admin'))
        ->assertDontSee($this->user->password, false);
});

it('avoids N+1 queries on list pages', function () {
    foreach (range(1, 12) as $i) {
        project(['name' => "Store {$i}", 'slug' => "store-{$i}"]);
    }

    DB::enableQueryLog();
    $this->get('/admin/projects')->assertOk();
    $adminQueries = count(DB::getQueryLog());

    DB::flushQueryLog();
    $this->get('/work')->assertOk();
    $siteQueries = count(DB::getQueryLog());

    expect($adminQueries)->toBeLessThan(20)->and($siteQueries)->toBeLessThan(30);
});

it('reports published content with missing SEO data', function () {
    Service::query()->create(['title' => ['en' => 'Bare Service'], 'status' => PublishStatus::Published]);

    $this->get('/admin/seo-report')->assertInertia(fn (Assert $page) => $page
        ->where('issues.0.title', 'Bare Service')->where('issues.0.problems.0', 'No meta description'));
});
