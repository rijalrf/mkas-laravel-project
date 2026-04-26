<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ 
    darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
}" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? config('app.name', 'MKAS') }}</title>
        
        <!-- Plus Jakarta Sans Font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; -webkit-tap-highlight-color: transparent; }
            [x-cloak] { display: none !important; }
            .hide-scroll::-webkit-scrollbar { display: none; }
            .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
            input:focus, select:focus, textarea:focus { outline: none !important; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1) !important; border-color: #2563eb !important; }
            .card-hover:active { transform: scale(0.98); transition: all 0.2s ease; }
            
            /* Dark Mode Overrides */
            .dark body { background-color: #0f172a; color: #f8fafc; }
            .dark .bg-white { background-color: #1e293b; border-color: #334155; }
            .dark .text-gray-900 { color: #f8fafc; }
            .dark .text-gray-800 { color: #f1f5f9; }
            .dark .text-gray-700 { color: #e2e8f0; }
            .dark .text-gray-600 { color: #cbd5e1; }
            .dark .border-gray-100 { border-color: #334155; }
            .dark .border-gray-200 { border-color: #334155; }
            .dark .bg-gray-50 { background-color: #1e293b; }
            .dark header { background-color: #1e293b; border-color: #334155; }
            .dark nav { background-color: #1e293b; border-color: #334155; }
        </style>
    </head>
    <body class="bg-gray-50 text-gray-900 antialiased leading-relaxed" 
          x-data="{ 
            sheetOpen: false, 
            sheetView: 'menu', 
            selectedType: '', 
            categoryId: null, 
            categoryName: '',
            categoryUrl: '',
            toast: { show: false, message: '', type: 'success' },
            openSheet(view = 'menu') {
                this.sheetView = view;
                this.sheetOpen = true;
            },
            closeSheet() {
                this.sheetOpen = false;
            },
            selectType(type) {
                this.selectedType = type;
                this.sheetView = 'categories';
            },
            selectCategory(id, name) {
                this.categoryId = id;
                this.categoryName = name;
                this.sheetView = 'form';
            },
            showToast(msg, type = 'success') {
                this.toast.message = msg;
                this.toast.type = type;
                this.toast.show = true;
                setTimeout(() => this.toast.show = false, 3000);
            }
          }"
          x-init="
            @if(session('success')) showToast('{{ session('success') }}', 'success'); @endif
            @if(session('error')) showToast('{{ session('error') }}', 'error'); @endif
          ">
        
        <!-- POPUP ALERT (CENTERED) -->
        <div x-cloak x-show="toast.show" x-transition.scale.origin.center 
             class="fixed inset-0 flex items-center justify-center z-[100] px-6 pointer-events-none">
            <div :class="toast.type === 'success' ? 'bg-emerald-600' : 'bg-rose-600'" 
                 class="px-6 py-4 rounded-2xl shadow-2xl text-white text-center flex flex-col items-center gap-2 pointer-events-auto">
                <template x-if="toast.type === 'success'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </template>
                <template x-if="toast.type === 'error'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                </template>
                <p class="text-sm font-bold uppercase tracking-widest" x-text="toast.message"></p>
            </div>
        </div>

        <div class="min-h-screen flex flex-col max-w-md mx-auto bg-gray-50 shadow-sm relative overflow-x-hidden">
            <!-- TOP HEADER -->
            <header class="bg-white sticky top-0 z-30 border-b border-gray-200 h-16 flex items-center shrink-0 px-2">
                <div class="w-full px-3 flex items-center justify-between">
                    <div class="w-10">
                        @if(!request()->routeIs('dashboard'))
                            <a href="{{ route('dashboard') }}" class="p-2 text-gray-400 hover:text-blue-600 transition-colors inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                            </a>
                        @endif
                    </div>
                    <h1 class="text-lg font-semibold text-gray-900 tracking-tight">{{ $title ?? 'Dashboard' }}</h1>
                    <div class="w-10 flex justify-end">
                        @if(request()->routeIs('history.index') || request()->routeIs('deposits.index'))
                            <button @click="openSheet(request()->routeIs('history.index') ? 'filter' : 'filter-deposit')" class="p-2 text-gray-400 hover:text-blue-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" /></svg>
                            </button>
                        @endif
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto pb-36 p-4 space-y-5">
                {{ $slot }}
                <div class="h-12 shrink-0"></div>
            </main>

            <!-- BOTTOM NAVIGATION (DASHBOARD ONLY) -->
            @if(request()->routeIs('dashboard'))
            <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-40 max-w-md mx-auto h-16 flex items-center shrink-0 px-2">
                <div class="flex-1 flex flex-col items-center justify-center gap-0.5 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400' }}" onclick="window.location='{{ route('dashboard') }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                    <span class="text-[10px] font-medium">Home</span>
                </div>
                <div class="flex-1 flex flex-col items-center justify-center gap-0.5 {{ request()->routeIs('history.index') ? 'text-blue-600' : 'text-gray-400' }}" onclick="window.location='{{ route('history.index') }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="text-[10px] font-medium">Riwayat</span>
                </div>
                <div class="flex-1 flex justify-center -mt-10">
                    <button @click="openSheet('menu')" class="w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-lg border-4 border-gray-50 active:scale-95 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    </button>
                </div>
                <div class="flex-1 flex flex-col items-center justify-center gap-0.5 {{ request()->routeIs('deposits.*') ? 'text-blue-600' : 'text-gray-400' }}" onclick="window.location='{{ route('deposits.index') }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" /></svg>
                    <span class="text-[10px] font-medium">Iuran</span>
                </div>
                <div class="flex-1 flex flex-col items-center justify-center gap-0.5 {{ request()->routeIs('profile.*') ? 'text-blue-600' : 'text-gray-400' }}" onclick="window.location='{{ route('profile.edit') }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    <span class="text-[10px] font-medium">Profil</span>
                </div>
            </nav>
            @endif

            <!-- UNIVERSAL BOTTOM SHEET -->
            <div x-cloak x-show="sheetOpen" class="fixed inset-0 z-50">
                <div x-show="sheetOpen" x-transition.opacity @click="closeSheet()" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
                <div x-show="sheetOpen" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" 
                     class="absolute bottom-0 left-0 right-0 max-w-md mx-auto bg-white rounded-t-2xl shadow-xl p-6 overflow-hidden flex flex-col max-h-[90vh]">
                    <div class="w-12 h-1 bg-gray-200 rounded-full mx-auto mb-6 shrink-0"></div>
                    <div class="overflow-y-auto hide-scroll flex-1 px-1">
                        <!-- Selection Menu -->
                        <template x-if="sheetView === 'menu'">
                            <div class="space-y-6 w-full text-center">
                                <h3 class="text-md font-semibold text-gray-800">Buat transaksi baru</h3>
                                <div class="grid grid-cols-3 gap-3 w-full">
                                    <button @click="selectType('IN')" class="flex flex-col items-center gap-3 p-3 group active:scale-95 transition-all">
                                        <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0l-6.75 6.75M12 4.5l6.75 6.75" /></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-600">Kas masuk</span>
                                    </button>
                                    <button @click="selectType('OUT')" class="flex flex-col items-center gap-3 p-3 group active:scale-95 transition-all">
                                        <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center border border-rose-100 group-hover:bg-rose-600 group-hover:text-white transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0l6.75-6.75M12 19.5l-6.75-6.75" /></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-600">Kas keluar</span>
                                    </button>
                                    <a href="{{ route('deposits.create') }}" class="flex flex-col items-center gap-3 p-3 group active:scale-95 transition-all">
                                        <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center border border-amber-100 group-hover:bg-amber-600 group-hover:text-white transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" /></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-600">Bayar iuran</span>
                                    </a>
                                </div>
                            </div>
                        </template>

                        <!-- Categories List -->
                        <template x-if="sheetView === 'categories'">
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 px-1">
                                    <button @click="sheetView = 'menu'" class="p-1 text-gray-400 hover:text-blue-600 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg></button>
                                    <h3 class="text-md font-semibold text-gray-800">Pilih kategori</h3>
                                </div>
                                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden divide-y divide-gray-50 max-h-[60vh] overflow-y-auto hide-scroll shadow-sm">
                                    @foreach(\App\Models\Category::getStaticList() as $cat)
                                        <a :href="'/transactions/create/' + selectedType + '?category_id={{ $cat['id'] }}'" class="w-full text-left p-4 hover:bg-gray-50 transition-colors flex items-center justify-between group">
                                            <div class="flex items-center gap-4 text-left">
                                                <div class="w-9 h-9 bg-{{ $cat['color'] }}-50 text-{{ $cat['color'] }}-600 rounded-lg flex items-center justify-center shrink-0">
                                                    {!! \App\Models\Category::getIconHtml($cat['icon'], "w-5 h-5") !!}
                                                </div>
                                                <span class="text-sm font-medium text-gray-700">{{ $cat['name'] }}</span>
                                            </div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </template>

                        <!-- Filters -->
                        <template x-if="sheetView === 'filter' || sheetView === 'filter-deposit'">
                            <div class="space-y-6 text-left">
                                <h3 class="text-md font-semibold text-gray-800 px-1">Filter riwayat</h3>
                                <form :action="sheetView === 'filter' ? '{{ route('history.index') }}' : '{{ route('deposits.index') }}'" method="GET" class="space-y-5">
                                    <template x-if="sheetView === 'filter'">
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="space-y-1.5">
                                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-widest ml-1">Tgl mulai</label>
                                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm bg-gray-50 focus:ring-2 focus:ring-blue-500">
                                            </div>
                                            <div class="space-y-1.5">
                                                <label class="text-xs font-semibold text-gray-400 uppercase tracking-widest ml-1">Tgl selesai</label>
                                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm bg-gray-50 focus:ring-2 focus:ring-blue-500">
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="sheetView === 'filter-deposit'">
                                        <div class="space-y-1.5">
                                            <label class="text-xs font-semibold text-gray-400 uppercase tracking-widest ml-1">Pilih bulan</label>
                                            <input type="month" name="month" value="{{ request('month') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-gray-50 focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </template>
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-widest ml-1">Status</label>
                                        <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-gray-50 focus:ring-2 focus:ring-blue-500">
                                            <option value="">Semua status</option>
                                            <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                                            <option value="APPROVED" {{ request('status') == 'APPROVED' ? 'selected' : '' }}>APPROVED</option>
                                            <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>REJECTED</option>
                                        </select>
                                    </div>
                                    <div class="flex gap-2 pt-4">
                                        <button type="reset" class="flex-1 py-3.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-500 bg-white">Reset</button>
                                        <button type="submit" class="flex-[2] py-3.5 bg-blue-600 text-white rounded-lg font-bold text-sm shadow-md">Terapkan filter</button>
                                    </div>
                                </form>
                            </div>
                        </template>

                        <!-- Admin Approvals -->
                        <template x-if="sheetView === 'approve-tx' || sheetView === 'reject-tx' || sheetView === 'approve-dp' || sheetView === 'reject-dp'">
                            <div class="space-y-6 text-left">
                                <h3 class="text-md font-semibold text-gray-800" x-text="sheetView.includes('approve') ? 'Setujui permintaan' : 'Tolak permintaan'"></h3>
                                <form :action="sheetView.includes('tx') ? '/admin/transactions/' + categoryId + '/' + (sheetView.includes('approve') ? 'approve' : 'reject') : '/admin/deposits/' + categoryId + '/' + (sheetView.includes('approve') ? 'approve' : 'reject')" method="POST" class="space-y-4">
                                    @csrf
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-semibold text-gray-400 uppercase tracking-widest ml-1">Catatan admin (Wajib)</label>
                                        <textarea name="admin_note" required rows="4" placeholder="Tuliskan alasan atau catatan..." class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm bg-gray-50 focus:ring-2 focus:ring-blue-500"></textarea>
                                    </div>
                                    <button type="submit" 
                                        :class="sheetView.includes('approve') ? 'bg-emerald-600' : 'bg-rose-600'"
                                        class="w-full py-4 text-white rounded-lg font-bold text-sm shadow-md active:scale-[0.98] transition-all" 
                                        x-text="sheetView.includes('approve') ? 'Konfirmasi setujui' : 'Konfirmasi tolak'">
                                    </button>
                                </form>
                            </div>
                        </template>

                        @stack('bottom-sheet')
                    </div>
                </div>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
