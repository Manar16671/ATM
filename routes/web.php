<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\otpController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::prefix('otp')->group(function () {
    Route::get('/login', [otpController::class, 'login'])->name('otp.login');
    Route::post('/generate', [otpController::class, 'generate'])->name('otp.generate');
    Route::get('/verification/{user_id}', [otpController::class, 'verification'])->name('otp.verification');
    Route::post('/login', [otpController::class, 'loginWithOtp'])->name('otp.getlogin');
});
Route::middleware(['web', 'auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/deposit', [DashboardController::class, 'depositForm'])->name('deposit.form');
    Route::post('/deposit', [DashboardController::class, 'deposit'])->name('deposit');
    
    Route::get('/transfer', [DashboardController::class, 'transferForm'])->name('transfer.form');
    Route::post('/transfer', [DashboardController::class, 'transfer'])->name('transfer')->middleware('sufficient.balance');
    
    Route::get('/withdrawal', [DashboardController::class, 'withdrawalForm'])->name('withdrawal.form');
    Route::post('/withdrawal', [DashboardController::class, 'withdrawal'])->name('withdrawal')->middleware('sufficient.balance');
    Route::get('/dashboard/receipt/{receiptId}', [DashboardController::class, 'receipt'])->name('dashboard.receipt');
});
