<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ServiceController;
use App\Http\Controllers\Frontend\ProjectController;
use App\Http\Controllers\Frontend\TeamController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\FaqController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\LegalController;
use App\Http\Controllers\Frontend\SitemapController;
use Illuminate\Support\Facades\Route;

// SEO endpoints — always available, before the catch-all, not gated by maintenance
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', function () {
    $txt = "User-agent: *\nAllow: /\n\nSitemap: ".url('/sitemap.xml')."\n";

    return response($txt)->header('Content-Type', 'text/plain');
})->name('robots');

Route::middleware('maintenance')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

    Route::get('/team', [TeamController::class, 'index'])->name('team.index');

    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

    Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');

    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
    Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:6,1')->name('contact.store');

    Route::get('/legal/{slug}', [LegalController::class, 'show'])->name('legal.show');

    // Generic CMS page by slug — keep LAST so it doesn't swallow the routes above
    Route::get('/{page}', [PageController::class, 'show'])->name('page.show');
});
