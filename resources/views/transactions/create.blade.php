<x-app-layout>
    <x-slot name="title">Kas {{ $type === 'IN' ? 'Masuk' : 'Keluar' }}</x-slot>

    <!-- FORM CARD -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="tx-form">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <!-- Category Display -->
            <div class="space-y-1.5">
                @php 
                    $staticList = \App\Models\Category::getStaticList();
                    $currentCat = collect($staticList)->firstWhere('id', (int)$selectedCategoryId) ?? collect($staticList)->last();
                @endphp
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Kategori Terpilih</label>
                <div class="w-full px-4 py-3 bg-{{ $currentCat['color'] ?? 'blue' }}-50 border border-{{ $currentCat['color'] ?? 'blue' }}-100 rounded-xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-{{ $currentCat['color'] ?? 'blue' }}-600 shadow-sm">
                            {!! \App\Models\Category::getIconHtml($currentCat['icon'] ?? 'dots-horizontal', "w-5 h-5") !!}
                        </div>
                        <span class="text-sm font-bold text-{{ $currentCat['color'] ?? 'blue' }}-700 uppercase tracking-tight">{{ $currentCat['name'] ?? 'Kategori Umum' }}</span>
                    </div>
                </div>
                <input type="hidden" name="category_id" value="{{ $currentCat['id'] }}">
            </div>

            <!-- Amount Input -->
            <div class="space-y-1.5" x-data="{ 
                formattedAmount: '{{ number_format((int)($amount ?? 0), 0, ',', '.') }}',
                updateAmount(val) {
                    let numeric = val.replace(/\D/g, '');
                    this.formattedAmount = numeric.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    document.getElementById('raw_amount').value = numeric;
                }
            }" x-init="if('{{ $amount }}') { updateAmount('{{ $amount }}') }">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nominal (Rp)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-gray-400">Rp</span>
                    <input type="text" x-model="formattedAmount" @input="updateAmount($event.target.value)" 
                        inputmode="numeric" required placeholder="0" 
                        class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg text-lg font-bold text-gray-800 focus:ring-2 focus:ring-blue-500 bg-gray-50 placeholder:text-gray-300 transition-all">
                    <input type="hidden" name="amount" id="raw_amount" value="{{ $amount ?? '' }}">
                </div>
                <x-input-error :messages="$errors->get('amount')" class="mt-1" />
            </div>

            <!-- Description -->
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Keterangan</label>
                <textarea name="description" required rows="3" placeholder="Contoh: Pembelian peralatan kantor..." 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 focus:ring-2 focus:ring-blue-500 bg-gray-50 transition-all">{{ old('description', $description ?? '') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-1" />
            </div>

            <!-- Photo Upload -->
            <div class="space-y-1.5" x-data="{ preview: null }">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Bukti Foto</label>
                <div class="relative group">
                    <label for="photo-input" class="cursor-pointer block">
                        <div id="photo-preview-box" class="w-full min-h-[120px] border-2 border-dashed border-gray-200 rounded-xl bg-gray-50 flex flex-col items-center justify-center gap-2 group-hover:bg-blue-50 group-hover:border-blue-200 transition-all overflow-hidden p-2">
                            <div x-show="!preview" class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-300 group-hover:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" /></svg>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Kamera atau Galeri</p>
                            </div>
                            <div x-show="preview" class="relative w-full h-full">
                                <img :src="preview" class="w-full h-40 object-cover rounded-lg shadow-sm">
                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-white text-[10px] font-bold uppercase">Ganti Foto</span>
                                </div>
                            </div>
                        </div>
                    </label>
                    <input type="file" name="photo" id="photo-input" class="hidden" accept="image/*" 
                        @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { preview = e.target.result; }; reader.readAsDataURL(file); }">
                </div>
                <x-input-error :messages="$errors->get('photo')" class="mt-1" />
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-lg font-bold text-sm shadow-md hover:bg-blue-700 active:scale-[0.98] transition-all uppercase tracking-widest">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
