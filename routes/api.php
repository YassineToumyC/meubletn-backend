<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\Auth\FournisseurAuthController;
use App\Http\Controllers\Auth\UnifiedAuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Dashboard\AgentController;
use App\Http\Controllers\Dashboard\AnnouncementDashboardController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// ── Unified login (all user types) ────────────────────────────
Route::post('login', [UnifiedAuthController::class, 'login']);

// ── Public ────────────────────────────────────────────────────
Route::get('home',                    [HomeController::class,         'index']);
Route::get('categories',              [CategoryController::class,    'index']);
Route::get('announcements',           [AnnouncementController::class, 'index']);
Route::get('announcements/{slug}',    [AnnouncementController::class, 'show']);

// ── Client auth ────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('register', [ClientAuthController::class, 'register']);
    Route::post('login',    [ClientAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me',          [ClientAuthController::class, 'me']);
        Route::put('profile',     [ClientAuthController::class, 'updateProfile']);
        Route::put('password',    [ClientAuthController::class, 'changePassword']);
        Route::post('logout',     [ClientAuthController::class, 'logout']);
        Route::post('logout-all', [ClientAuthController::class, 'logoutAll']);
    });
});

// ── Fournisseur / Agent auth ───────────────────────────────────
Route::prefix('fournisseur')->group(function () {
    Route::post('register', [FournisseurAuthController::class, 'register']);
    Route::post('login',    [FournisseurAuthController::class, 'login']);

    Route::middleware('auth:sanctum,agent')->group(function () {
        Route::get('me',      [FournisseurAuthController::class, 'me']);
        Route::post('logout', [FournisseurAuthController::class, 'logout']);
    });
});

// ── Dashboard (fournisseur + agents) ───────────────────────────
Route::prefix('dashboard')->middleware('auth:sanctum,agent')->group(function () {

    // Annonces — fournisseur & agent (full CRUD)
    Route::apiResource('announcements', AnnouncementDashboardController::class);

    // Fournisseur only
    Route::get('stats',            [ProfileController::class, 'stats']);
    Route::get('profile',          [ProfileController::class, 'show']);
    Route::put('profile',          [ProfileController::class, 'update']);
    Route::put('profile/password', [ProfileController::class, 'changePassword']);
    Route::apiResource('agents',   AgentController::class)->except(['show']);
});