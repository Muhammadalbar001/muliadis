<div class="space-y-6 font-jakarta">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-white/90 p-4 rounded-b-2xl shadow-sm border-b border-slate-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">

            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div>
                    <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Monitoring Piutang (AR)</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar tagihan outstanding.</p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">

                <div class="relative w-full sm:w-48">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400 text-xs"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-8 w-full border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:ring-orange-500 py-2 shadow-sm placeholder-slate-400 transition-all"
                        placeholder="No Invoice / Pelanggan...">
                </div>

                <div class="w-full sm:w-36">
                    <select wire:model.live="filterCabang"
                        class="w-full border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:ring-orange-500 py-2 shadow-sm cursor-pointer bg-white hover:border-orange-300 transition-colors">
                        <option value="">Semua Cabang</option>
                        @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-36">
                    <select wire:model.live="filterUmur"
                        class="w-full border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:ring-orange-500 py-2 shadow-sm cursor-pointer bg-white hover:border-orange-300 transition-colors">
                        <option value="">Semua Status</option>
                        <option value="lancar">✅ Lancar (≤30)</option>
                        <option value="macet">⚠️ Macet (>30)</option>
                    </select>
                </div>

                <div class="hidden sm:block h-6 w-px bg-slate-300 mx-1"></div>

                <button wire:click="resetFilter"
                    class="px-3 py-2 bg-white border border-rose-200 text-rose-600 rounded-lg text-xs font-bold hover:bg-rose-50 shadow-sm transition-all flex items-center gap-2"
                    title="Reset Filter">
                    <i class="fas fa-undo"></i>
                </button>

                <button wire:click="openImportModal"
                    class="px-3 py-2 bg-gradient-to-r from-orange-500 to-amber-600 text-white rounded-lg text-xs font-bold hover:from-orange-600 hover:to-amber-700 shadow-md shadow-orange-500/20 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                    <i class="fas fa-file-invoice-dollar"></i> <span class="hidden sm:inline">Import</span>
                </button>

                <div wire:loading class="text-orange-600 ml-1"><i class="fas fa-circle-notch fa-spin"></i></div>
            </div>
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
                    class="fas fa-hand-holding-usd text-6xl rotate-12"></i></div>
        </div>

        <div
            class="bg-white rounded-2xl p-5 border border-red-100 shadow-sm flex items-center justify-between group hover:border-red-300 transition-colors">
            <div>
                <p class="text-red-500 text-xs font-bold uppercase tracking-wider mb-1">Piutang Macet (>30 Hari)</p>
                <h3 class="text-2xl font-extrabold text-red-600">Rp
                    {{ number_format($summary['total_macet'], 0, ',', '.') }}</h3>
                <span
                    class="text-[10px] text-red-500 font-bold bg-red-50 px-2 py-0.5 rounded-full mt-1 inline-block border border-red-100"><i
                        class="fas fa-exclamation-circle mr-1"></i> Perlu Follow Up</span>
            </div>
            <div
                class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center text-red-500 group-hover:bg-red-500 group-hover:text-white transition-all">
                <i class="fas fa-bell text-xl"></i></div>
        </div>

        <div
            class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between group hover:border-orange-300 transition-colors">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Faktur Belum Lunas</p>
                <h3 class="text-2xl font-extrabold text-slate-800">
                    {{ number_format($summary['total_faktur'], 0, ',', '.') }}</h3>
                <span
                    class="text-[10px] text-orange-600 font-bold bg-orange-50 px-2 py-0.5 rounded-full mt-1 inline-block border border-orange-100">Outstanding
                    Inv</span>
            </div>
            <div
                class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-slate-800 group-hover:text-white transition-all">
                <i class="fas fa-file-invoice text-xl"></i></div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[65vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-xs text-left border-collapse whitespace-nowrap w-full">
                <thead class="bg-slate-50 border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">Tgl Faktur
                        </th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">No Invoice
                        </th>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200 min-w-[200px]">
                            Pelanggan</th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">Salesman</th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">Jatuh Tempo
                        </th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200 text-center">
                            Umur (Hari)</th>
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
                            class="px-6 py-3 border-r border-slate-100 font-mono text-orange-700 font-bold group-hover:text-orange-800">
                            {{ $item->no_penjualan }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 font-bold text-slate-700 truncate max-w-[250px]"
                            title="{{ $item->pelanggan_name }}">{{ $item->pelanggan_name }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 text-slate-500">{{ $item->sales_name }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 text-slate-600">
                            {{ $item->jatuh_tempo ? date('d/m/Y', strtotime($item->jatuh_tempo)) : '-' }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 text-center">
                            @php $umur = $item->umur_piutang; @endphp
                            @if($umur > 30)
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600 border border-red-200 shadow-sm">{{ $umur }}
                                Hari</span>
                            @elseif($umur > 15)
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-600 border border-amber-200">{{ $umur }}
                                Hari</span>
                            @else
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-600 border border-emerald-200">{{ $umur }}
                                Hari</span>
                            @endif
                        </td>
                        <td
                            class="px-6 py-3 border-r border-slate-100 text-right font-extrabold text-slate-800 bg-orange-50/10 group-hover:bg-orange-50/20">
                            {{ number_format($item->nilai, 0, ',', '.') }}</td>
                        <td
                            class="px-6 py-3 text-center sticky right-0 bg-white group-hover:bg-orange-50/20 transition-colors">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Hapus data piutang ini?') || event.stopImmediatePropagation()"
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-300 hover:text-white hover:bg-red-500 transition-all shadow-sm"
                                title="Hapus Data"><i class="fas fa-trash-alt"></i></button>
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