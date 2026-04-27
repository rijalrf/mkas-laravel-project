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
        $plans = PaymentPlan::with('category')
            ->latest()
            ->get();

        $totalAmount = $plans->sum('amount');
        $categories = Category::all();

        return view('payment-plans.index', compact('plans', 'totalAmount', 'categories'));
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
            'status' => 'PENDING',
        ]);

        return redirect()->route('payment-plans.index')->with('success', 'Rencana pembayaran berhasil ditambahkan');
    }
}
