<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <!-- SUMMARY CARD (BLUE THEME) -->
    <div class="rounded-2xl p-6 text-white relative overflow-hidden mb-8" style="background-color: #2563eb;">
        <div class="relative z-10 space-y-8 text-left">
            <div class="flex justify-between items-center">
                <div class="space-y-1">
                    <p class="text-[10px] font-semibold text-blue-100 uppercase tracking-[0.3em] mb-1">Total saldo kas</p>
                    <h3 class="text-3xl font-black tracking-tighter text-white leading-none">Rp {{ number_format($saldoUtama, 0, ',', '.') }}</h3>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-0 divide-x divide-white/20 -mx-2">
                <div class="px-4 space-y-1">
                    <div class="flex items-center gap-1.5 text-blue-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0l-6.75 6.75M12 4.5l6.75 6.75" /></svg>
                        <p class="text-[10px] font-bold uppercase tracking-wide leading-none">Pemasukan</p>
                    </div>
                    <p class="text-base font-bold text-white leading-none">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                </div>
                <div class="px-6 space-y-1">
                    <div class="flex items-center gap-1.5 text-blue-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0l6.75-6.75M12 19.5l-6.75-6.75" /></svg>
                        <p class="text-[10px] font-bold uppercase tracking-wide leading-none">Pengeluaran</p>
                    </div>
                    <p class="text-base font-bold text-white leading-none">Rp {{ number_format($totalOut, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full"></div>
    </div>

    <!-- ADMIN TODO SECTION -->
    @if(Auth::user()->role === 'admin')
        @if(count($pendingTransactions) > 0 || count($pendingDeposits) > 0)
        <div class="space-y-4 mb-8">
            <h4 class="text-xs font-semibold text-rose-500 uppercase tracking-widest px-1">Butuh persetujuan</h4>
            <div class="flex items-center gap-3 overflow-x-auto hide-scroll px-1 pb-1">
                @foreach($pendingDeposits as $dp)
                    <a href="{{ route('deposits.show', $dp->id) }}" class="flex-shrink-0 w-48 bg-white p-4 rounded-2xl border border-rose-100 active:scale-95 transition-all text-left shadow-sm">
                        <p class="text-[9px] font-bold text-rose-400 uppercase tracking-wider mb-1">{{ $dp->reference_number ?? 'Iuran' }}</p>
                        <p class="text-sm font-semibold text-gray-800 truncate mb-2">{{ $dp->user->name }}</p>
                        <p class="text-sm font-bold text-blue-600">Rp {{ number_format($dp->amount, 0, ',', '.') }}</p>
                    </a>
                @endforeach
                @foreach($pendingTransactions as $tx)
                    <a href="{{ route('history.show', $tx->id) }}" class="flex-shrink-0 w-48 bg-white p-4 rounded-2xl border border-rose-100 active:scale-95 transition-all text-left shadow-sm">
                        <p class="text-[9px] font-bold text-rose-400 uppercase tracking-wider mb-1">{{ $tx->reference_number ?? 'Kas' }}</p>
                        <p class="text-sm font-semibold text-gray-800 truncate mb-2">{{ $tx->description }}</p>
                        <p class="text-sm font-bold text-rose-600">Rp {{ number_format($tx->amount, 0, ',', '.') }}</p>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- UNPAID USERS LIST -->
        @if(count($unpaidUsers) > 0)
        <div class="space-y-4 mb-8">
            <h4 class="text-xs font-semibold text-amber-600 uppercase tracking-widest px-1">Belum bayar iuran ({{ date('F') }})</h4>
            <div class="flex items-center gap-3 overflow-x-auto hide-scroll px-1 pb-1">
                @foreach($unpaidUsers as $u)
                    <div class="flex-shrink-0 w-32 bg-amber-50/50 p-3 rounded-2xl border border-amber-100 flex flex-col items-center gap-2">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-amber-600 font-bold border border-amber-100 uppercase text-xs">
                            {{ substr($u->name, 0, 2) }}
                        </div>
                        <p class="text-[10px] font-semibold text-gray-700 truncate w-full text-center">{{ $u->name }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    @endif

    <!-- CATEGORY GRID (CLEAN NO-BG) -->
    <div class="space-y-4 mb-8">
        <h4 class="text-sm font-medium text-gray-700 px-1 text-center">Kategori kas</h4>
        <div style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; padding-left: 4px; padding-right: 4px;">
            @foreach(\App\Models\Category::getStaticList() as $cat)
                @php
                    $staticModel = \App\Models\Category::find($cat['id']);
                @endphp
                <button onclick="window.location='{{ route('history.index') }}?category_id={{ $cat['id'] }}'" 
                    class="flex flex-col items-center gap-2 active:scale-95 transition-all text-center group">
                    <div class="w-16 h-16 bg-transparent border border-gray-200 rounded-[24px] flex items-center justify-center shadow-none group-hover:border-blue-500 group-hover:bg-blue-50 transition-all overflow-hidden">
                        <div class="w-7 h-7 flex items-center justify-center text-gray-900">
                            @if($staticModel && $staticModel->image_url)
                                <img src="{{ $staticModel->image_url }}" class="w-full h-full object-cover">
                            @else
                                {!! \App\Models\Category::getIconHtml($cat['icon'], "w-full h-full") !!}
                            @endif
                        </div>
                    </div>
                    <span class="text-[10px] font-medium text-gray-600 text-center leading-tight h-8 flex items-center justify-center px-1">
                        {{ $cat['name'] }}
                    </span>
                </button>
            @endforeach
        </div>
    </div>

    <!-- ANALISA KEUANGAN -->
    <div class="px-1 py-1 mb-8 text-center w-full">
        <p class="text-sm text-gray-600 leading-tight">
            Pengeluaran bulan ini lebih <span class="{{ $percentChange <= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-bold lowercase">{{ $percentChange <= 0 ? 'kecil' : 'besar' }} {{ abs(round($percentChange)) }}%</span> dari bulan lalu.
        </p>
    </div>

    <!-- CHARTS -->
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 space-y-6 shadow-none">
            <div class="flex items-center justify-between">
                <h4 class="text-md font-medium text-gray-800">Tren kas bulanan</h4>
                <form action="{{ route('dashboard') }}" method="GET" id="period-form">
                    <select name="period" onchange="document.getElementById('period-form').submit()" class="text-xs font-semibold border-none bg-gray-50 rounded-lg py-1.5 pl-3 pr-8 focus:ring-0 text-blue-600">
                        <option value="3" {{ $period == 3 ? 'selected' : '' }}>3 bulan</option>
                        <option value="6" {{ $period == 6 ? 'selected' : '' }}>6 bulan</option>
                        <option value="12" {{ $period == 12 ? 'selected' : '' }}>12 bulan</option>
                    </select>
                </form>
            </div>
            <div class="h-44 relative">
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 space-y-6 shadow-none">
            <div class="flex items-center justify-between">
                <h4 class="text-md font-medium text-gray-800">Distribusi pengeluaran</h4>
                <form action="{{ route('dashboard') }}" method="GET" id="year-form">
                    <select name="year" onchange="document.getElementById('year-form').submit()" class="text-xs font-semibold border-none bg-gray-50 rounded-lg py-1.5 pl-3 pr-8 focus:ring-0 text-blue-600">
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            @if($pieData->count() > 0)
                <div class="flex items-center gap-8 py-2">
                    <div class="shrink-0 relative" style="width: 110px; height: 110px;">
                        <canvas id="pieChart"></canvas>
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Out</span>
                        </div>
                    </div>
                    <div class="flex-1 space-y-2.5 text-left">
                        @foreach($pieData->take(4) as $index => $item)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ ['#2563eb', '#f43f5e', '#f59e0b', '#10b981', '#7c3aed'][$index % 5] }}"></span>
                                    <span class="text-xs text-gray-500 truncate">{{ $item->category->name }}</span>
                                </div>
                                <span class="text-xs font-bold text-gray-800">{{ round(($item->total / max($totalOut, 1)) * 100) }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chartFont = { size: 10, weight: '500', family: "'Plus Jakarta Sans', sans-serif" };
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
                options: { responsive: true, maintainAspectRatio: true, cutout: '82%', plugins: { legend: { display: false } } }
            });
            @endif
        });
    </script>
    @endpush
</x-app-layout>
