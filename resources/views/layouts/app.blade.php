<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? config('app.name', 'MKAS') }}</title>
        
        <!-- Inter Font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Inter', system-ui, sans-serif; -webkit-tap-highlight-color: transparent; }
            [x-cloak] { display: none !important; }
            .hide-scroll::-webkit-scrollbar { display: none; }
            .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
            input:focus, select:focus, textarea:focus { outline: none !important; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1) !important; border-color: #2563eb !important; }
            .premium-shadow { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02); }
            .card-hover:active { transform: scale(0.98); transition: all 0.2s ease; }
        </style>
    </head>
    <body class="bg-gray-50 text-gray-900 antialiased" 
          x-data="{ 
            sheetOpen: false, 
            sheetView: 'menu', 
            selectedType: '', 
            categoryId: null, 
            categoryName: '',
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
            }
          }">
        
        <div class="min-h-screen flex flex-col max-w-md mx-auto bg-gray-50 shadow-sm">
            <!-- TOP HEADER (MANDATORY - SINGLE SOURCE OF TRUTH) -->
            <header class="bg-white sticky top-0 z-30 border-b border-gray-200 h-16 flex items-center shrink-0">
                <div class="w-full px-4 flex items-center justify-between">
                    <!-- Left: Absolute Home Navigation -->
                    <div class="w-10 text-left">
                        @if(!request()->routeIs('dashboard'))
                            <a href="{{ route('dashboard') }}" class="p-2 text-gray-400 hover:text-blue-600 transition-colors inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                            </a>
                        @endif
                    </div>

                    <!-- Center: Page Title (REQUIRED) -->
                    <h1 class="text-base font-semibold text-gray-900 tracking-tight">
                        {{ $title ?? 'Dashboard' }}
                    </h1>

                    <!-- Right: Action Icon -->
                    <div class="w-10 flex justify-end">
                        @if(request()->routeIs('history.index'))
                            <button @click="openSheet('filter')" class="p-2 text-gray-400 hover:text-blue-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" /></svg>
                            </button>
                        @elseif(request()->routeIs('deposits.index'))
                            <button @click="openSheet('filter-deposit')" class="p-2 text-gray-400 hover:text-blue-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" /></svg>
                            </button>
                        @else
                            <div class="w-5"></div>
                        @endif
                    </div>
                </div>
            </header>

            <!-- SCROLLABLE CONTENT (NO TITLES INSIDE) -->
            <main class="flex-1 overflow-y-auto pb-36 p-4 space-y-4">
                <!-- Notifications -->
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                         class="bg-emerald-50 border border-emerald-200 p-4 rounded-xl flex items-center gap-3 text-emerald-700 shadow-sm animate-fade-in-down">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg>
                        <p class="text-xs font-bold">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                         class="bg-rose-50 border border-rose-200 p-4 rounded-xl flex items-center gap-3 text-rose-700 shadow-sm animate-fade-in-down">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z" clip-rule="evenodd" /></svg>
                        <p class="text-xs font-bold">{{ session('error') }}</p>
                    </div>
                @endif

                {{ $slot }}
                <!-- Spacer to prevent content being hidden behind bottom nav -->
                <div class="h-12 shrink-0"></div>
            </main>

            <!-- BOTTOM NAVIGATION (DASHBOARD ONLY) -->
            @if(request()->routeIs('dashboard'))
            <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-40 max-w-md mx-auto h-16 flex items-center shrink-0">
                <div class="flex-1 flex flex-col items-center justify-center gap-0.5 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400' }}" onclick="window.location='{{ route('dashboard') }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                    <span class="text-[9px] font-medium uppercase tracking-wider">Home</span>
                </div>

                <div class="flex-1 flex flex-col items-center justify-center gap-0.5 {{ request()->routeIs('history.index') ? 'text-blue-600' : 'text-gray-400' }}" onclick="window.location='{{ route('history.index') }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="text-[9px] font-medium uppercase tracking-wider">Riwayat</span>
                </div>

                <!-- FAB Center "+" -->
                <div class="flex-1 flex justify-center -mt-10">
                    <button @click="openSheet('menu')" class="w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-lg shadow-blue-200 border-4 border-gray-50 active:scale-95 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    </button>
                </div>

                <div class="flex-1 flex flex-col items-center justify-center gap-0.5 {{ request()->routeIs('deposits.*') ? 'text-blue-600' : 'text-gray-400' }}" onclick="window.location='{{ route('deposits.index') }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" /></svg>
                    <span class="text-[9px] font-medium uppercase tracking-wider">Deposit</span>
                </div>

                <div class="flex-1 flex flex-col items-center justify-center gap-0.5 {{ request()->routeIs('profile.*') ? 'text-blue-600' : 'text-gray-400' }}" onclick="window.location='{{ route('profile.edit') }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    <span class="text-[9px] font-medium uppercase tracking-wider">Profile</span>
                </div>
            </nav>
            @endif

            <!-- UNIVERSAL BOTTOM SHEET -->
            <div x-cloak x-show="sheetOpen" class="fixed inset-0 z-50">
                <div x-show="sheetOpen" x-transition.opacity @click="closeSheet()" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
                <div x-show="sheetOpen" 
                     x-transition:enter="transition ease-out duration-300 transform" 
                     x-transition:enter-start="translate-y-full" 
                     x-transition:enter-end="translate-y-0" 
                     x-transition:leave="transition ease-in duration-200 transform" 
                     x-transition:leave-start="translate-y-0" 
                     x-transition:leave-end="translate-y-full" 
                     class="absolute bottom-0 left-0 right-0 max-w-md mx-auto bg-white rounded-t-2xl shadow-xl p-6 overflow-hidden flex flex-col max-h-[90vh]">
                    
                    <div class="w-12 h-1 bg-gray-200 rounded-full mx-auto mb-6 shrink-0"></div>
                    <div class="overflow-y-auto hide-scroll flex-1 w-full text-left">
                        <!-- Menu Form Selection (3-Column Grid) -->
                        <template x-if="sheetView === 'menu'">
                            <div class="space-y-6 w-full text-center">
                                <h3 class="text-base font-bold text-gray-900 px-1">Buat Transaksi Baru</h3>
                                <div class="grid grid-cols-3 gap-3 w-full">
                                    <!-- Kas Masuk -->
                                    <button @click="selectType('IN')" class="flex flex-col items-center gap-3 p-3 group active:scale-95 transition-all">
                                        <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-sm border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0l-6.75 6.75M12 4.5l6.75 6.75" /></svg>
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-600 uppercase tracking-tighter">Kas Masuk</span>
                                    </button>
                                    
                                    <!-- Kas Keluar -->
                                    <button @click="selectType('OUT')" class="flex flex-col items-center gap-3 p-3 group active:scale-95 transition-all">
                                        <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center shadow-sm border border-rose-100 group-hover:bg-rose-600 group-hover:text-white transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0l6.75-6.75M12 19.5l-6.75-6.75" /></svg>
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-600 uppercase tracking-tighter">Kas Keluar</span>
                                    </button>

                                    <!-- Bayar Iuran -->
                                    <a href="{{ route('deposits.create') }}" class="flex flex-col items-center gap-3 p-3 group active:scale-95 transition-all">
                                        <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center shadow-sm border border-amber-100 group-hover:bg-amber-600 group-hover:text-white transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" /></svg>
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-600 uppercase tracking-tighter">Bayar Iuran</span>
                                    </a>
                                </div>
                            </div>
                        </template>

                        <!-- Step 2: Categories (Redirect to create page) -->
                        <template x-if="sheetView === 'categories'">
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 px-1">
                                    <button @click="sheetView = 'menu'" class="p-1 text-gray-400 hover:text-blue-600 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg></button>
                                    <h3 class="text-base font-bold text-gray-900">Pilih Kategori</h3>
                                </div>
                                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden divide-y divide-gray-50 shadow-sm max-h-[60vh] overflow-y-auto hide-scroll">
                                    @foreach(\App\Models\Category::getStaticList() as $cat)
                                        <a :href="'/transactions/create/' + selectedType + '?category_id={{ $cat['id'] }}'" class="w-full text-left p-4 hover:bg-gray-50 transition-colors flex items-center justify-between group">
                                            <div class="flex items-center gap-4 text-left">
                                                <div class="w-9 h-9 bg-{{ $cat['color'] }}-50 text-{{ $cat['color'] }}-600 rounded-lg flex items-center justify-center shrink-0">
                                                    {!! \App\Models\Category::getIconHtml($cat['icon'], "w-5 h-5") !!}
                                                </div>
                                                <span class="text-sm font-semibold text-gray-700 uppercase tracking-tighter">{{ $cat['name'] }}</span>
                                            </div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </template>

                        <!-- Step 3: Transaction Form -->
                        <template x-if="sheetView === 'form'">
                            <div class="space-y-6">
                                <div class="flex items-center gap-2">
                                    <button @click="sheetView = 'categories'" class="p-1 text-gray-400"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg></button>
                                    <h3 class="text-base font-bold text-gray-900" x-text="'Kas ' + (selectedType === 'IN' ? 'Masuk' : 'Keluar') + ': ' + categoryName"></h3>
                                </div>
                                <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="type" :value="selectedType">
                                    <input type="hidden" name="category_id" :value="categoryId">
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider ml-1">Nominal</label>
                                        <input type="number" name="amount" required placeholder="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-sky-500">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider ml-1">Keterangan</label>
                                        <textarea name="description" required rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-sky-500"></textarea>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider ml-1">Unggah Bukti</label>
                                        <input type="file" name="photo" required class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-sky-50 file:text-sky-700 w-full">
                                    </div>
                                    <button type="submit" class="w-full py-3.5 bg-sky-500 text-white rounded-lg font-bold text-sm shadow-md hover:bg-sky-600 transition-all">Simpan Transaksi</button>
                                </form>
                            </div>
                        </template>

                        <!-- Special: Filter View (History) -->
                        <template x-if="sheetView === 'filter'">
                            <div class="space-y-6">
                                <h3 class="text-base font-bold text-gray-900 px-1">Filter Riwayat</h3>
                                <form action="{{ route('history.index') }}" method="GET" class="space-y-4">
                                    <!-- ... (existing history filters) ... -->
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Tgl Mulai</label>
                                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Tgl Selesai</label>
                                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50">
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Kategori</label>
                                        <select name="category_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50">
                                            <option value="">Semua Kategori</option>
                                            @foreach(\App\Models\Category::all() as $cat)
                                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Status</label>
                                        <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50">
                                            <option value="">Semua Status</option>
                                            <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                                            <option value="APPROVED" {{ request('status') == 'APPROVED' ? 'selected' : '' }}>APPROVED</option>
                                            <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>REJECTED</option>
                                        </select>
                                    </div>
                                    <div class="flex gap-2 pt-4">
                                        <a href="{{ route('history.index') }}" class="flex-1 py-3.5 text-center border border-gray-200 rounded-lg text-sm font-semibold text-gray-600 bg-white hover:bg-gray-50 transition-colors uppercase tracking-widest text-[10px]">Reset</a>
                                        <button type="submit" class="flex-[2] py-3.5 bg-blue-600 text-white rounded-lg font-bold text-[10px] shadow-md hover:bg-blue-700 transition-all active:scale-[0.98] uppercase tracking-widest">Terapkan Filter</button>
                                    </div>
                                </form>
                            </div>
                        </template>

                        <!-- Special: Filter View (Deposit) -->
                        <template x-if="sheetView === 'filter-deposit'">
                            <div class="space-y-6">
                                <h3 class="text-base font-bold text-gray-900 px-1">Filter Deposit</h3>
                                <form action="{{ route('deposits.index') }}" method="GET" class="space-y-4">
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Pilih Bulan</label>
                                        <input type="month" name="month" value="{{ request('month') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50">
                                    </div>
                                    @if(Auth::user()->role === 'admin')
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Oleh Siapa</label>
                                        <select name="user_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50">
                                            <option value="">Semua Warga</option>
                                            @foreach(\App\Models\User::all() as $user)
                                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                    <div class="flex gap-2 pt-4">
                                        <a href="{{ route('deposits.index') }}" class="flex-1 py-3.5 text-center border border-gray-200 rounded-lg text-sm font-semibold text-gray-600 bg-white hover:bg-gray-50 transition-colors uppercase tracking-widest text-[10px]">Reset</a>
                                        <button type="submit" class="flex-[2] py-3.5 bg-blue-600 text-white rounded-lg font-bold text-[10px] shadow-md hover:bg-blue-700 transition-all active:scale-[0.98] uppercase tracking-widest">Terapkan Filter</button>
                                    </div>
                                </form>
                            </div>
                        </template>

                        <template x-if="sheetView === 'edit-profile'">
                            <div class="space-y-6 text-left">
                                <h3 class="text-base font-bold text-gray-900">Ubah Profil</h3>
                                <div class="space-y-6 pb-4">
                                    @include('profile.partials.update-profile-information-form')
                                    <hr class="border-gray-100">
                                    @include('profile.partials.update-password-form')
                                </div>
                            </div>
                        </template>

                        @stack('bottom-sheet')
                    </div>
                </div>
            </div>

                        <!-- Special: Admin Approval Form (Transactions) -->
                        <template x-if="sheetView === 'approve-tx' || sheetView === 'reject-tx'">
                            <div class="space-y-6">
                                <h3 class="text-base font-bold text-gray-900" x-text="sheetView === 'approve-tx' ? 'Setujui Transaksi' : 'Tolak Transaksi'"></h3>
                                <form :action="sheetView === 'approve-tx' ? '/admin/transactions/' + categoryId + '/approve' : '/admin/transactions/' + categoryId + '/reject'" method="POST" class="space-y-4">
                                    @csrf
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider ml-1">Catatan Admin (Wajib)</label>
                                        <textarea name="admin_note" required rows="4" placeholder="Tuliskan catatan atau alasan di sini..." class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50"></textarea>
                                    </div>
                                    <button type="submit" 
                                        :class="sheetView === 'approve-tx' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-rose-600 hover:bg-rose-700'"
                                        class="w-full py-4 text-white rounded-lg font-bold text-sm shadow-md transition-all active:scale-[0.98]" 
                                        x-text="sheetView === 'approve-tx' ? 'Konfirmasi Setujui' : 'Konfirmasi Tolak'">
                                    </button>
                                </form>
                            </div>
                        </template>

                        <!-- Special: Admin Approval Form (Deposits) -->
                        <template x-if="sheetView === 'approve-dp' || sheetView === 'reject-dp'">
                            <div class="space-y-6">
                                <h3 class="text-base font-bold text-gray-900" x-text="sheetView === 'approve-dp' ? 'Setujui Iuran' : 'Tolak Iuran'"></h3>
                                <form :action="sheetView === 'approve-dp' ? '/admin/deposits/' + categoryId + '/approve' : '/admin/deposits/' + categoryId + '/reject'" method="POST" class="space-y-4">
                                    @csrf
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider ml-1">Catatan Admin (Wajib)</label>
                                        <textarea name="admin_note" required rows="4" placeholder="Tuliskan catatan atau alasan di sini..." class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 bg-gray-50"></textarea>
                                    </div>
                                    <button type="submit" 
                                        :class="sheetView === 'approve-dp' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-rose-600 hover:bg-rose-700'"
                                        class="w-full py-4 text-white rounded-lg font-bold text-sm shadow-md transition-all active:scale-[0.98]" 
                                        x-text="sheetView === 'approve-dp' ? 'Konfirmasi Setujui' : 'Konfirmasi Tolak'">
                                    </button>
                                </form>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
