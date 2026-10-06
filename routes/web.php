<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MaterialController as AdminMaterialController;
use App\Http\Controllers\Admin\PembuatController as AdminPembuatController;
use App\Http\Controllers\Admin\ProgressController as AdminProgressController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Pembuat\DashboardController as PembuatDashboardController;
use App\Http\Controllers\Pembuat\MaterialController as PembuatMaterialController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\MaterialController as UserMaterialController;
use App\Http\Controllers\User\ProgressController as UserProgressController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/dashboard', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'pembuat_materi' => redirect()->route('pembuat.dashboard'),
            'user' => redirect()->route('user.dashboard'),
            default => redirect()->route('home'),
        };
    }
    return redirect()->route('home');
})->name('dashboard.redirect');

Route::get('/materi', [PublicController::class, 'materials'])->name('public.materials');
Route::get('/materi/{slug}/preview', [PublicController::class, 'preview'])->name('public.materi.preview');
Route::get('/kategori', [PublicController::class, 'categories'])->name('public.categories');
Route::get('/tentang', [PublicController::class, 'about'])->name('public.about');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Profile (All logged-in roles)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');
});

/*
|--------------------------------------------------------------------------
| User Routes (Role: user)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/materi', [UserMaterialController::class, 'index'])->name('materi.index');
    Route::get('/materi/{id}', [UserMaterialController::class, 'show'])->name('materi.show');
    Route::get('/materi/{id}/belajar', [UserMaterialController::class, 'learn'])->name('materi.learn');
    Route::get('/materi/{id}/soal', [UserMaterialController::class, 'quiz'])->name('materi.quiz');
    Route::post('/materi/{id}/submit', [UserMaterialController::class, 'submitQuiz'])->name('materi.submit');
    Route::get('/progres', [UserProgressController::class, 'index'])->name('progres');
    Route::get('/hasil', [UserProgressController::class, 'results'])->name('hasil');
});

/*
|--------------------------------------------------------------------------
| Pembuat Materi Routes (Role: pembuat_materi)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pembuat_materi'])->prefix('pembuat')->name('pembuat.')->group(function () {
    Route::get('/dashboard', [PembuatDashboardController::class, 'index'])->name('dashboard');
    
    // Material Management
    Route::get('/materi', [PembuatMaterialController::class, 'index'])->name('materi.index');
    Route::get('/materi/create', [PembuatMaterialController::class, 'create'])->name('materi.create');
    Route::post('/materi', [PembuatMaterialController::class, 'store'])->name('materi.store');
    Route::get('/materi/{id}', [PembuatMaterialController::class, 'show'])->name('materi.show');
    Route::get('/materi/{id}/edit', [PembuatMaterialController::class, 'edit'])->name('materi.edit');
    Route::put('/materi/{id}', [PembuatMaterialController::class, 'update'])->name('materi.update');
    Route::delete('/materi/{id}', [PembuatMaterialController::class, 'destroy'])->name('materi.destroy');
    
    // Content & Questions
    Route::post('/materi/{id}/upload', [PembuatMaterialController::class, 'uploadContents'])->name('materi.upload');
    Route::delete('/materi/{id}/content/{content_id}', [PembuatMaterialController::class, 'deleteContent'])->name('materi.content.delete');
    Route::post('/materi/{id}/soal', [PembuatMaterialController::class, 'storeQuestion'])->name('materi.soal');
    Route::delete('/materi/{id}/question/{question_id}', [PembuatMaterialController::class, 'deleteQuestion'])->name('materi.question.delete');
    
    // Submission & Revision
    Route::post('/materi/{id}/submit', [PembuatMaterialController::class, 'submitForReview'])->name('materi.submit');
    Route::get('/materi/{id}/revision', [PembuatMaterialController::class, 'revision'])->name('materi.revision');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Role: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{id}/toggle', [AdminUserController::class, 'toggleStatus'])->name('users.toggle');

    // Pembuat Materi Management
    Route::get('/pembuat', [AdminPembuatController::class, 'index'])->name('pembuat.index');
    Route::get('/pembuat/create', [AdminPembuatController::class, 'create'])->name('pembuat.create');
    Route::post('/pembuat', [AdminPembuatController::class, 'store'])->name('pembuat.store');
    Route::get('/pembuat/{id}/edit', [AdminPembuatController::class, 'edit'])->name('pembuat.edit');
    Route::put('/pembuat/{id}', [AdminPembuatController::class, 'update'])->name('pembuat.update');
    Route::post('/pembuat/{id}/toggle', [AdminPembuatController::class, 'toggleStatus'])->name('pembuat.toggle');
    Route::delete('/pembuat/{id}', [AdminPembuatController::class, 'destroy'])->name('pembuat.destroy');

    // Material Review & Management
    Route::get('/materi', [AdminMaterialController::class, 'index'])->name('materi.index');
    Route::get('/materi/pending', [AdminMaterialController::class, 'pending'])->name('materi.pending');
    Route::get('/materi/{id}', [AdminMaterialController::class, 'show'])->name('materi.show');
    Route::post('/materi/{id}/approve', [AdminMaterialController::class, 'approve'])->name('materi.approve');
    Route::post('/materi/{id}/revision', [AdminMaterialController::class, 'revision'])->name('materi.revision');
    Route::delete('/materi/{id}', [AdminMaterialController::class, 'destroy'])->name('materi.destroy');

    // Category Management
    Route::get('/kategori', [AdminCategoryController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [AdminCategoryController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{id}', [AdminCategoryController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [AdminCategoryController::class, 'destroy'])->name('kategori.destroy');

    // Progress Monitoring & Reports
    Route::get('/progres', [AdminProgressController::class, 'index'])->name('progres.index');
    Route::get('/progres/{user}', [AdminProgressController::class, 'showUser'])->name('progres.user');
    Route::get('/laporan', [AdminProgressController::class, 'reports'])->name('laporan.index');
});
