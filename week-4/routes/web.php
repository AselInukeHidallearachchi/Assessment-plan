<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/tickets');
Route::resource('tickets', TicketController::class);
