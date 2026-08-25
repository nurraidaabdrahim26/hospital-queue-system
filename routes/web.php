<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SmsController;

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

// // Home & Public Pages
// Route::get('/qr', function () {
//     return view('queues.qr-page');
// })->name('home');

// Route::get('/patient', function () {
//     return view('queues.qr-page');
// })->name('patient.portal');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Queue Routes
Route::get('/qr', [QueueController::class, 'showQR'])->name('queues.qr');
Route::get('/check-queue', [QueueController::class, 'checkStatus'])->name('queues.check-status');
Route::post('/check-queue', [QueueController::class, 'getStatus'])->name('queues.get-status');

/*
|--------------------------------------------------------------------------
| Protected Routes (Authentication Required)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    /*
    |--------------------------------------------------------------------------
    | Queue Management Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('queues')->group(function () {
        Route::get('/create', [QueueController::class, 'create'])->name('queues.create');
        Route::post('/', [QueueController::class, 'store'])->name('queues.store');
        Route::get('/manage', [QueueController::class, 'manage'])->name('queues.manage');
        
        // Queue Actions
        Route::post('/{queue}/call', [QueueController::class, 'call'])->name('queues.call');
        Route::post('/{queue}/complete', [QueueController::class, 'complete'])->name('queues.complete');
        Route::post('/{queue}/cancel', [QueueController::class, 'cancel'])->name('queues.cancel');
        Route::post('/call-next', [QueueController::class, 'callNext'])->name('queues.call-next');
        Route::post('/call-first', [QueueController::class, 'callFirst'])->name('queues.call-first');
        Route::post('/call-next/{departmentId}', [QueueController::class, 'callNext'])->name('queues.call-next-department');
    });
    
    /*
    |--------------------------------------------------------------------------
    | SMS Module Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth'])->prefix('sms')->group(function () {
        // SMS Dashboard
        Route::get('/dashboard', [SmsController::class, 'dashboard'])->name('sms.dashboard');
        
        // Send SMS
        Route::get('/send', [SmsController::class, 'sendForm'])->name('sms.send');
        Route::post('/send', [SmsController::class, 'sendSms'])->name('sms.send.post');
        
        // Queue Alerts
        Route::post('/send-queue-alert', [SmsController::class, 'sendQueueAlert'])->name('sms.queue-alert');
        Route::post('/send-call-notification/{queue}', [SmsController::class, 'sendCallNotification'])->name('sms.call-notification');
        
        // Settings
        Route::get('/settings', [SmsController::class, 'settings'])->name('sms.settings');
        Route::put('/settings', [SmsController::class, 'updateSettings'])->name('sms.settings.update');
        
        // Test SMS
        Route::get('/test', [SmsController::class, 'test'])->name('sms.test');
        Route::post('/test', [SmsController::class, 'sendTest'])->name('sms.test.send');
        
        // History/Reports
        Route::get('/history', [SmsController::class, 'history'])->name('sms.history');
    });
        
    /*
    |--------------------------------------------------------------------------
    | Admin Management Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth'])->group(function () {
        Route::resource('staff', StaffController::class);
        Route::resource('departments', DepartmentController::class);
    });
});

/*
|--------------------------------------------------------------------------
| API Routes (for AJAX calls)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('api')->group(function () {
    // Queue API Routes
    Route::post('/queues/{queue}/call', [QueueController::class, 'call']);
    Route::post('/queues/{queue}/complete', [QueueController::class, 'complete']);
    Route::post('/queues/{queue}/cancel', [QueueController::class, 'cancel']);
    Route::post('/queues/call-next', [QueueController::class, 'callNext']);
    Route::post('/queues/call-first', [QueueController::class, 'callFirst']);
    
    // SMS API Routes
    Route::post('/send-queue-alert', [SmsController::class, 'sendQueueAlert']);
    Route::post('/auto-send-alerts', [SmsController::class, 'autoSendAlerts']);
    Route::post('/send-call-notification/{queue}', [SmsController::class, 'sendCallNotification']);
    Route::get('/get-queue-position/{queue}', [SmsController::class, 'getQueuePosition']);
    Route::post('/bulk-send-alerts', [SmsController::class, 'bulkSendAlerts']);
});

Route::get('/sms/settings', [SmsController::class, 'settings'])->name('sms.settings');
Route::get('/sms/settings/edit', [SmsController::class, 'editSettings'])->name('sms.settings.edit');
Route::put('/sms/settings/update', [SmsController::class, 'updateSettings'])->name('sms.settings.update');

// SMS Management Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/sms/dashboard', [SmsController::class, 'dashboard'])->name('sms.dashboard');
    Route::get('/sms/send', [SmsController::class, 'sendForm'])->name('sms.send');
    Route::get('/sms/test', [SmsController::class, 'testForm'])->name('sms.test');
    Route::get('/sms/settings', [SmsController::class, 'settings'])->name('sms.settings');
    Route::get('/sms/history', [SmsController::class, 'history'])->name('sms.history');
    Route::post('/sms/queue-alert', [SmsController::class, 'sendQueueAlert'])->name('sms.queue-alert');
});