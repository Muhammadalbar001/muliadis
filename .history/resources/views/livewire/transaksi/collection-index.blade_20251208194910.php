<div class="space-y-6 font-jakarta">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Rekap Pelunasan (Collection)</h2>
            <p class="text-sm text-slate-500">Daftar pembayaran masuk dari toko.</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="openImportModal"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-file-excel mr-2"></i> Import Collection
            </button>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2 relative">
                <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-10 w-full border-slate-200 rounded-xl text-sm focus:ring-blue-500"
                    placeholder="Cari No Bukti, Toko...">
            </div>
            <div class="flex items-center gap-2">
                <input type="date" wire:model.live="startDate" class="w-full border-slate-200 rounded-xl text-xs">
                <span class="text-slate-400">-</span>
                <input type="date" wire:model.live="endDate" class="w-full border-slate-200 rounded-xl text-xs">
            </div>
            <div>
                <select wire:model.live="filterCabang"
                    class="w-full border-slate-200 rounded-xl text-xs focus:ring-blue-500">
                    <option value="">Semua Cabang</option>
                    @foreach($optCabang ?? [] as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 flex flex-col h-[70vh]">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-xs text-left border-collapse whitespace-nowrap w-full">
                <thead
                    class="text-slate-500 uppercase bg-slate-50 font-bold border-b border-slate-200 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-3">Tgl Bayar</th>
                        <th class="px-4 py-3">No Bukti</th>
                        <th class="px-4 py-3">Toko</th>
                        <th class="px-4 py-3">Salesman</th>
                        <th class="px-4 py-3 text-center">Metode</th>
                        <th class="px-4 py-3 text-right">Jumlah Bayar</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($collections as $item)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="px-4 py-2 border-r">{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                        <td class="px-4 py-2 border-r font-mono text-blue-600 font-bold">{{ $item->no_bukti }}</td>
                        <td class="px-4 py-2 border-r font-medium">{{ $item->nama_pelanggan }}</td>
                        <td class="px-4 py-2 border-r">{{ $item->sales_name }}</td>
                        <td class="px-4 py-2 border-r text-center text-slate-500">Tunai/Transfer</td>
                        <td class="px-4 py-2 border-r text-right font-bold text-emerald-600">
                            Rp {{ number_format($item->receive_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 text-center">
                            <button wire:click="delete({{ $item->id }})" class="text-slate-300 hover:text-red-500"><i
                                    class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-slate-400">Belum ada pelunasan.</td>
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