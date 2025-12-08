<div class="space-y-6 font-jakarta">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Transaksi Collection</h2>
            <p class="text-sm text-slate-500">Daftar pelunasan tagihan dari toko.</p>
        </div>
        <button wire:click="openImportModal"
            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all hover:-translate-y-0.5">
            <i class="fas fa-file-excel mr-2"></i> Import Collection
        </button>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex flex-col md:flex-row gap-4 items-end">

            <div class="w-full md:flex-1 relative">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Pencarian</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-10 w-full border-slate-200 rounded-xl text-sm focus:ring-blue-500"
                        placeholder="No Bukti / Nama Toko...">
                </div>
            </div>

            <div class="w-full md:w-auto">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Tgl Bayar</label>
                <div class="flex items-center gap-2">
                    <input type="date" wire:model.live="startDate"
                        class="border-slate-200 rounded-xl text-xs w-32 focus:ring-blue-500">
                    <span class="text-slate-400">-</span>
                    <input type="date" wire:model.live="endDate"
                        class="border-slate-200 rounded-xl text-xs w-32 focus:ring-blue-500">
                </div>
            </div>

            <div class="w-full md:w-48" x-data="{ open: false }">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Cabang</label>
                <div class="relative">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border border-slate-200 text-slate-700 py-2.5 px-3 rounded-xl text-xs focus:ring-2 focus:ring-blue-500">
                        <span
                            class="truncate">{{ count($filterCabang) > 0 ? count($filterCabang).' Dipilih' : 'Semua Cabang' }}</span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto p-2">
                        @foreach($optCabang as $c)
                        <label class="flex items-center px-2 py-2 hover:bg-blue-50 rounded-lg cursor-pointer">
                            <input type="checkbox" value="{{ $c }}" wire:model.live="filterCabang"
                                class="rounded border-slate-300 text-blue-600 mr-2">
                            <span class="text-xs text-slate-700">{{ $c }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="w-full md:w-48" x-data="{ open: false }">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Penagih (Sales)</label>
                <div class="relative">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border border-slate-200 text-slate-700 py-2.5 px-3 rounded-xl text-xs focus:ring-2 focus:ring-blue-500">
                        <span
                            class="truncate">{{ count($filterSales) > 0 ? count($filterSales).' Sales' : 'Semua Sales' }}</span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto p-2">
                        @foreach($optSales as $s)
                        <label class="flex items-center px-2 py-2 hover:bg-blue-50 rounded-lg cursor-pointer">
                            <input type="checkbox" value="{{ $s }}" wire:model.live="filterSales"
                                class="rounded border-slate-300 text-blue-600 mr-2">
                            <span class="text-xs text-slate-700">{{ Str::limit($s, 15) }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <button wire:click="resetFilter"
                class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl" title="Reset">
                <i class="fas fa-undo"></i>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 flex flex-col h-[70vh]">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-xs text-left border-collapse whitespace-nowrap w-full">
                <thead
                    class="text-slate-500 uppercase bg-slate-50 font-bold border-b border-slate-200 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-3 border-r">Tgl Bayar</th>
                        <th class="px-4 py-3 border-r">No Bukti</th>
                        <th class="px-4 py-3 border-r">Nama Toko</th>
                        <th class="px-4 py-3 border-r">Salesman</th>
                        <th class="px-4 py-3 border-r text-center">Metode</th>
                        <th class="px-4 py-3 border-r text-right bg-blue-50 text-blue-900">Total Bayar</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($collections as $item)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="px-4 py-2 border-r">{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                        <td class="px-4 py-2 border-r font-mono text-blue-600 font-bold">{{ $item->no_bukti }}</td>
                        <td class="px-4 py-2 border-r font-medium text-slate-800 truncate max-w-[200px]">
                            {{ $item->nama_pelanggan }}</td>
                        <td class="px-4 py-2 border-r text-slate-500">{{ $item->sales_name }}</td>
                        <td class="px-4 py-2 border-r text-center text-slate-500">Tunai/Transfer</td>
                        <td class="px-4 py-2 border-r text-right font-bold text-emerald-600">
                            Rp {{ number_format((float)$item->receive_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 text-center">
                            <button wire:click="delete({{ $item->id }})"
                                @click="$dispatch('confirm-delete', { method: 'deleteCollection', id: {{ $item->id }} })"
                                class="text-slate-300 hover:text-red-500"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">Belum ada pelunasan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-slate-50">{{ $collections->links() }}</div>
    </div>

    @if($isImportOpen) @include('livewire.partials.import-modal', ['title'=>'Import Collection', 'color'=>'blue'])
    @endif
</div>