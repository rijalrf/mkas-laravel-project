<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HistoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Transaksi
    Route::get('/transactions/create/{type}', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    
    // Deposit (Iuran)
    Route::get('/deposits', [DepositController::class, 'index'])->name('deposits.index');
    Route::get('/deposits/create', [DepositController::class, 'create'])->name('deposits.create');
    Route::post('/deposits', [DepositController::class, 'store'])->name('deposits.store');
    Route::get('/deposits/{deposit}', [DepositController::class, 'show'])->name('deposits.show');

    // Riwayat
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    Route::get('/history/{transaction}', [HistoryController::class, 'show'])->name('history.show');

    // Admin Panel
    Route::middleware('can:admin')->group(function () {
        Route::get('/admin/approvals', [AdminController::class, 'index'])->name('admin.approvals');
        Route::post('/admin/transactions/{transaction}/approve', [AdminController::class, 'approveTransaction'])->name('admin.transactions.approve');
        Route::post('/admin/transactions/{transaction}/reject', [AdminController::class, 'rejectTransaction'])->name('admin.transactions.reject');
        Route::post('/admin/deposits/{deposit}/approve', [AdminController::class, 'approveDeposit'])->name('admin.deposits.approve');
        Route::post('/admin/deposits/{deposit}/reject', [AdminController::class, 'rejectDeposit'])->name('admin.deposits.reject');

        // Prioritas Pembayaran
        Route::get('/payment-priorities', [\App\Http\Controllers\PaymentPlanController::class, 'index'])->name('payment-plans.index');
        Route::post('/payment-priorities', [\App\Http\Controllers\PaymentPlanController::class, 'store'])->name('payment-plans.store');
    });
});

Route::middleware('auth')->group(function () {
    // Rekening & Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/update', [ProfileController::class, 'showUpdateForm'])->name('profile.update-view');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/payment-accounts', [AdminController::class, 'managePaymentAccount'])->name('payment-accounts.index');
    Route::post('/payment-accounts', [AdminController::class, 'storePaymentAccount'])->name('payment-accounts.store');
    
    Route::get('/categories-management', [AdminController::class, 'manageCategories'])->name('categories.index');
    Route::post('/categories-management', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::patch('/categories-management/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    
    Route::get('/my-transactions', [HistoryController::class, 'myTransactions'])->name('history.my');

    // Rute Sementara untuk Generate Data (Hapus setelah digunakan)
    Route::get('/generate-dummy-data', function() {
        if (Auth::user()->role !== 'admin') return "Hanya Admin yang bisa menjalankan ini.";
        
        $user = Auth::user();
        $categories = \App\Models\Category::all();
        if ($categories->isEmpty()) {
            foreach (\App\Models\Category::getStaticList() as $c) {
                \App\Models\Category::create(['name' => $c['name']]);
            }
            $categories = \App\Models\Category::all();
        }

        // Generate Transaksi dalam 3 bulan terakhir
        for ($i = 0; $i < 15; $i++) {
            $date = now()->subDays(rand(1, 90));
            \App\Models\Transaction::create([
                'type' => rand(0,1) ? 'IN' : 'OUT',
                'amount' => rand(10, 50) * 5000,
                'description' => 'Contoh Transaksi ' . ($i+1),
                'category_id' => $categories->random()->id,
                'user_id' => $user->id,
                'status' => 'APPROVED',
                'photo' => 'receipts/dummy.jpg', // Tambahkan placeholder foto
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        // Generate Iuran dalam 2 tahun terakhir
        $months = ['2025-01', '2025-06', '2026-01', '2026-04'];
        foreach ($months as $m) {
            \App\Models\Deposit::create([
                'user_id' => $user->id,
                'month' => $m,
                'amount' => 100000,
                'status' => 'APPROVED',
                'description' => 'Iuran otomatis ' . $m,
                'created_at' => \Carbon\Carbon::parse($m . '-01'),
            ]);
        }

        return "Data dummy berhasil dibuat! Silakan cek halaman Riwayat dan Iuran.";
    });
});

require __DIR__.'/auth.php';
