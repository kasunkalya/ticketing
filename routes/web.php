<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;


Route::get('/', [TicketController::class, 'index']);
Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
Route::get('/tickets/status', [TicketController::class, 'showStatusPage'])->name('ticket.status');
Route::post('/ticket-status', [TicketController::class, 'checkStatus'])->name('ticket.status.check');


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/dashboard', [TicketController::class, 'dashboard'])->name('dashboard');
    Route::get('/tickets/list', [TicketController::class, 'list'])->name('tickets.list');
    Route::post('/tickets/reply/{ticket}', [ReplyController::class, 'store'])->name('replies.store');
    Route::post('/tickets/mark_as_viewed/{id}', [TicketController::class, 'markAsViewed'])->name('tickets.markAsViewed');
});

require __DIR__.'/auth.php';