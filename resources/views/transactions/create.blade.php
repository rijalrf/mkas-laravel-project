<x-app-layout>
    <x-slot name="title">Kas {{ $type === 'IN' ? 'Masuk' : 'Keluar' }}</x-slot>

    <!-- FORM CARD -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <!-- Category Display (Static List Mapping) -->
            <div class="space-y-1.5">
                @php 
                    $currentCat = collect(\App\Models\Category::getStaticList())->firstWhere('id', $selectedCategoryId);
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
                <input type="hidden" name="category_id" value="{{ $selectedCategoryId }}">
            </div>

            <!-- Amount Input with Mask -->
            <div class="space-y-1.5" x-data="{ 
                formattedAmount: '',
                updateAmount(val) {
                    let numeric = val.replace(/\D/g, '');
                    this.formattedAmount = numeric.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    document.getElementById('raw_amount').value = numeric;
                }
            }">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nominal (Rp)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-gray-400">Rp</span>
                    <input type="text" x-model="formattedAmount" @input="updateAmount($event.target.value)" 
                        inputmode="numeric" required placeholder="0" 
                        class="w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-lg text-lg font-bold text-gray-800 focus:ring-2 focus:ring-blue-500 bg-gray-50 placeholder:text-gray-300 transition-all">
                    <input type="hidden" name="amount" id="raw_amount">
                </div>
                <x-input-error :messages="$errors->get('amount')" class="mt-1" />
            </div>

            <!-- Description -->
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Keterangan</label>
                <textarea name="description" required rows="3" placeholder="Contoh: Pembelian peralatan kantor..." 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 focus:ring-2 focus:ring-blue-500 bg-gray-50 transition-all">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-1" />
            </div>

            <!-- File Upload with Preview & Compression -->
            <div class="space-y-1.5" x-data="{ preview: null }">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Bukti Foto</label>
                <div class="relative group">
                    <!-- Standard Input with Choice Hint -->
                    <label for="photo-input" class="cursor-pointer block">
                        <div id="photo-preview-box" class="w-full min-h-[120px] border-2 border-dashed border-gray-200 rounded-xl bg-gray-50 flex flex-col items-center justify-center gap-2 group-hover:bg-blue-50 group-hover:border-blue-200 transition-all overflow-hidden p-2">
                            <template x-if="!preview">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-300 group-hover:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" /></svg>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Kamera atau Galeri</p>
                                </div>
                            </template>
                            <template x-if="preview">
                                <div class="relative w-full h-full">
                                    <img :src="preview" class="w-full h-40 object-cover rounded-lg shadow-sm">
                                    <div class="absolute inset-0 bg-black/20 flex items-center justify-center rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="text-white text-[10px] font-bold uppercase">Ganti Foto</span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </label>
                    <input type="file" required id="photo-input" class="hidden" accept="image/*" capture="environment">
                    <input type="hidden" name="photo_base64" id="compressed_photo">
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

    @push('scripts')
    <script>
        // Image Preview & Compression
        document.getElementById('photo-input').onchange = function(evt) {
            const file = evt.target.files[0];
            if (!file) return;

            // Show Preview
            const reader = new FileReader();
            reader.onload = (e) => {
                // Alpine data binding fallback (using standard JS since it's outside x-data)
                // We'll use a better approach by putting it inside the script
            };

            // Compress Logic
            const imgReader = new FileReader();
            imgReader.readAsDataURL(file);
            imgReader.onload = event => {
                const img = new Image();
                img.src = event.target.result;
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    const MAX_WIDTH = 800;
                    const MAX_HEIGHT = 800;
                    let width = img.width;
                    let height = img.height;

                    if (width > height) {
                        if (width > MAX_WIDTH) {
                            height *= MAX_WIDTH / width;
                            width = MAX_WIDTH;
                        }
                    } else {
                        if (height > MAX_HEIGHT) {
                            width *= MAX_HEIGHT / height;
                            height = MAX_HEIGHT;
                        }
                    }
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);
                    
                    // Convert to base64
                    const dataurl = canvas.toDataURL('image/jpeg', 0.7);
                    document.getElementById('compressed_photo').value = dataurl;
                    
                    // Update Preview UI manually for responsiveness
                    const previewBox = document.getElementById('photo-preview-box');
                    previewBox.innerHTML = `<img src="${dataurl}" class="w-full h-32 object-cover rounded-lg shadow-sm">`;
                };
            };
        };
    </script>
    @endpush
</x-app-layout>
