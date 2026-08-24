<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UmkmMiddleware;

// Storage File Serving Fallback (for hosting without symlink support)
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*');

// Public / Guest Routes
Route::get('/', [FeedController::class, 'index'])->name('feed.index');
Route::get('/katalog', [ProductController::class, 'index'])->name('catalog.index');
Route::get('/katalog/{id}', [ProductController::class, 'show'])->name('catalog.show');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::get('/register-umkm', [AuthController::class, 'showRegisterUmkmForm'])->name('register.umkm');
Route::post('/register-umkm', [AuthController::class, 'registerUmkm'])->name('register.umkm.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('feed.index')->with('success', 'Email Anda telah berhasil diverifikasi! Selamat menjelajahi UMKM Desa Bojongsawah.');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Instant Email Verification Route for Testing / Local Dev
Route::post('/email/verify-instant', function (Request $request) {
    $user = $request->user();
    if ($user && !$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }
    return redirect()->route('feed.index')->with('success', 'Selamat! Akun Anda telah berhasil diverifikasi secara instan. Selamat menjelajahi UMKM Desa Bojongsawah!');
})->middleware('auth')->name('verification.instant');

// Authenticated User General Routes
Route::middleware(['auth'])->group(function () {
    // Profile Settings
    Route::get('/settings', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/settings/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Feed Interactions (Comments)
    Route::post('/posts/{id}/comment', [CommentController::class, 'store'])->name('posts.comment');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

// Feed Post Action (Logged in UMKM or Admin)
Route::post('/feed/post', [FeedController::class, 'store'])->name('feed.store')->middleware('auth');

// Admin Protected Routes
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/umkm/{id}/approve', [AdminController::class, 'approve'])->name('umkm.approve');
    Route::post('/umkm/{id}/reject', [AdminController::class, 'reject'])->name('umkm.reject');

    // Admin Category Management Routes
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Admin Post Management & CRUD Routes
    Route::get('/posts', [AdminController::class, 'postsIndex'])->name('posts.index');
    Route::post('/posts', [AdminController::class, 'storePost'])->name('posts.store');
    Route::get('/posts/{id}/edit', [AdminController::class, 'editPost'])->name('posts.edit');
    Route::put('/posts/{id}', [AdminController::class, 'updatePost'])->name('posts.update');
    Route::delete('/posts/{id}', [AdminController::class, 'deletePost'])->name('posts.destroy');
});

// UMKM Protected Routes
Route::middleware(['auth', UmkmMiddleware::class])->prefix('umkm')->as('umkm.')->group(function () {
    Route::get('/dashboard', [UmkmController::class, 'dashboard'])->name('dashboard');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    // UMKM Post Management Routes
    Route::get('/posts/{id}/edit', [FeedController::class, 'editPost'])->name('posts.edit');
    Route::put('/posts/{id}', [FeedController::class, 'updatePost'])->name('posts.update');
    Route::delete('/posts/{id}', [FeedController::class, 'destroyPost'])->name('posts.destroy');
});
