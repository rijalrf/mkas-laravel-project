<x-app-layout>
    <x-slot name="title">Prioritas Pembayaran</x-slot>

    <!-- SUMMARY CARD -->
    <div class="bg-blue-600 rounded-2xl p-6 text-white shadow-lg shadow-blue-200 dark:shadow-none relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-1">
                <p class="text-xs font-bold uppercase tracking-[0.2em] opacity-80">Estimasi Tagihan</p>
                <span class="text-[10px] font-black bg-white/20 px-2 py-0.5 rounded-full uppercase tracking-tighter">
                    {{ \Carbon\Carbon::create()->month($currentMonth)->translatedFormat('F') }} {{ $currentYear }}
                </span>
            </div>
            <h2 class="text-3xl font-extrabold tracking-tight">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h2>
            <p class="text-[10px] mt-2 font-medium opacity-70 italic">* Total dari rencana yang belum terbayar</p>
        </div>
    </div>

    <!-- ADD PLAN BUTTON -->
    <button @click="openSheet('add-payment-plan')" class="w-full flex items-center justify-center gap-3 py-4 bg-white dark:bg-slate-800 border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-2xl text-gray-500 dark:text-slate-400 hover:border-blue-500 hover:text-blue-600 transition-all group shadow-sm">
        <div class="w-8 h-8 bg-gray-50 dark:bg-slate-900 rounded-lg flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-blue-900/30 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        </div>
        <span class="text-sm font-bold">Tambah Template Plan</span>
    </button>

    <!-- LIST SECTION: BULAN INI (BELUM TERBAYAR) -->
    <div class="space-y-4">
        <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-2">
                <span class="w-2 h-4 bg-blue-600 rounded-full"></span>
                <h3 class="text-xs font-black text-gray-700 dark:text-slate-300 uppercase tracking-widest">Bulan Ini</h3>
            </div>
            <span class="text-[10px] font-bold px-2 py-0.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full">{{ $activePlans->count() }} Item</span>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 overflow-hidden divide-y divide-gray-50 dark:divide-slate-700 shadow-sm">
            @forelse($activePlans as $plan)
                @php
                    $cat = collect(\App\Models\Category::getStaticList())->firstWhere('id', (int)$plan->category_id);
                    $color = $cat['color'] ?? 'slate';
                    $icon = $cat['icon'] ?? 'dots-horizontal';
                    
                    // Cek status dari transaksi bulan ini
                    $currentStatus = $plan->transaction ? $plan->transaction->status : 'NEW';
                    
                    $statusColor = 'amber';
                    if($currentStatus === 'PENDING') $statusColor = 'blue';
                    if($currentStatus === 'REJECTED') $statusColor = 'rose';
                    if($currentStatus === 'NEW') $statusColor = 'slate';
                @endphp
                <a href="{{ $currentStatus !== 'PENDING' ? route('transactions.create', ['type' => 'OUT', 'category_id' => $plan->category_id, 'description' => $plan->description, 'amount' => (int)$plan->amount, 'payment_plan_id' => $plan->id]) : '#' }}" 
                   @if($currentStatus === 'PENDING') onclick="return false;" @endif
                   class="p-4 flex items-center justify-between group active:bg-gray-50 dark:active:bg-slate-700/50 transition-colors {{ $currentStatus === 'PENDING' ? 'opacity-70 cursor-not-allowed' : '' }}">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 bg-{{ $color }}-50 dark:bg-{{ $color }}-900/30 text-{{ $color }}-600 dark:text-{{ $color }}-400 rounded-xl flex items-center justify-center shrink-0 shadow-sm group-hover:rotate-12 transition-transform">
                            {!! \App\Models\Category::getIconHtml($icon, "w-6 h-6") !!}
                        </div>
                        <div class="min-w-0 text-left">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $plan->description }}</h4>
                            <div class="flex items-center gap-2 mt-0.5">
                                <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">{{ $plan->category->name }}</p>
                                @if($currentStatus !== 'NEW')
                                    <span class="w-1 h-1 bg-gray-300 dark:bg-slate-600 rounded-full"></span>
                                    <span class="text-[8px] font-black px-1.5 py-0.5 bg-{{ $statusColor }}-50 dark:bg-{{ $statusColor }}-900/30 text-{{ $statusColor }}-600 dark:text-{{ $statusColor }}-400 rounded uppercase tracking-tighter">{{ $currentStatus }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-right flex items-center gap-3">
                        <p class="text-sm font-black text-gray-900 dark:text-white">Rp {{ number_format($plan->amount, 0, ',', '.') }}</p>
                        @if($currentStatus !== 'PENDING')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                        @endif
                    </div>
                </a>
            @empty
                <div class="p-10 flex flex-col items-center justify-center text-center">
                    <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-400 dark:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-slate-200">Semua Tagihan Beres!</h3>
                    <p class="text-[10px] text-gray-400 dark:text-slate-500 mt-1 max-w-[200px]">Semua rencana pembayaran bulan ini sudah berhasil diselesaikan.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- LIST SECTION: BULAN SELANJUTNYA (SUDAH TERBAYAR) -->
    <div class="space-y-4">
        <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-2">
                <span class="w-2 h-4 bg-emerald-500 rounded-full"></span>
                <h3 class="text-xs font-black text-gray-700 dark:text-slate-300 uppercase tracking-widest">Bulan Selanjutnya</h3>
            </div>
            <span class="text-[10px] font-bold px-2 py-0.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full">{{ $paidPlans->count() }} Item</span>
        </div>

        <div class="bg-gray-50/50 dark:bg-slate-900/50 rounded-2xl border border-dashed border-gray-200 dark:border-slate-800 overflow-hidden divide-y divide-gray-100 dark:divide-slate-800">
            @forelse($paidPlans as $plan)
                @php
                    $cat = collect(\App\Models\Category::getStaticList())->firstWhere('id', (int)$plan->category_id);
                    $color = $cat['color'] ?? 'slate';
                    $icon = $cat['icon'] ?? 'dots-horizontal';
                @endphp
                <div class="p-4 flex items-center justify-between opacity-60">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 bg-white dark:bg-slate-800 text-emerald-500 dark:text-emerald-400 rounded-xl flex items-center justify-center shrink-0 shadow-sm border border-gray-100 dark:border-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="min-w-0 text-left">
                            <h4 class="text-sm font-bold text-gray-500 dark:text-slate-400 truncate line-through">{{ $plan->description }}</h4>
                            <p class="text-[10px] font-bold text-emerald-500 dark:text-emerald-400 uppercase tracking-wider">Ready for next cycle</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-black text-gray-400 dark:text-slate-500">Rp {{ number_format($plan->amount, 0, ',', '.') }}</p>
                    </div>
                </div>
            @empty
                <div class="p-8 flex flex-col items-center justify-center text-center">
                    <p class="text-[10px] font-bold text-gray-300 dark:text-slate-700 uppercase tracking-widest italic">Belum ada plan yang selesai</p>
                </div>
            @endforelse
        </div>
    </div>

    @push('bottom-sheet')
        <template x-if="sheetView === 'add-payment-plan'">
            <div class="space-y-6 text-left">
                <div class="flex items-center justify-between px-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tambah Template</h3>
                    <button @click="closeSheet()" class="p-2 text-gray-400 hover:text-rose-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('payment-plans.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
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

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest ml-1">Keterangan Template</label>
                        <textarea name="description" required rows="3" placeholder="Contoh: Tagihan WiFi Bulanan..." 
                            class="w-full px-4 py-3.5 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-bold text-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 bg-gray-50 dark:bg-slate-800 placeholder:text-gray-300 dark:placeholder:text-slate-600 transition-all"></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-200 dark:shadow-none active:scale-[0.98] transition-all uppercase tracking-widest">
                            Simpan Template
                        </button>
                    </div>
                </form>
            </div>
        </template>
    @endpush
</x-app-layout>
