<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContactSubmissionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\LegalPageController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.attempt');

Route::middleware('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Pages + section editor
    Route::middleware('permission:view pages')->group(function () {
        Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
    });
    Route::middleware('permission:manage pages')->group(function () {
        Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
        Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');
        Route::post('/pages/{page}/toggle', [PageController::class, 'toggle'])->name('pages.toggle');
        Route::post('/pages/{page}/toggle-nav', [PageController::class, 'toggleNav'])->name('pages.toggleNav');
        Route::post('/pages/{page}/sections', [SectionController::class, 'store'])->name('sections.store');
        Route::put('/sections/{section}', [SectionController::class, 'update'])->name('sections.update');
        Route::delete('/sections/{section}', [SectionController::class, 'destroy'])->name('sections.destroy');
        Route::post('/sections/{section}/toggle', [SectionController::class, 'toggle'])->name('sections.toggle');
        Route::post('/sections/reorder', [SectionController::class, 'reorder'])->name('sections.reorder');
    });

    // Legal pages (managed from the Pages & sections screen)
    Route::middleware('permission:manage legal')->group(function () {
        Route::get('/legal/create', [LegalPageController::class, 'create'])->name('legal.create');
        Route::post('/legal', [LegalPageController::class, 'store'])->name('legal.store');
        Route::get('/legal/{legal}/edit', [LegalPageController::class, 'edit'])->name('legal.edit');
        Route::put('/legal/{legal}', [LegalPageController::class, 'update'])->name('legal.update');
        Route::delete('/legal/{legal}', [LegalPageController::class, 'destroy'])->name('legal.destroy');
        Route::post('/legal/{legal}/toggle', [LegalPageController::class, 'toggle'])->name('legal.toggle');
    });

    // Services
    Route::middleware('permission:view services')->group(function () {
        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    });
    Route::middleware('permission:manage services')->group(function () {
        Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
        Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
        Route::post('/services/reorder', [ServiceController::class, 'reorder'])->name('services.reorder');
        Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
        Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
        Route::post('/services/{service}/toggle', [ServiceController::class, 'toggle'])->name('services.toggle');
    });

    // Projects
    Route::middleware('permission:view projects')->group(function () {
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    });
    Route::middleware('permission:manage projects')->group(function () {
        Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
        Route::post('/projects/reorder', [ProjectController::class, 'reorder'])->name('projects.reorder');
        Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
        Route::post('/projects/{project}/toggle', [ProjectController::class, 'toggle'])->name('projects.toggle');
    });

    // Team
    Route::middleware('permission:view team')->group(function () {
        Route::get('/team', [TeamMemberController::class, 'index'])->name('team.index');
    });
    Route::middleware('permission:manage team')->group(function () {
        Route::get('/team/create', [TeamMemberController::class, 'create'])->name('team.create');
        Route::post('/team', [TeamMemberController::class, 'store'])->name('team.store');
        Route::post('/team/reorder', [TeamMemberController::class, 'reorder'])->name('team.reorder');
        Route::get('/team/{team}/edit', [TeamMemberController::class, 'edit'])->name('team.edit');
        Route::put('/team/{team}', [TeamMemberController::class, 'update'])->name('team.update');
        Route::delete('/team/{team}', [TeamMemberController::class, 'destroy'])->name('team.destroy');
        Route::post('/team/{team}/toggle', [TeamMemberController::class, 'toggle'])->name('team.toggle');
    });

    // FAQs
    Route::middleware('permission:view faqs')->group(function () {
        Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');
    });
    Route::middleware('permission:manage faqs')->group(function () {
        Route::get('/faqs/create', [FaqController::class, 'create'])->name('faqs.create');
        Route::post('/faqs', [FaqController::class, 'store'])->name('faqs.store');
        Route::post('/faqs/reorder', [FaqController::class, 'reorder'])->name('faqs.reorder');
        Route::get('/faqs/{faq}/edit', [FaqController::class, 'edit'])->name('faqs.edit');
        Route::put('/faqs/{faq}', [FaqController::class, 'update'])->name('faqs.update');
        Route::delete('/faqs/{faq}', [FaqController::class, 'destroy'])->name('faqs.destroy');
        Route::post('/faqs/{faq}/toggle', [FaqController::class, 'toggle'])->name('faqs.toggle');
    });

    // Blog
    Route::middleware('permission:view blog')->group(function () {
        Route::get('/blog', [PostController::class, 'index'])->name('posts.index');
    });
    Route::middleware('permission:manage blog')->group(function () {
        Route::get('/blog/categories', [PostCategoryController::class, 'index'])->name('categories.index');
        Route::post('/blog/categories', [PostCategoryController::class, 'store'])->name('categories.store');
        Route::delete('/blog/categories/{category}', [PostCategoryController::class, 'destroy'])->name('categories.destroy');
        Route::get('/blog/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('/blog', [PostController::class, 'store'])->name('posts.store');
        Route::get('/blog/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/blog/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/blog/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
        Route::post('/blog/{post}/toggle', [PostController::class, 'toggle'])->name('posts.toggle');
    });

    // Messages
    Route::middleware('permission:view messages')->group(function () {
        Route::get('/messages', [ContactSubmissionController::class, 'index'])->name('messages.index');
        Route::get('/messages/{message}', [ContactSubmissionController::class, 'show'])->name('messages.show');
    });
    Route::middleware('permission:manage messages')->group(function () {
        Route::post('/messages/read-all', [ContactSubmissionController::class, 'markAllRead'])->name('messages.readAll');
        Route::post('/messages/{message}/toggle-read', [ContactSubmissionController::class, 'toggleRead'])->name('messages.toggleRead');
        Route::delete('/messages/{message}', [ContactSubmissionController::class, 'destroy'])->name('messages.destroy');
    });

    // Settings (includes SEO tab + Maintenance tab)
    Route::middleware('permission:view settings')->group(function () {
        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    });
    Route::middleware('permission:manage settings')->group(function () {
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::get('/settings/seo/pages/{page}', [SettingController::class, 'editPageSeo'])->name('settings.pageSeo.edit');
        Route::put('/settings/seo/pages/{page}', [SettingController::class, 'updatePageSeo'])->name('settings.pageSeo.update');
    });

    // Users
    Route::middleware('permission:view users')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });
    Route::middleware('permission:manage users')->group(function () {
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Roles
    Route::middleware('permission:view roles')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    });
    Route::middleware('permission:manage roles')->group(function () {
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });
});
