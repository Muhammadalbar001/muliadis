<div class="space-y-6 font-jakarta">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Collection / Pelunasan</h2>
            <p class="text-sm text-slate-500 mt-1">Data pembayaran piutang dari pelanggan (Giro/Tunai/Transfer).</p>
        </div>
        <button wire:click="openImportModal"
            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-cyan-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-file-import mr-2"></i> Import Collection
        </button>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:flex-1 relative group">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Pencarian</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400 group-focus-within:text-cyan-500 transition-colors"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-10 w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 placeholder-slate-400 transition-all"
                        placeholder="No Bukti / Pelanggan...">
                </div>
            </div>

            <div class="w-full md:w-auto">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Periode Bayar</label>
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
                    class="w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 text-slate-600 cursor-pointer">
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
                        <th class="px-6 py-4 border-r border-slate-200">Tanggal Bayar</th>
                        <th class="px-6 py-4 border-r border-slate-200">No Bukti</th>
                        <th class="px-6 py-4 border-r border-slate-200 min-w-[200px]">Nama Pelanggan</th>
                        <th class="px-6 py-4 border-r border-slate-200">Salesman</th>
                        <th class="px-6 py-4 border-r border-slate-200">Metode</th>
                        <th class="px-6 py-4 border-r border-slate-200 text-right bg-cyan-50 text-cyan-700">Jumlah Bayar
                        </th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($collections as $item)
                    <tr class="hover:bg-cyan-50/30 transition-colors group">
                        <td class="px-6 py-3 border-r border-slate-100 text-slate-600">
                            {{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 font-mono text-cyan-700 font-bold">
                            {{ $item->no_bukti }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 font-bold text-slate-700 truncate max-w-[250px]"
                            title="{{ $item->nama_pelanggan }}">{{ $item->nama_pelanggan }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 text-slate-500">{{ $item->sales_name }}</td>
                        <td class="px-6 py-3 border-r border-slate-100">
                            <span
                                class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 uppercase">{{ $item->payment_method ?? 'TUNAI' }}</span>
                        </td>
                        <td
                            class="px-6 py-3 border-r border-slate-100 text-right font-bold text-cyan-700 bg-cyan-50/10">
                            {{ number_format($item->receive_amount, 0, ',', '.') }}
                        </td>
                        <td
                            class="px-6 py-3 text-center sticky right-0 bg-white group-hover:bg-cyan-50/30 transition-colors">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Hapus data collection?') || event.stopImmediatePropagation()"
                                class="text-slate-300 hover:text-cyan-600 transition-colors"><i
                                    class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center text-slate-400">
                            <i class="fas fa-wallet text-3xl mb-2 text-slate-200"></i>
                            <p>Tidak ada data pembayaran.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $collections->links() }}</div>
    </div>

    @if($isImportOpen)
    @include('livewire.partials.import-modal', ['title' => 'Import Collection', 'color' => 'cyan'])
    @endif

</div>