<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function create(Request $request, $type)
    {
        $categories = Category::all();
        $selectedCategoryId = $request->category_id;
        $amount = $request->amount;
        $description = $request->description;
        return view('transactions.create', compact('categories', 'type', 'selectedCategoryId', 'amount', 'description'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:IN,OUT',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string',
            'category_id' => 'required',
            'photo' => 'required|image|max:5120', // Maksimal 5MB
        ]);

        $user = Auth::user();

        // Validasi Saldo jika Kas Keluar
        if ($request->type === 'OUT') {
            $totalIn = Transaction::where('status', 'APPROVED')->where('type', 'IN')->sum('amount') + 
                       Deposit::where('status', 'APPROVED')->sum('amount');
            $totalOut = Transaction::where('status', 'APPROVED')->where('type', 'OUT')->sum('amount');
            $currentBalance = $totalIn - $totalOut;

            if ($request->amount > $currentBalance) {
                return back()->with('error', 'Saldo tidak mencukupi!')->withInput();
            }
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('receipts', 'public');
        }

        $dbCategoryId = Category::ensureExists($request->category_id);

        Transaction::create([
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'category_id' => $dbCategoryId,
            'photo' => $photoPath,
            'user_id' => $user->id,
            'status' => 'PENDING',
        ]);

        return redirect()->route('dashboard')->with('success', 'Transaksi berhasil diajukan!');
    }
}
