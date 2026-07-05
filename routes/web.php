<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Models\MessageTemplate;

Route::get('/fix-server', function () {
    // Clear all caches
    Artisan::call('optimize:clear');
    Artisan::call('view:clear');
    
    // Run migrations just in case
    Artisan::call('migrate', ['--force' => true]);

    // Scan for reminders so calendar gets populated
    Artisan::call('crm:scan-reminders');

    // Setup Dummy Templates
    MessageTemplate::updateOrCreate(
        ['name' => 'Default WhatsApp Birthday', 'channel' => 'whatsapp', 'reminder_type' => 'birthday'],
        ['subject' => null, 'body' => 'Hi {{client_name}}, wishing you a very Happy Birthday! Hope you have a wonderful year ahead. - Preshak CRM', 'is_active' => true, 'is_default' => true]
    );

    MessageTemplate::updateOrCreate(
        ['name' => 'Default Email Anniversary', 'channel' => 'email', 'reminder_type' => 'anniversary'],
        ['subject' => 'Happy Anniversary {{client_name}}!', 'body' => 'Dear {{client_name}},\n\nWishing you a very Happy Anniversary! May your journey together be full of love.\n\nBest Regards,\nPreshak CRM', 'is_active' => true, 'is_default' => true]
    );

    MessageTemplate::updateOrCreate(
        ['name' => 'Default SMS Premium Due', 'channel' => 'sms', 'reminder_type' => 'premium_due'],
        ['subject' => null, 'body' => 'Dear {{client_name}}, this is a reminder that your premium for policy {{policy_number}} is due on {{premium_due_date}}. Please ignore if already paid.', 'is_active' => true, 'is_default' => true]
    );

    return "Server fixed! Cache cleared, Database checked, and 3 Dummy Templates created. Please refresh your dashboard.";
});



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
