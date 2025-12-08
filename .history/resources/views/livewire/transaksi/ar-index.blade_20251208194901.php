<div class="space-y-6 font-jakarta">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Monitoring Piutang (AR)</h2>
            <p class="text-sm text-slate-500">Daftar tagihan toko yang belum terbayar (Outstanding).</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="openImportModal"
                class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold rounded-xl shadow-sm transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-file-invoice-dollar mr-2"></i> Import AR
            </button>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2 relative">
                <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-10 w-full border-slate-200 rounded-xl text-sm focus:ring-orange-500"
                    placeholder="Cari Toko atau No Faktur...">
            </div>
            <div>
                <select wire:model.live="filterUmur"
                    class="w-full border-slate-200 rounded-xl text-xs focus:ring-orange-500">
                    <option value="">Semua Umur</option>
                    <option value="lancar">✅ Lancar (< 30 Hari)</option>
                    <option value="macet">⚠️ Macet (> 30 Hari)</option>
                </select>
            </div>
            <div>
                <select wire:model.live="filterCabang"
                    class="w-full border-slate-200 rounded-xl text-xs focus:ring-orange-500">
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
                        <th class="px-4 py-3">Tgl Faktur</th>
                        <th class="px-4 py-3">Jatuh Tempo</th>
                        <th class="px-4 py-3">No Dokumen</th>
                        <th class="px-4 py-3">Nama Toko</th>
                        <th class="px-4 py-3 text-center">Umur (Hari)</th>
                        <th class="px-4 py-3 text-right">Sisa Tagihan</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($ars as $item)
                    <tr class="hover:bg-orange-50/30 transition-colors">
                        <td class="px-4 py-2 border-r">{{ date('d/m/Y', strtotime($item->tgl_penjualan)) }}</td>
                        <td class="px-4 py-2 border-r text-slate-500">{{ date('d/m/Y', strtotime($item->jatuh_tempo)) }}
                        </td>
                        <td class="px-4 py-2 border-r font-mono text-orange-600 font-bold">{{ $item->no_faktur }}</td>
                        <td class="px-4 py-2 border-r font-medium">{{ $item->nama_pelanggan }}</td>
                        <td class="px-4 py-2 border-r text-center">
                            @php $umur = $item->umur_piutang ?? 0; @endphp
                            <span
                                class="px-2 py-0.5 rounded font-bold text-white text-[10px] {{ $umur > 30 ? 'bg-red-500' : 'bg-green-500' }}">
                                {{ $umur }} Hari
                            </span>
                        </td>
                        <td class="px-4 py-2 border-r text-right font-bold text-slate-800">
                            Rp {{ number_format($item->nilai, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 text-center">
                            <button wire:click="delete({{ $item->id }})" class="text-slate-300 hover:text-red-500"><i
                                    class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-slate-400">Tidak ada data piutang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-slate-50">{{ $ars->links() }}</div>
    </div>

    @if($isImportOpen) @include('livewire.partials.import-modal', ['title'=>'Import AR', 'color'=>'orange']) @endif
</div>