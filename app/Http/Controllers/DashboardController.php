<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Deposit;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Base Query for Totals
        $txQuery = Transaction::where('status', 'APPROVED');
        $dpQuery = Deposit::where('status', 'APPROVED');
        
        if ($user->role !== 'admin') {
            $txQuery->where('user_id', $user->id);
            $dpQuery->where('user_id', $user->id);
        }

        $totalIn = $txQuery->clone()->where('type', 'IN')->sum('amount');
        $totalOut = $txQuery->clone()->where('type', 'OUT')->sum('amount');
        $totalDeposit = $dpQuery->clone()->sum('amount');
        
        $totalMasuk = $totalIn + $totalDeposit;
        $saldoUtama = $totalMasuk - $totalOut;

        // Analisa Pengeluaran (Bulan ini vs Bulan lalu)
        $thisMonthOut = Transaction::where('status', 'APPROVED')->where('type', 'OUT')
            ->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount');
        
        $lastMonthOut = Transaction::where('status', 'APPROVED')->where('type', 'OUT')
            ->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->sum('amount');

        $percentChange = 0;
        if ($lastMonthOut > 0) {
            $percentChange = (($thisMonthOut - $lastMonthOut) / $lastMonthOut) * 100;
        } elseif ($thisMonthOut > 0) {
            $percentChange = 100;
        }

        // Line Chart Data (Trend)
        $period = $request->get('period', 6);
        $chartMonths = []; 
        $chartTotalIn = []; // Combined Deposit + Kas Masuk
        $chartTotalOut = []; // Kas Keluar

        for ($i = $period - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartMonths[] = $date->format('M');
            
            $monthIn = Transaction::where('status', 'APPROVED')
                ->where('type', 'IN')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount');
                
            $monthDeposit = Deposit::where('status', 'APPROVED')
                ->where('month', $date->format('Y-m'))
                ->sum('amount');
                
            $monthOut = Transaction::where('status', 'APPROVED')
                ->where('type', 'OUT')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount');

            $chartTotalIn[] = $monthIn + $monthDeposit;
            $chartTotalOut[] = $monthOut;
        }

        // Pie Chart Data (Expenses by Category)
        $selectedYear = $request->get('year', date('Y'));
        $pieData = Transaction::where('status', 'APPROVED')
            ->where('type', 'OUT')
            ->whereYear('created_at', $selectedYear)
            ->selectRaw('category_id, sum(amount) as total')
            ->groupBy('category_id')
            ->with('category')
            ->get();

        // Available years for filter
        $years = Transaction::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        if (empty($years)) $years = [date('Y')];

        $categories = Category::all();
        $recentTransactions = Transaction::with(['category', 'user'])->latest()->limit(5)->get();

        // Admin Todo Data
        $pendingTransactions = [];
        $pendingDeposits = [];
        $unpaidUsers = [];

        if ($user->role === 'admin') {
            $pendingTransactions = Transaction::with(['user', 'category'])->where('status', 'PENDING')->latest()->get();
            $pendingDeposits = Deposit::with('user')->where('status', 'PENDING')->latest()->get();

            // Warga yang belum bayar iuran bulan ini
            $currentMonth = date('Y-m');
            $paidUserIds = Deposit::where('month', $currentMonth)
                ->where('status', '!=', 'REJECTED')
                ->pluck('user_id')
                ->toArray();
            $unpaidUsers = User::whereNotIn('id', $paidUserIds)->where('role', 'user')->get();
        }

        return view('dashboard', compact(
            'saldoUtama', 'totalMasuk', 'totalOut', 'recentTransactions',
            'chartMonths', 'chartTotalIn', 'chartTotalOut', 'pieData',
            'categories', 'percentChange', 'thisMonthOut', 'years', 'selectedYear', 'period',
            'pendingTransactions', 'pendingDeposits', 'unpaidUsers'
        ));
    }
}
