<x-app-layout>
    <x-slot name="title">Prioritas Pembayaran</x-slot>

    <!-- SUMMARY CARD -->
    <div class="bg-blue-600 rounded-2xl p-6 text-white shadow-lg shadow-blue-200 dark:shadow-none relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
            <p class="text-xs font-bold uppercase tracking-[0.2em] opacity-80 mb-1">Total Estimasi Prioritas</p>
            <h2 class="text-3xl font-extrabold tracking-tight">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h2>
        </div>
    </div>

    <!-- ADD PLAN BUTTON -->
    <button @click="openSheet('add-payment-plan')" class="w-full flex items-center justify-center gap-3 py-4 bg-white dark:bg-slate-800 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-2xl text-gray-500 dark:text-slate-400 hover:border-blue-500 hover:text-blue-600 transition-all group">
        <div class="w-8 h-8 bg-gray-50 dark:bg-slate-900 rounded-lg flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-blue-900/30 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        </div>
        <span class="text-sm font-bold">Tambah Plan Pembayaran</span>
    </button>

    <!-- PAYMENT PLANS LIST -->
    <div class="space-y-3">
        <div class="flex items-center justify-between px-1">
            <h3 class="text-xs font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest">Daftar Rencana</h3>
            <span class="text-[10px] font-bold px-2 py-0.5 bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-slate-400 rounded-full">{{ $plans->count() }} Item</span>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 overflow-hidden divide-y divide-gray-50 dark:divide-slate-700 shadow-sm">
            @forelse($plans as $plan)
                @php
                    $cat = collect(\App\Models\Category::getStaticList())->firstWhere('id', (int)$plan->category_id);
                    $color = $cat['color'] ?? 'slate';
                    $icon = $cat['icon'] ?? 'dots-horizontal';
                @endphp
                <a href="{{ route('transactions.create', ['type' => 'OUT', 'category_id' => $plan->category_id, 'description' => $plan->description, 'amount' => (int)$plan->amount]) }}" 
                   class="p-4 flex items-center justify-between group active:bg-gray-50 dark:active:bg-slate-700/50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 bg-{{ $color }}-50 dark:bg-{{ $color }}-900/30 text-{{ $color }}-600 dark:text-{{ $color }}-400 rounded-xl flex items-center justify-center shrink-0 shadow-sm group-hover:scale-110 transition-transform">
                            {!! \App\Models\Category::getIconHtml($icon, "w-6 h-6") !!}
                        </div>
                        <div class="min-w-0 text-left">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $plan->description }}</h4>
                            <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">{{ $plan->category->name }}</p>
                        </div>
                    </div>
                    <div class="text-right flex items-center gap-3">
                        <div>
                            <p class="text-sm font-black text-gray-900 dark:text-white">Rp {{ number_format($plan->amount, 0, ',', '.') }}</p>
                            <span class="text-[9px] font-black px-1.5 py-0.5 rounded {{ $plan->status === 'APPROVED' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400' }} uppercase tracking-tighter">{{ $plan->status }}</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </div>
                </a>
            @empty
                <div class="p-12 flex flex-col items-center justify-center text-center">
                    <div class="w-20 h-20 bg-gray-50 dark:bg-slate-900 rounded-full flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-200 dark:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-400 dark:text-slate-500">Belum ada rencana pembayaran</h3>
                </div>
            @endforelse
        </div>
    </div>

    @push('bottom-sheet')
        <template x-if="sheetView === 'add-payment-plan'">
            <div class="space-y-6 text-left">
                <div class="flex items-center justify-between px-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tambah Rencana</h3>
                    <button @click="closeSheet()" class="p-2 text-gray-400 hover:text-rose-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('payment-plans.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Category Select -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest ml-1">Kategori Rencana</label>
                        <div class="relative">
                            <select name="category_id" required class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-bold text-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 transition-all appearance-none">
                                <option value="" disabled selected>Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Amount Input with Formatting -->
                    <div class="space-y-1.5" x-data="{ 
                        formattedAmount: '',
                        updateAmount(val) {
                            let numeric = val.replace(/\D/g, '');
                            this.formattedAmount = numeric.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            document.getElementById('plan_raw_amount').value = numeric;
                        }
                    }">
                        <label class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest ml-1">Estimasi Nominal (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-gray-400">Rp</span>
                            <input type="text" x-model="formattedAmount" @input="updateAmount($event.target.value)" 
                                inputmode="numeric" required placeholder="0" 
                                class="w-full pl-11 pr-4 py-3.5 border border-gray-200 dark:border-slate-700 rounded-xl text-xl font-black text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 bg-gray-50 dark:bg-slate-800 placeholder:text-gray-300 dark:placeholder:text-slate-600 transition-all">
                            <input type="hidden" name="amount" id="plan_raw_amount">
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest ml-1">Keterangan / Tagihan</label>
                        <textarea name="description" required rows="3" placeholder="Contoh: Bayar Listrik April..." 
                            class="w-full px-4 py-3.5 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-bold text-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 bg-gray-50 dark:bg-slate-800 placeholder:text-gray-300 dark:placeholder:text-slate-600 transition-all"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-200 dark:shadow-none active:scale-[0.98] transition-all uppercase tracking-widest">
                            Simpan Rencana
                        </button>
                    </div>
                </form>
            </div>
        </template>
    @endpush
</x-app-layout>
