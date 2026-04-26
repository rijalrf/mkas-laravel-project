<x-app-layout>
    <x-slot name="title">Profil</x-slot>

    <!-- HERO PROFILE CARD (SMALL & COMPACT) -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-16 h-16 rounded-full bg-blue-50 overflow-hidden border-2 border-white shadow-sm shrink-0">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0ea5e9&color=fff&size=128" class="w-full h-full object-cover">
        </div>
        <div class="min-w-0">
            <h3 class="text-base font-bold text-gray-900 truncate">{{ Auth::user()->name }}</h3>
            <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
            <div class="mt-1">
                <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md text-[8px] font-black uppercase tracking-widest border border-blue-100">{{ Auth::user()->role }}</span>
            </div>
        </div>
    </div>

    <!-- MENU LIST (LIST STYLE) -->
    <div class="space-y-2">
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden divide-y divide-gray-50 shadow-sm">
            <!-- Informasi Akun (Direct Link) -->
            <a href="{{ route('profile.update-view') }}" class="w-full flex items-center justify-between p-4 hover:bg-gray-50 transition-colors text-left group">
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">Informasi Akun</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            </a>

            <!-- Master Rekening (Admin Only) -->
            @if(Auth::user()->role === 'admin')
            <a href="{{ route('payment-accounts.index') }}" class="w-full flex items-center justify-between p-4 hover:bg-gray-50 transition-colors text-left group">
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">Master Rekening</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-amber-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            </a>

            <!-- Master Kategori (Admin Only) -->
            <a href="{{ route('categories.index') }}" class="w-full flex items-center justify-between p-4 hover:bg-gray-50 transition-colors text-left group">
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 006 8.25h2.25A2.25 2.25 0 0010.5 6V3.75a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 3.75V6zM3.75 15.75A2.25 2.25 0 006 18h2.25a2.25 2.25 0 002.25-2.25V13.5a2.25 2.25 0 00-2.25-2.25H6a2.25 2.25 0 00-2.25 2.25v2.25zM13.5 6A2.25 2.25 0 0115.75 3.75H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">Master Kategori</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            </a>
            @endif

            <!-- Kas Saya -->
            <a href="{{ route('history.my') }}" class="w-full flex items-center justify-between p-4 hover:bg-gray-50 transition-colors text-left group">
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">Kas Saya</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-emerald-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <button type="submit" class="w-full flex items-center justify-between p-4 hover:bg-rose-50 transition-colors text-left group">
                    <div class="flex items-center gap-4">
                        <div class="w-9 h-9 bg-rose-50 text-rose-600 rounded-lg flex items-center justify-center shrink-0 group-hover:bg-rose-600 group-hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-rose-600">Keluar Aplikasi</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                </button>
            </form>
        </div>
    </div>

    <!-- ADD ACCOUNT INFO VIEW TO LAYOUT SYSTEM -->
    <template x-if="sheetView === 'account-info'">
        <div class="space-y-6">
            <h3 class="text-base font-bold text-gray-900">Detail Informasi Akun</h3>
            <div class="bg-gray-50 rounded-xl border border-gray-100 overflow-hidden divide-y divide-gray-200 shadow-inner">
                <div class="p-4 flex items-center justify-between">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nama Lengkap</span>
                    <span class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</span>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Email</span>
                    <span class="text-sm font-bold text-gray-800">{{ Auth::user()->email }}</span>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Role Sistem</span>
                    <span class="text-[10px] font-black px-2 py-0.5 bg-white rounded border border-gray-200 text-blue-600 uppercase">{{ Auth::user()->role }}</span>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sejak</span>
                    <span class="text-sm font-bold text-gray-800">{{ Auth::user()->created_at->format('d M Y') }}</span>
                </div>
            </div>
            
            <button @click="sheetView = 'edit-profile'" class="w-full py-3.5 bg-blue-600 text-white rounded-lg font-bold text-sm shadow-md active:scale-[0.98] transition-all">
                Ubah Profil & Password
            </button>
        </div>
    </template>

</x-app-layout>
