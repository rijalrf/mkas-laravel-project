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
        return view('transactions.create', compact('categories', 'type', 'selectedCategoryId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:IN,OUT',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'photo' => 'required_without:photo_base64|image|max:2048',
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
        if ($request->filled('photo_base64')) {
            $image = $request->photo_base64;
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'receipts/' . time() . '_' . uniqid() . '.jpg';
            Storage::disk('public')->put($imageName, base64_decode($image));
            $photoPath = $imageName;
        } elseif ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('receipts', 'public');
        }

        Transaction::create([
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'photo' => $photoPath,
            'user_id' => $user->id,
            'status' => 'PENDING',
        ]);

        return redirect()->route('dashboard')->with('success', 'Transaksi berhasil diajukan!');
    }
}
