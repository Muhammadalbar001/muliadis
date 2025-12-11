<div class="space-y-6 font-jakarta">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Collection / Pelunasan</h2>
            <p class="text-sm text-slate-500 mt-1">Data pembayaran piutang dari pelanggan (Tunai/Transfer/Giro).</p>
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
                <div
                    class="flex items-center gap-2 bg-slate-50 p-1 rounded-xl border border-slate-200 hover:border-cyan-300">
                    <input type="date" wire:model.live="startDate"
                        class="border-none bg-transparent text-xs font-bold text-slate-700 focus:ring-0 w-32 cursor-pointer">
                    <span class="text-slate-300">|</span>
                    <input type="date" wire:model.live="endDate"
                        class="border-none bg-transparent text-xs font-bold text-slate-700 focus:ring-0 w-32 cursor-pointer">
                </div>
            </div>

            <div class="w-full md:w-64 relative" x-data="{ open: false }">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Filter Cabang</label>
                <button @click="open = !open" @click.outside="open = false"
                    class="w-full flex items-center justify-between bg-white border border-slate-200 text-slate-700 py-2.5 px-3.5 rounded-xl text-xs font-bold hover:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-500/20 transition-all">
                    <span
                        class="truncate">{{ count($filterCabang) > 0 ? count($filterCabang).' Dipilih' : 'Semua Cabang' }}</span>
                    <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform"
                        :class="{'rotate-180': open}"></i>
                </button>
                <div x-show="open"
                    class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto p-2"
                    style="display: none;">
                    @foreach($optCabang as $cab)
                    <label
                        class="flex items-center px-2 py-2 hover:bg-cyan-50 rounded-lg cursor-pointer transition-colors">
                        <input type="checkbox" value="{{ $cab }}" wire:model.live="filterCabang"
                            class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500 h-4 w-4 mr-3">
                        <span class="text-xs font-medium text-slate-600">{{ $cab }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <button wire:click="resetFilter"
                class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-xl transition-colors h-[42px]">
                <i class="fas fa-undo text-xs"></i>
            </button>
        </div>
    </div>

    @if(isset($summary))
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div
            class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl p-5 text-white shadow-lg shadow-cyan-500/20 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-cyan-100 text-xs font-bold uppercase tracking-wider mb-1">Total Pelunasan Masuk</p>
                <h3 class="text-2xl font-extrabold tracking-tight">Rp
                    {{ number_format($summary['total_cair'], 0, ',', '.') }}</h3>
            </div>
            <div class="absolute right-4 top-4 text-white/20 group-hover:scale-110 transition-transform duration-500"><i
                    class="fas fa-money-bill-wave text-6xl rotate-12"></i></div>
        </div>

        <div
            class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between group hover:border-cyan-300 transition-colors">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Total Bukti Potong</p>
                <h3 class="text-2xl font-extrabold text-slate-800">
                    {{ number_format($summary['total_bukti'], 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600"><i
                    class="fas fa-receipt text-xl"></i></div>
        </div>

        <div
            class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between group hover:border-green-300 transition-colors">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Faktur Lunas</p>
                <h3 class="text-2xl font-extrabold text-slate-800">
                    {{ number_format($summary['total_faktur'], 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600"><i
                    class="fas fa-check-double text-xl"></i></div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[65vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-xs text-left border-collapse whitespace-nowrap w-full">
                <thead class="bg-slate-50 border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">Tgl Bayar
                        </th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">No Bukti</th>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200 min-w-[200px]">
                            Pelanggan (Klik Detail)</th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">Salesman</th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200 text-center">
                            Faktur</th>
                        <th
                            class="px-6 py-4 font-bold text-cyan-700 uppercase border-r border-slate-200 text-right bg-cyan-50/50">
                            Total Bayar</th>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase text-center bg-slate-50 sticky right-0 z-20 border-l border-slate-200">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($collections as $item)
                    <tr class="hover:bg-cyan-50/20 transition-colors group">
                        <td class="px-6 py-3 border-r border-slate-100 text-slate-600 font-medium">
                            {{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                        <td
                            class="px-6 py-3 border-r border-slate-100 font-mono text-cyan-700 font-bold group-hover:text-cyan-800">
                            {{ $item->receive_no }}</td>

                        <td class="px-6 py-3 border-r border-slate-100">
                            <button wire:click="openDetail('{{ $item->receive_no }}')"
                                class="text-left font-bold text-slate-700 hover:text-cyan-600 transition-colors flex items-center gap-2 w-full group/link relative">
                                <span class="truncate max-w-[250px]">{{ $item->nama_pelanggan }}</span>
                                <i
                                    class="fas fa-external-link-alt text-[10px] opacity-0 group-hover/link:opacity-100 text-slate-400 transition-opacity"></i>
                                <div wire:loading wire:target="openDetail('{{ $item->receive_no }}')"
                                    class="absolute right-0 text-cyan-500"><i class="fas fa-spinner fa-spin"></i></div>
                            </button>
                        </td>

                        <td class="px-6 py-3 border-r border-slate-100 text-slate-500">{{ $item->sales_name }}</td>

                        <td class="px-6 py-3 border-r border-slate-100 text-center">
                            <span
                                class="px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-600">
                                {{ $item->total_faktur }} Inv
                            </span>
                        </td>

                        <td
                            class="px-6 py-3 border-r border-slate-100 text-right font-extrabold text-slate-800 bg-cyan-50/10 group-hover:bg-cyan-50/20">
                            {{ number_format($item->total_bayar, 0, ',', '.') }}
                        </td>

                        <td
                            class="px-6 py-3 text-center sticky right-0 bg-white group-hover:bg-cyan-50/20 transition-colors">
                            <button wire:click="delete('{{ $item->receive_no }}')"
                                onclick="return confirm('Hapus Bukti Collection ini?') || event.stopImmediatePropagation()"
                                class="text-slate-300 hover:text-red-500 transition-colors"><i
                                    class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7"
                            class="px-6 py-24 text-center text-slate-400 flex flex-col items-center justify-center">
                            <i class="fas fa-wallet text-4xl mb-2 text-slate-200"></i>
                            <p>Tidak ada data pembayaran.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $collections->links() }}</div>
    </div>

    @if($isDetailOpen)
    <div class="fixed inset-0 z-[70] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" wire:click="closeDetail">
            </div>

            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full border border-white/20 relative">

                <div wire:loading.flex wire:target="closeDetail"
                    class="absolute inset-0 bg-white/50 z-50 items-center justify-center backdrop-blur-sm">
                    <span class="text-cyan-600 font-bold"><i class="fas fa-spinner fa-spin mr-2"></i> Menutup...</span>
                </div>

                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2"><i
                                class="fas fa-receipt text-cyan-600"></i> Rincian Pembayaran</h3>
                        <p class="text-slate-500 text-xs mt-0.5 font-mono">Bukti: {{ $selectedBukti }}</p>
                    </div>
                    <button wire:click="closeDetail"
                        class="w-8 h-8 rounded-full flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-slate-600 hover:bg-slate-50"><i
                            class="fas fa-times"></i></button>
                </div>

                <div class="px-6 py-6 bg-white max-h-[60vh] overflow-y-auto custom-scrollbar">
                    <table class="w-full text-xs text-left border-collapse">
                        <thead class="bg-slate-50 font-bold text-slate-500 uppercase border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3">No Invoice</th>
                                <th class="px-4 py-3">Penagih</th>
                                <th class="px-4 py-3 text-right">Jumlah Bayar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($detailItems as $d)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-2 font-mono text-cyan-600">{{ $d->invoice_no }}</td>
                                <td class="px-4 py-2 font-bold text-slate-700">{{ $d->penagih ?: '-' }}</td>
                                <td class="px-4 py-2 text-right font-bold text-cyan-600">
                                    {{ number_format($d->receive_amount, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-50 border-t border-slate-200 font-bold sticky bottom-0">
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-right text-slate-600 uppercase">Total Bayar</td>
                                <td class="px-4 py-3 text-right text-cyan-700 bg-cyan-50">Rp
                                    {{ number_format($detailItems->sum('receive_amount'), 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="bg-slate-50 px-6 py-3 flex justify-end border-t border-slate-200">
                    <button wire:click="closeDetail"
                        class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-slate-600 text-xs font-bold hover:bg-slate-50">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($isImportOpen)
    @include('livewire.partials.import-modal', ['title' => 'Import Collection', 'color' => 'cyan'])
    @endif

</div>