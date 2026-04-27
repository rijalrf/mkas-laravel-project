@forelse($transactions as $tx)
    @php
        $tagColors = [
            'APPROVED' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
            'REJECTED' => 'bg-rose-50 text-rose-600 border-rose-100',
            'PENDING' => 'bg-blue-50 text-blue-600 border-blue-100',
        ];
        $colorClass = $tagColors[$tx->status] ?? $tagColors['PENDING'];
    @endphp

    <a href="{{ route('history.show', $tx->id) }}" class="transaction-item p-4 flex items-center justify-between transition-all active:bg-gray-50 border-b border-gray-50 last:border-0" data-description="{{ strtolower($tx->description) }}">
        <div class="flex flex-col gap-1 min-w-0 flex-1 text-left">
            <p class="text-[9px] font-bold {{ $tx->type === 'IN' ? 'text-blue-400' : 'text-rose-400' }} uppercase tracking-wider leading-none mb-0.5">
                {{ $tx->reference_number ?? ($tx->type === 'IN' ? 'MKASIN' : 'MKASOUT') }}
            </p>
            <p class="text-sm font-semibold text-gray-800 truncate leading-tight">{{ $tx->description }}</p>
            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">
                {{ $tx->category->name }} • {{ $tx->created_at->format('d M, H:i') }}
            </p>
        </div>

        <div class="flex flex-col items-end gap-2 shrink-0 ml-4">
            <span class="text-[8px] font-bold px-2 py-0.5 rounded-full uppercase tracking-widest border {{ $colorClass }}">
                {{ $tx->status }}
            </span>
            <p class="text-sm font-bold @if($tx->type == 'IN') text-blue-600 @else text-rose-600 @endif leading-none">
                {{ $tx->type == 'IN' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }}
            </p>
        </div>
    </a>
@empty
    @if(!request()->ajax())
        <x-empty-state 
            title="Belum Ada Transaksi" 
            message="Catatan kas masih kosong untuk saat ini."
        />
    @endif
@endforelse
