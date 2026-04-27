<?php

namespace App\Http\Controllers;

use App\Models\PaymentPlan;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentPlanController extends Controller
{
    public function index()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Ambil semua payment plan (sebagai template)
        $allPlans = PaymentPlan::with(['category', 'transaction' => function($query) use ($currentMonth, $currentYear) {
            $query->whereMonth('created_at', $currentMonth)
                  ->whereYear('created_at', $currentYear)
                  ->latest();
        }])->get();

        // List 1: Bulan Ini (Belum bayar ATAU Transaksi belum APPROVED)
        $activePlans = $allPlans->filter(function($plan) {
            return !$plan->transaction || $plan->transaction->status !== 'APPROVED';
        });

        // List 2: Bulan Selanjutnya (Sudah APPROVED bulan ini)
        $paidPlans = $allPlans->filter(function($plan) {
            return $plan->transaction && $plan->transaction->status === 'APPROVED';
        });

        // Total nominal hanya dari plan yang belum terbayar (Bulan Ini)
        $totalAmount = $activePlans->sum('amount');
        $categories = Category::all();

        return view('payment-plans.index', compact('activePlans', 'paidPlans', 'totalAmount', 'categories', 'currentMonth', 'currentYear'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        PaymentPlan::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'status' => 'NEW',
        ]);

        return redirect()->route('payment-plans.index')->with('success', 'Rencana pembayaran berhasil ditambahkan');
    }

    public function destroy(PaymentPlan $paymentPlan)
    {
        $paymentPlan->delete();
        return redirect()->route('payment-plans.index')->with('success', 'Rencana pembayaran berhasil dihapus');
    }
}
