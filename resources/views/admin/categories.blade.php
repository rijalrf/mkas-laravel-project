<x-app-layout>
    <x-slot name="title">Master Kategori</x-slot>

    <!-- Header Actions -->
    <div class="flex items-center justify-between px-1">
        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Daftar Kategori</h4>
        <button @click="openSheet('add-category')" class="flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white rounded-lg font-bold text-[10px] uppercase tracking-widest shadow-md active:scale-95 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            <span>Tambah</span>
        </button>
    </div>

    <!-- CATEGORY LIST -->
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden divide-y divide-gray-50 shadow-sm">
        @foreach($categories as $cat)
            @php
                $staticCat = collect(\App\Models\Category::getStaticList())->firstWhere('id', $cat->id);
                $icon = $staticCat['icon'] ?? 'square';
                $color = $staticCat['color'] ?? 'slate';
            @endphp
            <div class="p-4 flex items-center justify-between transition-all active:bg-gray-50">
                <div class="flex items-center gap-4 text-left">
                    <div class="w-12 h-12 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center shrink-0 border border-gray-100 overflow-hidden">
                        @if($cat->image_url)
                            <img src="{{ $cat->image_url }}" class="w-full h-full object-cover">
                        @else
                            {!! \App\Models\Category::getIconHtml($icon, "w-6 h-6") !!}
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 uppercase tracking-tight">{{ $cat->name }}</p>
                        @if($cat->image_url)
                            <p class="text-[9px] text-blue-500 font-medium truncate w-32">Custom URL Aktif</p>
                        @else
                            <p class="text-[10px] text-gray-400 font-medium italic">Ikon standar</p>
                        @endif
                    </div>
                </div>
                
                <button @click="categoryId = '{{ $cat->id }}'; categoryName = '{{ $cat->name }}'; categoryUrl = '{{ $cat->image_url }}'; openSheet('edit-category')" class="p-2 text-gray-300 hover:text-blue-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                </button>
            </div>
        @endforeach
    </div>

    @push('bottom-sheet')
    <template x-if="sheetView === 'add-category' || sheetView === 'edit-category'">
        <div class="space-y-6 text-left" x-data="{ tempUrl: categoryUrl }">
            <h3 class="text-base font-bold text-gray-900" x-text="sheetView === 'add-category' ? 'Tambah Kategori Baru' : 'Ubah Kategori'"></h3>
            <form :action="sheetView === 'add-category' ? '{{ route('categories.store') }}' : '{{ url('/categories-management') }}/' + categoryId" method="POST" class="space-y-5">
                @csrf
                <template x-if="sheetView === 'edit-category'">
                    <input type="hidden" name="_method" value="PATCH">
                </template>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Kategori</label>
                    <input type="text" name="name" :value="sheetView === 'edit-category' ? categoryName : ''" required placeholder="Contoh: Kebutuhan Kantor" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 bg-gray-50">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Image URL (Optional)</label>
                    <input type="url" name="image_url" x-model="tempUrl" placeholder="https://..." 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 bg-gray-50">
                    <p class="text-[9px] text-gray-400 italic">Kosongkan jika ingin menggunakan ikon standar.</p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-xl font-bold text-sm shadow-md active:scale-95 transition-all uppercase tracking-widest"
                        x-text="sheetView === 'add-category' ? 'Simpan Kategori' : 'Simpan Perubahan'">
                    </button>
                </div>
            </form>
        </div>
    </template>
    @endpush

</x-app-layout>
