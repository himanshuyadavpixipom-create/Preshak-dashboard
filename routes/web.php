<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('clients', ClientController::class);
    Route::resource('reminders', \App\Http\Controllers\ReminderController::class);
    Route::resource('festivals', \App\Http\Controllers\FestivalController::class);

    // Message Templates Routes
    Route::get('/templates/{template}/preview', [App\Http\Controllers\MessageTemplateController::class, 'preview'])->name('templates.preview');
    Route::resource('templates', App\Http\Controllers\MessageTemplateController::class)->except(['show']);

    Route::get('/logs', [App\Http\Controllers\DeliveryLogController::class, 'index'])->name('logs.index');
    Route::get('/logs/{log}', [App\Http\Controllers\DeliveryLogController::class, 'show'])->name('logs.show');

    Route::get('/integrations', [App\Http\Controllers\SettingController::class, 'index'])->name('integrations.index');
    Route::post('/integrations', [App\Http\Controllers\SettingController::class, 'update'])->name('integrations.update');

    Route::get('/settings', function () {
        return view('settings.app');
    })->name('settings.index');

    Route::post('/theme', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate(['theme' => 'required|in:light,dark,system']);
        session(['theme' => $validated['theme']]);
        return response()->json(['status' => 'success']);
    })->name('theme.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
