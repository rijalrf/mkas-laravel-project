<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Deposit;
use App\Models\PaymentAccount;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $pendingTransactions = Transaction::with(['user', 'category'])->where('status', 'PENDING')->latest()->get();
        $pendingDeposits = Deposit::with('user')->where('status', 'PENDING')->latest()->get();
        
        return view('admin.approvals', compact('pendingTransactions', 'pendingDeposits'));
    }

    public function approveTransaction(Request $request, Transaction $transaction)
    {
        $request->validate([
            'admin_note' => 'required|string',
            'admin_photo' => 'nullable|image|max:2048'
        ]);

        $data = [
            'status' => 'APPROVED',
            'admin_note' => $request->admin_note
        ];

        if ($request->hasFile('admin_photo')) {
            $data['admin_photo'] = $request->file('admin_photo')->store('admin_receipts', 'public');
        }

        $transaction->update($data);
        return redirect()->route('history.show', $transaction->id)->with('success', 'Transaksi disetujui!');
    }

    public function rejectTransaction(Request $request, Transaction $transaction)
    {
        $request->validate([
            'admin_note' => 'required|string',
            'admin_photo' => 'nullable|image|max:2048'
        ]);

        $data = [
            'status' => 'REJECTED',
            'admin_note' => $request->admin_note
        ];

        if ($request->hasFile('admin_photo')) {
            $data['admin_photo'] = $request->file('admin_photo')->store('admin_receipts', 'public');
        }

        $transaction->update($data);
        return redirect()->route('history.show', $transaction->id)->with('error', 'Transaksi ditolak!');
    }

    public function approveDeposit(Request $request, Deposit $deposit)
    {
        $request->validate([
            'admin_note' => 'required|string',
            'admin_photo' => 'nullable|image|max:2048'
        ]);

        $data = [
            'status' => 'APPROVED',
            'admin_note' => $request->admin_note
        ];

        if ($request->hasFile('admin_photo')) {
            $data['admin_photo'] = $request->file('admin_photo')->store('admin_receipts', 'public');
        }

        $deposit->update($data);
        return redirect()->route('deposits.show', $deposit->id)->with('success', 'Iuran disetujui!');
    }

    public function rejectDeposit(Request $request, Deposit $deposit)
    {
        $request->validate([
            'admin_note' => 'required|string',
            'admin_photo' => 'nullable|image|max:2048'
        ]);

        $data = [
            'status' => 'REJECTED',
            'admin_note' => $request->admin_note
        ];

        if ($request->hasFile('admin_photo')) {
            $data['admin_photo'] = $request->file('admin_photo')->store('admin_receipts', 'public');
        }

        $deposit->update($data);
        return redirect()->route('deposits.show', $deposit->id)->with('error', 'Iuran ditolak!');
    }

    public function managePaymentAccount()
    {
        $account = PaymentAccount::latest()->first();
        return view('admin.payment-account', compact('account'));
    }

    public function storePaymentAccount(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string',
            'bank_code' => 'required|string',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
            'monthly_amount' => 'required|numeric',
        ]);

        PaymentAccount::create($request->all());
        return redirect()->route('profile.edit')->with('success', 'Master rekening diperbarui!');
    }

    public function manageCategories()
    {
        $categories = Category::all();
        $staticIcons = array_keys(Category::getStaticList()); // Simplified for this context
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:categories,name']);
        Category::create([
            'name' => $request->name,
            'icon' => 'dots-horizontal', // Default
            'color' => 'blue' // Default
        ]);
        return back()->with('success', 'Kategori baru ditambahkan!');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|unique:categories,name,'.$category->id]);
        $category->update(['name' => $request->name]);
        return back()->with('success', 'Kategori diperbarui!');
    }
}
