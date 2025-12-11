<div class="space-y-6 font-jakarta">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Monitoring Piutang (AR)</h2>
            <p class="text-sm text-slate-500 mt-1">Daftar tagihan pelanggan yang belum terbayar (Outstanding).</p>
        </div>
        <button wire:click="openImportModal"
            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-orange-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-file-invoice-dollar mr-2"></i> Import AR
        </button>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:flex-1 relative group">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Pencarian</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i
                            class="fas fa-search text-slate-400 group-focus-within:text-orange-500 transition-colors"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-10 w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 placeholder-slate-400 transition-all"
                        placeholder="No Invoice / Nama Toko...">
                </div>
            </div>

            <div class="w-full md:w-48">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Status Umur</label>
                <select wire:model.live="filterUmur"
                    class="w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-slate-600 cursor-pointer">
                    <option value="">Semua</option>
                    <option value="lancar">✅ Lancar (<= 30 Hari)</option>
                    <option value="macet">⚠️ Macet (> 30 Hari)</option>
                </select>
            </div>

            <div class="w-full md:w-64 relative" x-data="{ open: false }">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5 ml-1">Filter Cabang</label>
                <button @click="open = !open" @click.outside="open = false"
                    class="w-full flex items-center justify-between bg-white border border-slate-200 text-slate-700 py-2.5 px-3.5 rounded-xl text-xs font-bold hover:border-orange-300 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition-all">
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
                        class="flex items-center px-2 py-2 hover:bg-orange-50 rounded-lg cursor-pointer transition-colors">
                        <input type="checkbox" value="{{ $cab }}" wire:model.live="filterCabang"
                            class="rounded border-slate-300 text-orange-600 focus:ring-orange-500 h-4 w-4 mr-3">
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
            class="bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl p-5 text-white shadow-lg shadow-orange-500/20 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-orange-100 text-xs font-bold uppercase tracking-wider mb-1">Total Sisa Piutang</p>
                <h3 class="text-2xl font-extrabold tracking-tight">Rp
                    {{ number_format($summary['total_piutang'], 0, ',', '.') }}</h3>
            </div>
            <div class="absolute right-4 top-4 text-white/20 group-hover:scale-110 transition-transform duration-500"><i
                    class="fas fa-file-invoice-dollar text-6xl rotate-12"></i></div>
        </div>

        <div
            class="bg-white rounded-2xl p-5 border border-red-100 shadow-sm flex items-center justify-between group hover:border-red-300 transition-colors">
            <div>
                <p class="text-red-500 text-xs font-bold uppercase tracking-wider mb-1">Potensi Macet (>30 Hari)</p>
                <h3 class="text-2xl font-extrabold text-red-600">Rp
                    {{ number_format($summary['total_macet'], 0, ',', '.') }}</h3>
                <span
                    class="text-[10px] text-red-500 font-bold bg-red-50 px-2 py-0.5 rounded-full mt-1 inline-block">Harap
                    Follow Up!</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center text-red-500"><i
                    class="fas fa-exclamation-triangle text-xl"></i></div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Jumlah Faktur Belum Lunas</p>
                <h3 class="text-2xl font-extrabold text-slate-800">
                    {{ number_format($summary['total_faktur'], 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500"><i
                    class="fas fa-list-ol text-xl"></i></div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[65vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-xs text-left border-collapse whitespace-nowrap w-full">
                <thead class="bg-slate-50 border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">Tanggal</th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">No Invoice
                        </th>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200 min-w-[200px]">
                            Pelanggan</th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">Salesman</th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">Jatuh Tempo
                        </th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200 text-center">
                            Umur</th>
                        <th
                            class="px-6 py-4 font-bold text-orange-700 uppercase border-r border-slate-200 text-right bg-orange-50/50">
                            Sisa Piutang</th>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase text-center bg-slate-50 sticky right-0 z-20 border-l border-slate-200">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($ars as $item)
                    <tr class="hover:bg-orange-50/20 transition-colors group">
                        <td class="px-6 py-3 border-r border-slate-100 text-slate-600 font-medium">
                            {{ date('d/m/Y', strtotime($item->tgl_penjualan)) }}</td>
                        <td
                            class="px-6 py-3 border-r border-slate-100 font-mono text-orange-600 font-bold group-hover:text-orange-700">
                            {{ $item->no_penjualan }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 font-bold text-slate-700 truncate max-w-[250px]"
                            title="{{ $item->pelanggan_name }}">{{ $item->pelanggan_name }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 text-slate-500">{{ $item->sales_name }}</td>

                        <td class="px-6 py-3 border-r border-slate-100 text-slate-600">
                            {{ $item->jatuh_tempo ? date('d/m/Y', strtotime($item->jatuh_tempo)) : '-' }}
                        </td>

                        <td class="px-6 py-3 border-r border-slate-100 text-center">
                            @php $umur = $item->umur_piutang; @endphp
                            @if($umur > 30)
                            <span
                                class="px-2 py-0.5 rounded-full bg-red-100 text-red-600 border border-red-200 text-[10px] font-bold">{{ $umur }}
                                Hari</span>
                            @elseif($umur > 15)
                            <span
                                class="px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-600 border border-yellow-200 text-[10px] font-bold">{{ $umur }}
                                Hari</span>
                            @else
                            <span
                                class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-600 border border-emerald-200 text-[10px] font-bold">{{ $umur }}
                                Hari</span>
                            @endif
                        </td>

                        <td
                            class="px-6 py-3 border-r border-slate-100 text-right font-extrabold text-slate-800 bg-orange-50/10 group-hover:bg-orange-50/20">
                            {{ number_format($item->nilai, 0, ',', '.') }}
                        </td>

                        <td
                            class="px-6 py-3 text-center sticky right-0 bg-white group-hover:bg-orange-50/20 transition-colors">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Hapus data piutang ini?') || event.stopImmediatePropagation()"
                                class="text-slate-300 hover:text-red-500 transition-colors"><i
                                    class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8"
                            class="px-6 py-24 text-center text-slate-400 flex flex-col items-center justify-center">
                            <i class="fas fa-check-circle text-4xl mb-2 text-emerald-200"></i>
                            <p>Tidak ada tagihan piutang.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $ars->links() }}</div>
    </div>

    @if($isImportOpen)
    @include('livewire.partials.import-modal', ['title' => 'Import Piutang (AR)', 'color' => 'orange'])
    @endif

</div>