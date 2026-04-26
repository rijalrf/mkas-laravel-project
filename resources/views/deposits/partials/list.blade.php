@foreach($deposits as $dp)
    @php
        $tagColors = [
            'APPROVED' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
            'REJECTED' => 'bg-rose-50 text-rose-600 border-rose-100',
            'PENDING' => 'bg-blue-50 text-blue-600 border-blue-100',
        ];
        $colorClass = $tagColors[$dp->status] ?? $tagColors['PENDING'];
    @endphp
    <a href="{{ route('deposits.show', $dp->id) }}" class="p-4 flex items-center justify-between transition-all active:bg-gray-50 border-b border-gray-50 last:border-0">
        <div class="flex flex-col gap-1 min-w-0 flex-1 text-left">
            <p class="text-[9px] font-bold text-amber-500 uppercase tracking-wider leading-none mb-0.5">{{ $dp->reference_number ?? 'MKASDT' }}</p>
            <p class="text-sm font-semibold text-gray-800 truncate leading-tight">{{ $dp->user->name }}</p>
            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">
                Iuran {{ \Carbon\Carbon::parse($dp->month)->format('M Y') }} • {{ $dp->created_at->format('d M, H:i') }}
            </p>
        </div>

        <div class="flex flex-col items-end gap-2 shrink-0 ml-4">
            <span class="text-[8px] font-bold px-2 py-0.5 rounded-full uppercase tracking-widest border {{ $colorClass }}">
                {{ $dp->status }}
            </span>
            <p class="text-sm font-bold text-blue-600 leading-none">
                Rp {{ number_format($dp->amount, 0, ',', '.') }}
            </p>
        </div>
    </a>
@endforeach
