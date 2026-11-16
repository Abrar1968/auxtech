<?php

use App\Http\Controllers\Feeds\FeedController;
use App\Http\Controllers\Site\BlogController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\LeadController;
use App\Http\Controllers\Site\PageController;
use App\Http\Controllers\Site\SearchController;
use App\Http\Controllers\Site\ServiceController;
use App\Http\Controllers\Site\SolutionController;
use App\Http\Controllers\Site\WorkController;
use App\Support\ReservedSlugs;
use Illuminate\Support\Facades\Route;

/*
| Feeds for search engines and AI crawlers.
*/
Route::get('sitemap.xml', [FeedController::class, 'sitemap'])->name('sitemap');
Route::get('robots.txt', [FeedController::class, 'robots'])->name('robots');
Route::get('llms.txt', [FeedController::class, 'llms'])->name('llms');
Route::get('llms-full.txt', [FeedController::class, 'llmsFull'])->name('llms.full');
Route::get('feed.xml', [FeedController::class, 'rss'])->name('feed');

/*
| Small JSON endpoints used by the public pages.
*/
Route::post('api/leads', [LeadController::class, 'store'])->middleware('throttle:leads')->name('leads.store');
Route::post('api/events', [LeadController::class, 'event'])->middleware('throttle:events')->name('events.store');
Route::get('api/search', SearchController::class)->middleware('throttle:search')->name('search');
Route::get('go/whatsapp', [LeadController::class, 'whatsapp'])->middleware('throttle:events')->name('go.whatsapp');

Route::permanentRedirect('auxify', '/solutions/auxify');

/*
| Public pages. Registered twice: English without a prefix and Bangla
| under /bn, so route names are "work.show" and "bn.work.show".
*/
$site = function (): void {
    Route::get('/', HomeController::class)->name('home');
    Route::get('work', [WorkController::class, 'index'])->name('work.index');
    Route::get('work/{slug}', [WorkController::class, 'show'])->name('work.show');
    Route::get('services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('services/{slug}', [ServiceController::class, 'show'])->name('services.show');
    Route::get('solutions', [SolutionController::class, 'index'])->name('solutions.index');
    Route::get('solutions/{slug}', [SolutionController::class, 'show'])->name('solutions.show');
    Route::get('industries/{slug}', [PageController::class, 'industry'])->name('industries.show');
    Route::get('pricing', [PageController::class, 'pricing'])->name('pricing');
    Route::get('about', [PageController::class, 'about'])->name('about');
    Route::get('faq', [PageController::class, 'faq'])->name('faq');
    Route::get('contact', [PageController::class, 'contact'])->name('contact');
    Route::get('estimate', [PageController::class, 'estimate'])->name('estimate');
    Route::get('blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
    Route::get('blog/tag/{slug}', [BlogController::class, 'tag'])->name('blog.tag');
    Route::get('blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

    // CMS pages and SEO landing pages: last, so they never shadow a real route.
    Route::get('{slug}', [PageController::class, 'show'])
        ->where('slug', '(?!(?:'.implode('|', ReservedSlugs::all()).')$)[a-z0-9]+(?:-[a-z0-9]+)*')
        ->name('page.show');
};

Route::middleware('site')->group(function () use ($site): void {
    Route::prefix('bn')->name('bn.')->group($site);
    Route::group([], $site);
});
