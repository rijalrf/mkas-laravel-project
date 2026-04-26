<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <!-- FLAT PREMIUM SUMMARY CARD -->
    <div class="bg-blue-600 rounded-2xl p-6 text-white relative overflow-hidden border border-blue-500 mb-8 shadow-none">
        <div class="relative z-10 space-y-8 text-left">
            <div class="flex justify-between items-center">
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-blue-100 uppercase tracking-[0.3em]">Total Saldo Kas</p>
                    <h3 class="text-3xl font-black tracking-tighter italic text-white leading-none">Rp {{ number_format($saldoUtama, 0, ',', '.') }}</h3>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center border border-white/20 shadow-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M12 7.5a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z" /><path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v14.25c0 1.035-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 19.125V4.875zM12 15.75a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /><path d="M22.5 10.5a3 3 0 01-3 3V9a3 3 0 013 3zM1.5 10.5a3 3 0 003 3V9a3 3 0 00-3 3z" /></svg>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-0 divide-x divide-white/20 -mx-2">
                <div class="px-4 space-y-1">
                    <div class="flex items-center gap-1.5 text-blue-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-blue-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0l-6.75 6.75M12 4.5l6.75 6.75" /></svg>
                        <p class="text-[9px] font-black uppercase tracking-widest leading-none">Pemasukan</p>
                    </div>
                    <p class="text-base font-black tracking-tight leading-none text-white">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                </div>
                <div class="px-6 space-y-1">
                    <div class="flex items-center gap-1.5 text-blue-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-blue-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0l6.75-6.75M12 19.5l-6.75-6.75" /></svg>
                        <p class="text-[9px] font-black uppercase tracking-widest leading-none">Pengeluaran</p>
                    </div>
                    <p class="text-base font-black tracking-tight leading-none text-white">Rp {{ number_format($totalOut, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/5 rounded-full shadow-none"></div>
    </div>

    <!-- ADMIN TODO SECTION -->
    @if(Auth::user()->role === 'admin' && (count($pendingTransactions) > 0 || count($pendingDeposits) > 0))
    <div class="space-y-3 mb-8">
        <h4 class="text-[10px] font-black text-rose-500 uppercase tracking-widest px-1 text-left">Butuh Persetujuan</h4>
        <div class="flex items-center gap-3 overflow-x-auto hide-scroll px-1 pb-1">
            @foreach($pendingDeposits as $dp)
                <a href="{{ route('deposits.show', $dp->id) }}" class="flex-shrink-0 w-44 bg-white p-4 rounded-2xl border border-rose-100 shadow-none active:scale-95 transition-all text-left">
                    <p class="text-[9px] font-black text-rose-400 uppercase mb-1">Iuran</p>
                    <p class="text-xs font-bold text-gray-800 truncate mb-2">{{ $dp->user->name }}</p>
                    <p class="text-sm font-black text-blue-600">Rp {{ number_format($dp->amount, 0, ',', '.') }}</p>
                </a>
            @endforeach
            @foreach($pendingTransactions as $tx)
                <a href="{{ route('history.show', $tx->id) }}" class="flex-shrink-0 w-44 bg-white p-4 rounded-2xl border border-rose-100 shadow-none active:scale-95 transition-all text-left">
                    <p class="text-[9px] font-black text-rose-400 uppercase mb-1">Kas {{ $tx->type }}</p>
                    <p class="text-xs font-bold text-gray-800 truncate mb-2">{{ $tx->description }}</p>
                    <p class="text-sm font-black text-rose-600">Rp {{ number_format($tx->amount, 0, ',', '.') }}</p>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- FORCED CATEGORY GRID (STRICT 4 COLUMNS VIA INLINE CSS) -->
    <div class="space-y-4 mb-8">
        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] px-1 text-center">Kategori Kas</h4>
        <div style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; padding-left: 4px; padding-right: 4px;">
            @foreach(\App\Models\Category::getStaticList() as $cat)
                @php
                    $colors = [
                        'blue' => 'bg-blue-50 border-blue-100',
                        'amber' => 'bg-amber-50 border-amber-100',
                        'emerald' => 'bg-emerald-50 border-emerald-100',
                        'indigo' => 'bg-indigo-50 border-indigo-100',
                        'rose' => 'bg-rose-50 border-rose-100',
                        'sky' => 'bg-sky-50 border-sky-100',
                        'violet' => 'bg-violet-50 border-violet-100',
                        'orange' => 'bg-orange-50 border-orange-100',
                        'slate' => 'bg-slate-50 border-slate-100',
                    ];
                    $c = $colors[$cat['color']] ?? $colors['blue'];
                @endphp
                <button onclick="window.location='{{ route('history.index') }}?category_id={{ $cat['id'] }}'" 
                    class="flex flex-col items-center gap-2 group w-full active:scale-95 transition-all text-center">
                    <!-- Clean Container with Slightly Thicker Border and Black Icons -->
                    <div class="w-16 h-16 bg-transparent border border-gray-200 rounded-[24px] flex items-center justify-center shadow-none text-slate-900 group-hover:border-blue-500 group-hover:bg-blue-50 transition-colors">
                        <div class="w-6 h-6 flex items-center justify-center">
                            {!! \App\Models\Category::getIconHtml($cat['icon'], "w-full h-full") !!}
                        </div>
                    </div>
                    <span class="text-[9px] font-bold text-gray-500 text-center uppercase tracking-tighter leading-tight h-8 flex items-center justify-center px-1">
                        {{ $cat['name'] }}
                    </span>
                </button>
            @endforeach
        </div>
    </div>

    <!-- MINIMALIST HEALTH STATUS (CENTERED TEXT ONLY) -->
    <div class="px-1 py-1 mb-8 text-center w-full">
        <p class="text-xs font-semibold text-gray-700 leading-tight">
            Pengeluaran bulan ini lebih <span class="{{ $percentChange <= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-black uppercase">{{ $percentChange <= 0 ? 'kecil' : 'besar' }} {{ abs(round($percentChange)) }}%</span> dari bulan lalu.
        </p>
    </div>

    <!-- TREND CHART -->
    <div class="bg-white p-5 rounded-xl border border-gray-100 space-y-4 mb-8 shadow-none">
        <div class="flex items-center justify-between">
            <h4 class="text-[11px] font-black text-gray-800 uppercase tracking-widest">Tren Kas</h4>
            <form action="{{ route('dashboard') }}" method="GET" id="period-form">
                <select name="period" onchange="document.getElementById('period-form').submit()" class="text-[10px] font-black border-none bg-gray-50 rounded-lg py-1.5 pl-3 pr-8 focus:ring-0 text-blue-600 shadow-none">
                    <option value="3" {{ $period == 3 ? 'selected' : '' }}>3B</option>
                    <option value="6" {{ $period == 6 ? 'selected' : '' }}>6B</option>
                    <option value="12" {{ $period == 12 ? 'selected' : '' }}>12B</option>
                </select>
                <input type="hidden" name="year" value="{{ $selectedYear }}">
            </form>
        </div>
        <div class="h-40 relative">
            <canvas id="lineChart"></canvas>
        </div>
    </div>

    <!-- PIE CHART -->
    <div class="bg-white p-5 rounded-xl border border-gray-100 space-y-4 mb-8 shadow-none">
        <div class="flex items-center justify-between">
            <h4 class="text-[11px] font-black text-gray-800 uppercase tracking-widest">Distribusi</h4>
            <form action="{{ route('dashboard') }}" method="GET" id="year-form">
                <select name="year" onchange="document.getElementById('year-form').submit()" class="text-[10px] font-black border-none bg-gray-50 rounded-lg py-1.5 pl-3 pr-8 focus:ring-0 text-blue-600 shadow-none">
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="period" value="{{ $period }}">
            </form>
        </div>
        @if($pieData->count() > 0)
            <div class="flex items-center gap-8 py-2">
                <!-- Balanced Fixed Chart Container -->
                <div class="shrink-0 relative" style="width: 110px; height: 110px;">
                    <canvas id="pieChart"></canvas>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <span class="text-[9px] font-black text-gray-400 leading-none uppercase tracking-tighter">Out</span>
                    </div>
                </div>
                <!-- Compact Legend -->
                <div class="flex-1 space-y-2 text-left">
                    @foreach($pieData->take(4) as $index => $item)
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ ['#2563eb', '#f43f5e', '#f59e0b', '#10b981', '#7c3aed'][$index % 5] }}"></span>
                                <span class="text-[9px] font-bold text-gray-500 uppercase truncate tracking-tighter">{{ $item->category->name }}</span>
                            </div>
                            <span class="text-[10px] font-black text-gray-900">{{ round(($item->total / max($totalOut, 1)) * 100) }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="h-32 flex flex-col items-center justify-center opacity-20">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.001 0 0120.488 9z" /></svg>
                <p class="text-[9px] font-black uppercase tracking-widest mt-2">Belum ada data</p>
            </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chartFont = { size: 9, weight: '700', family: "'Inter', sans-serif" };
            new Chart(document.getElementById('lineChart'), {
                type: 'line',
                data: { labels: {!! json_encode($chartMonths) !!}, datasets: [
                    { label: 'In', data: {!! json_encode($chartTotalIn) !!}, borderColor: '#2563eb', backgroundColor: '#2563eb10', fill: true, tension: 0.4, borderWidth: 3, pointRadius: 0 },
                    { label: 'Out', data: {!! json_encode($chartTotalOut) !!}, borderColor: '#f43f5e', backgroundColor: '#f43f5e10', fill: true, tension: 0.4, borderWidth: 3, pointRadius: 0 }
                ]},
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { display: false }, x: { grid: { display: false }, border: { display: false }, ticks: { font: chartFont, color: '#94a3b8' } } } }
            });

            @if($pieData->count() > 0)
            new Chart(document.getElementById('pieChart'), {
                type: 'doughnut',
                data: { labels: {!! json_encode($pieData->map(fn($i) => $i->category->name)) !!}, datasets: [{ data: {!! json_encode($pieData->pluck('total')) !!}, backgroundColor: ['#2563eb', '#f43f5e', '#f59e0b', '#10b981', '#7c3aed'], borderWidth: 0 }]},
                options: { responsive: true, maintainAspectRatio: true, cutout: '80%', plugins: { legend: { display: false } } }
            });
            @endif
        });
    </script>
    @endpush
</x-app-layout>
