<div class="space-y-6 font-jakarta">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Retur Barang</h2>
            <p class="text-sm text-slate-500 mt-1">Daftar pengembalian barang dari pelanggan.</p>
        </div>
        <button wire:click="openImportModal"
            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-700 hover:to-pink-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-rose-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-file-import mr-2"></i> Import Retur
        </button>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:flex-1 relative group">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Pencarian</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400 group-focus-within:text-rose-500 transition-colors"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-10 w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 placeholder-slate-400 transition-all"
                        placeholder="No Retur / Pelanggan...">
                </div>
            </div>

            <div class="w-full md:w-auto">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Periode</label>
                <div class="flex items-center gap-2 bg-slate-50 p-1 rounded-xl border border-slate-200">
                    <input type="date" wire:model.live="startDate"
                        class="border-none bg-transparent text-xs font-bold text-slate-700 focus:ring-0 w-32 cursor-pointer">
                    <span class="text-slate-300">|</span>
                    <input type="date" wire:model.live="endDate"
                        class="border-none bg-transparent text-xs font-bold text-slate-700 focus:ring-0 w-32 cursor-pointer">
                </div>
            </div>

            <div class="w-full md:w-48">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Cabang</label>
                <select wire:model.live="filterCabang"
                    class="w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 text-slate-600 cursor-pointer">
                    <option value="">Semua Cabang</option>
                    @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                </select>
            </div>

            <button wire:click="resetFilter"
                class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-xl transition-colors h-[42px]">
                <i class="fas fa-undo"></i>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[70vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-xs text-left border-collapse whitespace-nowrap w-full">
                <thead
                    class="text-slate-500 uppercase bg-slate-50 font-bold border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-6 py-4 border-r border-slate-200">Tanggal</th>
                        <th class="px-6 py-4 border-r border-slate-200">No Retur</th>
                        <th class="px-6 py-4 border-r border-slate-200 min-w-[200px]">Pelanggan</th>
                        <th class="px-6 py-4 border-r border-slate-200">Salesman</th>
                        <th class="px-6 py-4 border-r border-slate-200 text-center">Cabang</th>
                        <th class="px-6 py-4 border-r border-slate-200 text-right bg-rose-50 text-rose-700">Nilai Retur
                        </th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($returs as $item)
                    <tr class="hover:bg-rose-50/30 transition-colors group">
                        <td class="px-6 py-3 border-r border-slate-100 text-slate-600">
                            {{ date('d/m/Y', strtotime($item->tgl_retur)) }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 font-mono text-rose-600 font-bold">
                            {{ $item->no_retur }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 font-bold text-slate-700 truncate max-w-[250px]"
                            title="{{ $item->nama_pelanggan }}">{{ $item->nama_pelanggan }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 text-slate-500">{{ $item->sales_name }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 text-center">
                            <span
                                class="px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-600">{{ $item->cabang }}</span>
                        </td>
                        <td
                            class="px-6 py-3 border-r border-slate-100 text-right font-bold text-rose-600 bg-rose-50/10">
                            {{ number_format($item->total_grand, 0, ',', '.') }}
                        </td>
                        <td
                            class="px-6 py-3 text-center sticky right-0 bg-white group-hover:bg-rose-50/30 transition-colors">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Hapus data retur?') || event.stopImmediatePropagation()"
                                class="text-slate-300 hover:text-rose-500 transition-colors"><i
                                    class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center text-slate-400">
                            <i class="fas fa-box-open text-3xl mb-2 text-slate-200"></i>
                            <p>Tidak ada data retur.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $returs->links() }}</div>
    </div>

    @if($isImportOpen)
    @include('livewire.partials.import-modal', ['title' => 'Import Retur', 'color' => 'rose'])
    @endif
</div>