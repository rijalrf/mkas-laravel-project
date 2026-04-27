<x-app-layout>
    <x-slot name="title">Profil</x-slot>

    <!-- HERO PROFILE CARD (SMALL & COMPACT) -->
    <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 flex items-center gap-4">
        <div class="w-16 h-16 rounded-full bg-blue-50 dark:bg-blue-900/30 overflow-hidden border-2 border-white dark:border-slate-700 shadow-sm shrink-0">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0ea5e9&color=fff&size=128" class="w-full h-full object-cover">
        </div>
        <div class="min-w-0">
            <h3 class="text-base font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</h3>
            <p class="text-xs text-gray-400 dark:text-slate-400 truncate">{{ Auth::user()->email }}</p>
            <div class="mt-1">
                <span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md text-[8px] font-black uppercase tracking-widest border border-blue-100 dark:border-blue-800">{{ Auth::user()->role }}</span>
            </div>
        </div>
    </div>

    <!-- MENU LIST (LIST STYLE) -->
    <div class="space-y-2">
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 overflow-hidden divide-y divide-gray-50 dark:divide-slate-700 shadow-sm">
            <!-- Informasi Akun (Direct Link) -->
            <a href="{{ route('profile.update-view') }}" class="w-full flex items-center justify-between p-4 hover:bg-gray-50 dark-hover transition-colors text-left group">
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 dark:text-slate-200">Informasi Akun</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            </a>

            <!-- Theme Switcher -->
            <div class="w-full flex items-center justify-between p-4" x-data="{ 
                current: localStorage.getItem('theme') || 'system',
                setTheme(t) {
                    this.current = t;
                    if(t === 'system') {
                        localStorage.removeItem('theme');
                        this.darkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    } else {
                        localStorage.setItem('theme', t);
                        this.darkMode = (t === 'dark');
                    }
                }
            }">
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 bg-indigo-50 dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 rounded-lg flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 1.035-.84 1.875-1.875 1.875S16.5 7.41 16.5 6.375 17.34 4.5 18.375 4.5s1.875.84 1.875 1.875zM6.25 8.25c0 1.035-.84 1.875-1.875 1.875S2.5 9.285 2.5 8.25 3.34 6.375 4.375 6.375s1.875.84 1.875 1.875zM18.75 18.75c0 1.035-.84 1.875-1.875 1.875s-1.875-.84-1.875-1.875.84-1.875 1.875-1.875 1.875.84 1.875 1.875zM6.75 15.25c0 1.035-.84 1.875-1.875 1.875S3 16.285 3 15.25s.84-1.875 1.875-1.875 1.875.84 1.875 1.875zM12 12.75c0 1.38-1.12 2.5-2.5 2.5s-2.5-1.12-2.5-2.5 1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5zM20.25 12.75c0 1.38-1.12 2.5-2.5 2.5s-2.5-1.12-2.5-2.5 1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5zM15.75 16.5c0 1.38-1.12 2.5-2.5 2.5s-2.5-1.12-2.5-2.5 1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5zM12.75 6.75c0 1.38-1.12 2.5-2.5 2.5s-2.5-1.12-2.5-2.5 1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5z" /></svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 dark:text-slate-200">Tema Aplikasi</span>
                </div>
                
                <div class="flex bg-gray-100 dark:bg-slate-900 p-1 rounded-xl gap-1 h-9 shadow-inner">
                    <button @click="setTheme('light')" :class="current === 'light' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-400 dark:text-slate-500'" class="px-3 rounded-lg transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M3 12h2.25m.386-6.364l1.591 1.591M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" /></svg>
                    </button>
                    <button @click="setTheme('dark')" :class="current === 'dark' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-400 dark:text-slate-500'" class="px-3 rounded-lg transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" /></svg>
                    </button>
                    <button @click="setTheme('system')" :class="current === 'system' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-400 dark:text-slate-500'" class="px-3 rounded-lg transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" /></svg>
                    </button>
                </div>
            </div>

            <!-- Master Rekening (Admin Only) -->
            @if(Auth::user()->role === 'admin')
            <a href="{{ route('payment-accounts.index') }}" class="w-full flex items-center justify-between p-4 hover:bg-gray-50 dark-hover transition-colors text-left group">
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-lg flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 dark:text-slate-200">Master Rekening</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-amber-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            </a>

            <!-- Master Kategori (Admin Only) -->
            <a href="{{ route('categories.index') }}" class="w-full flex items-center justify-between p-4 hover:bg-gray-50 dark-hover transition-colors text-left group">
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 006 8.25h2.25A2.25 2.25 0 0010.5 6V3.75a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 3.75V6zM3.75 15.75A2.25 2.25 0 006 18h2.25a2.25 2.25 0 002.25-2.25V13.5a2.25 2.25 0 00-2.25-2.25H6a2.25 2.25 0 00-2.25 2.25v2.25zM13.5 6A2.25 2.25 0 0115.75 3.75H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 dark:text-slate-200">Master Kategori</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            </a>

            <!-- Prioritas Pembayaran (Admin Only) -->
            <a href="{{ route('payment-plans.index') }}" class="w-full flex items-center justify-between p-4 hover:bg-gray-50 dark-hover transition-colors text-left group">
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 dark:text-slate-200">Prioritas Pembayaran</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            </a>
            @endif

            <!-- Kas Saya -->
            <a href="{{ route('history.my') }}" class="w-full flex items-center justify-between p-4 hover:bg-gray-50 dark-hover transition-colors text-left group">
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 dark:text-slate-200">Kas Saya</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-emerald-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <button type="submit" class="w-full flex items-center justify-between p-4 hover:bg-rose-50 dark-hover transition-colors text-left group">
                    <div class="flex items-center gap-4">
                        <div class="w-9 h-9 bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-lg flex items-center justify-center shrink-0 group-hover:bg-rose-600 dark:group-hover:bg-rose-500 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-slate-200 group-hover:text-rose-600 dark:group-hover:text-rose-400">Keluar Aplikasi</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                </button>
            </form>
        </div>
    </div>

    <!-- ADD ACCOUNT INFO VIEW TO LAYOUT SYSTEM -->
    <template x-if="sheetView === 'account-info'">
        <div class="space-y-6">
            <h3 class="text-base font-bold text-gray-900 dark:text-white">Detail Informasi Akun</h3>
            <div class="bg-gray-50 dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-700 overflow-hidden divide-y divide-gray-200 dark:divide-slate-700 shadow-inner">
                <div class="p-4 flex items-center justify-between">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nama Lengkap</span>
                    <span class="text-sm font-bold text-gray-800 dark:text-slate-200">{{ Auth::user()->name }}</span>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Email</span>
                    <span class="text-sm font-bold text-gray-800 dark:text-slate-200">{{ Auth::user()->email }}</span>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Role Sistem</span>
                    <span class="text-[10px] font-black px-2 py-0.5 bg-white dark:bg-slate-800 rounded border border-gray-200 dark:border-slate-700 text-blue-600 dark:text-blue-400 uppercase">{{ Auth::user()->role }}</span>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sejak</span>
                    <span class="text-sm font-bold text-gray-800 dark:text-slate-200">{{ Auth::user()->created_at->format('d M Y') }}</span>
                </div>
            </div>
            
            <button @click="sheetView = 'edit-profile'" class="w-full py-3.5 bg-blue-600 text-white rounded-lg font-bold text-sm shadow-md active:scale-[0.98] transition-all">
                Ubah Profil & Password
            </button>
        </div>
    </template>

</x-app-layout>
