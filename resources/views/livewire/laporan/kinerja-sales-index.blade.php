<div class="space-y-6 font-jakarta" x-data="{ activeTab: 'penjualan' }">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-amber-50/90 p-4 rounded-b-2xl shadow-sm border-b border-amber-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">

            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2 bg-amber-100 rounded-lg text-amber-600 shadow-sm">
                    <i class="fas fa-trophy text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-amber-900 tracking-tight">Rapor Kinerja Sales</h1>
                    <p class="text-xs text-amber-700 font-medium mt-0.5">Analisa KPI & Target Tim.</p>
                </div>
                <div
                    class="hidden md:flex px-3 py-1 bg-white text-amber-600 rounded-lg text-[10px] font-bold border border-amber-100 items-center gap-2 shadow-sm">
                    <i class="fas fa-users"></i> {{ $laporan->total() }} Personil
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">

                <div class="relative w-full sm:w-auto">
                    <input type="month" wire:model.live="bulan"
                        class="w-full sm:w-36 border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-amber-500 py-2 pl-3 shadow-sm hover:bg-amber-50 cursor-pointer">
                </div>

                <div class="relative w-full sm:w-40" x-data="{ open: false, selected: @entangle('filterCabang').live }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border-white text-slate-700 px-3 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-amber-50 transition-all">
                        <span class="truncate"
                            x-text="selected.length > 0 ? selected.length + ' Cabang' : 'Semua Cabang'"></span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform"
                            :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-50 mt-1 w-48 bg-white border border-slate-200 rounded-lg shadow-xl p-2 max-h-60 overflow-y-auto custom-scrollbar"
                        style="display: none;">
                        <div @click="selected = []"
                            class="px-2 py-1.5 text-xs text-rose-500 font-bold cursor-pointer hover:bg-rose-50 rounded mb-1 flex items-center gap-1">
                            <i class="fas fa-times-circle"></i> Reset</div>
                        @foreach($optCabang as $c)
                        <div @click="selected.includes('{{ $c }}') ? selected = selected.filter(i => i !== '{{ $c }}') : selected.push('{{ $c }}')"
                            class="flex items-center px-2 py-1.5 hover:bg-amber-50 rounded cursor-pointer transition-colors group">
                            <div class="w-4 h-4 rounded border flex items-center justify-center mr-2"
                                :class="selected.includes('{{ $c }}') ? 'bg-amber-500 border-amber-500' : 'border-slate-300'">
                                <i x-show="selected.includes('{{ $c }}')"
                                    class="fas fa-check text-white text-[9px]"></i>
                            </div>
                            <span class="text-xs text-slate-600 truncate">{{ $c }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="relative w-full sm:w-40" x-data="{ open: false, selected: @entangle('filterDivisi').live }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border-white text-slate-700 px-3 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-amber-50 transition-all">
                        <span class="truncate"
                            x-text="selected.length > 0 ? selected.length + ' Divisi' : 'Semua Divisi'"></span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform"
                            :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open"
                        class="absolute z-50 mt-1 w-48 bg-white border border-slate-200 rounded-lg shadow-xl p-2 max-h-60 overflow-y-auto custom-scrollbar"
                        style="display: none;">
                        <div @click="selected = []"
                            class="px-2 py-1.5 text-xs text-rose-500 font-bold cursor-pointer hover:bg-rose-50 rounded mb-1 flex items-center gap-1">
                            <i class="fas fa-times-circle"></i> Reset</div>
                        @foreach($optDivisi as $d)
                        <div @click="selected.includes('{{ $d }}') ? selected = selected.filter(i => i !== '{{ $d }}') : selected.push('{{ $d }}')"
                            class="flex items-center px-2 py-1.5 hover:bg-amber-50 rounded cursor-pointer transition-colors group">
                            <div class="w-4 h-4 rounded border flex items-center justify-center mr-2"
                                :class="selected.includes('{{ $d }}') ? 'bg-amber-500 border-amber-500' : 'border-slate-300'">
                                <i x-show="selected.includes('{{ $d }}')"
                                    class="fas fa-check text-white text-[9px]"></i>
                            </div>
                            <span class="text-xs text-slate-600 truncate">{{ $d }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="hidden sm:block h-6 w-px bg-amber-200 mx-1"></div>

                <button wire:click="resetFilter"
                    class="px-3 py-2 bg-white border border-amber-200 text-amber-600 rounded-lg text-xs font-bold hover:bg-amber-50 shadow-sm"
                    title="Reset Filters"><i class="fas fa-undo"></i></button>

                <button wire:click="export" wire:loading.attr="disabled"
                    class="px-3 py-2 bg-gradient-to-r from-amber-500 to-yellow-600 text-white rounded-lg text-xs font-bold hover:from-amber-600 hover:to-yellow-700 shadow-md shadow-amber-500/20 flex items-center gap-2 transform hover:-translate-y-0.5">
                    <span wire:loading.remove wire:target="export"><i class="fas fa-file-excel"></i> Export</span>
                    <span wire:loading wire:target="export"><i class="fas fa-spinner fa-spin"></i> Proses...</span>
                </button>
                <!-- <div wire:loading class="text-amber-600 ml-1"><i class="fas fa-circle-notch fa-spin"></i></div> -->
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-center">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Total Target</p>
            <h3 class="text-xl font-bold text-slate-800">Rp {{ $this->formatCompact($globalSummary['total_target']) }}
            </h3>
        </div>
        <div
            class="bg-gradient-to-br from-emerald-500 to-teal-600 p-4 rounded-2xl shadow-lg shadow-emerald-500/20 text-white relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-emerald-100 text-[10px] font-bold uppercase tracking-wider mb-0.5">Total Realisasi</p>
                <h3 class="text-2xl font-extrabold">Rp {{ $this->formatCompact($globalSummary['total_real']) }}</h3>
                <div
                    class="mt-1 inline-flex items-center px-1.5 py-0.5 rounded bg-white/20 text-[10px] font-medium backdrop-blur-sm">
                    Ach:
                    {{ $globalSummary['total_target'] > 0 ? number_format(($globalSummary['total_real'] / $globalSummary['total_target']) * 100, 1) : 0 }}%
                </div>
            </div>
            <i
                class="fas fa-chart-line absolute right-3 top-3 text-white/20 text-5xl group-hover:scale-110 transition-transform"></i>
        </div>
        <div
            class="bg-white p-4 rounded-2xl shadow-sm border border-orange-100 flex flex-col justify-center group hover:border-orange-300 transition-colors">
            <p class="text-[10px] font-bold text-orange-400 uppercase tracking-wider mb-0.5">Total Piutang</p>
            <h3 class="text-xl font-bold text-slate-800">Rp {{ $this->formatCompact($globalSummary['total_ar']) }}</h3>
        </div>
        <div
            class="bg-white p-4 rounded-2xl shadow-sm border border-red-100 flex flex-col justify-center group hover:border-red-300 transition-colors">
            <p class="text-[10px] font-bold text-red-400 uppercase tracking-wider mb-0.5">Macet (>30 Hari)</p>
            <h3 class="text-xl font-bold text-red-600">Rp {{ $this->formatCompact($globalSummary['total_macet']) }}</h3>
        </div>
    </div>

    <div class="flex space-x-2 bg-slate-100 p-1.5 rounded-xl w-fit overflow-x-auto">
        <button @click="activeTab = 'penjualan'"
            :class="activeTab === 'penjualan' ? 'bg-white text-emerald-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap"><i
                class="fas fa-chart-line"></i> Penjualan</button>
        <button @click="activeTab = 'ar'"
            :class="activeTab === 'ar' ? 'bg-white text-orange-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap"><i
                class="fas fa-money-bill-wave"></i> Piutang (AR)</button>
        <button @click="activeTab = 'supplier'"
            :class="activeTab === 'supplier' ? 'bg-white text-purple-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap"><i
                class="fas fa-boxes-stacked"></i> Per Supplier</button>
        <button @click="activeTab = 'produktifitas'"
            :class="activeTab === 'produktifitas' ? 'bg-white text-blue-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap"><i
                class="fas fa-users-viewfinder"></i> Produktifitas</button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden min-h-[500px]">

        <div x-show="activeTab === 'penjualan'" x-transition>
            <div class="p-4 border-b border-slate-200 bg-emerald-50/30 flex justify-between items-center">
                <h3 class="font-bold text-emerald-800 text-sm">Target vs Pencapaian</h3>
            </div>
            <div class="overflow-auto custom-scrollbar h-[70vh]">
                <table class="w-full text-sm text-left border-collapse">
                    <thead
                        class="bg-slate-50 text-slate-500 font-bold text-xs uppercase border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="px-6 py-3.5 border-r border-slate-200 bg-slate-50">Nama Sales</th>
                            <th class="px-6 py-3.5 text-right border-r border-slate-200 bg-slate-50">Target (Rp)</th>
                            <th
                                class="px-6 py-3.5 text-right text-emerald-700 border-r border-slate-200 bg-emerald-50/50">
                                Pencapaian (Rp)</th>
                            <th class="px-6 py-3.5 text-center w-32 bg-slate-50">%</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($laporan as $row)
                        <tr class="hover:bg-emerald-50/10 transition-colors odd:bg-white even:bg-slate-50/30">
                            <td class="px-6 py-3 border-r border-slate-100">
                                <div class="font-bold text-slate-700">{{ $row['nama'] }}</div>
                                <div class="text-[10px] text-slate-400">{{ $row['cabang'] }}</div>
                            </td>
                            <td class="px-6 py-3 text-right text-slate-600 font-mono border-r border-slate-100">
                                {{ number_format($row['target_ims'], 0, ',', '.') }}</td>
                            <td
                                class="px-6 py-3 text-right font-bold text-emerald-700 font-mono border-r border-slate-100 bg-emerald-50/10">
                                {{ number_format($row['real_ims'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-center">
                                <span
                                    class="px-2 py-1 rounded text-xs font-bold {{ $row['persen_ims'] >= 100 ? 'bg-emerald-100 text-emerald-700' : ($row['persen_ims'] >= 80 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ number_format($row['persen_ims'], 1) }}%
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400">Tidak ada data penjualan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $laporan->links() }}</div>
        </div>

        <div x-show="activeTab === 'ar'" style="display: none;" x-transition>
            <div class="p-4 border-b border-slate-200 bg-orange-50/30">
                <h3 class="font-bold text-orange-800 text-sm">Monitoring Piutang</h3>
            </div>
            <div class="overflow-auto custom-scrollbar h-[70vh]">
                <table class="w-full text-sm text-left border-collapse">
                    <thead
                        class="bg-slate-50 text-slate-500 font-bold text-xs uppercase border-b border-orange-100 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="px-6 py-3.5 border-r border-slate-200 bg-slate-50">Nama Sales</th>
                            <th class="px-6 py-3.5 text-right text-emerald-600 border-r border-slate-200 bg-slate-50">AR
                                Reguler</th>
                            <th class="px-6 py-3.5 text-right text-red-600 border-r border-slate-200 bg-slate-50">AR >
                                30 Hari</th>
                            <th class="px-6 py-3.5 text-center bg-slate-50 w-32">% (Macet)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-orange-50/10 transition-colors odd:bg-white even:bg-slate-50/30">
                            <td class="px-6 py-3 border-r border-slate-100">
                                <div class="font-bold text-slate-700">{{ $row['nama'] }}</div>
                            </td>
                            <td class="px-6 py-3 text-right font-mono text-emerald-600 border-r border-slate-100">
                                {{ number_format($row['ar_lancar'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right font-mono text-red-600 font-bold border-r border-slate-100">
                                {{ number_format($row['ar_macet'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-center">
                                <span
                                    class="px-2 py-1 rounded text-xs font-bold {{ $row['ar_persen_macet'] > 5 ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ number_format($row['ar_persen_macet'], 1) }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $laporan->links() }}</div>
        </div>

        <div x-show="activeTab === 'supplier'" style="display: none;" x-transition>
            <div class="p-4 border-b border-slate-200 bg-purple-50/30">
                <h3 class="font-bold text-purple-800 text-sm">Penjualan By Supplier</h3>
            </div>
            <div class="overflow-auto custom-scrollbar h-[70vh]">
                <table class="w-full text-xs text-left whitespace-nowrap border-collapse min-w-max">
                    <thead
                        class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-purple-100 sticky top-0 z-20 shadow-sm">
                        <tr>
                            <th class="px-4 py-3 sticky left-0 bg-slate-50 border-r z-30 shadow-sm border-slate-200">
                                Nama Sales</th>
                            @foreach($topSuppliers as $supp)
                            <th class="px-4 py-3 text-right border-r border-slate-200 min-w-[120px] bg-slate-50">
                                {{ $supp }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-purple-50/10 transition-colors odd:bg-white even:bg-slate-50/30">
                            <td
                                class="px-4 py-2 font-bold text-slate-700 sticky left-0 bg-inherit border-r border-slate-100 z-10 shadow-sm border-r-2">
                                {{ $row['nama'] }}
                            </td>
                            @foreach($topSuppliers as $supp)
                            <td
                                class="px-4 py-2 text-right border-r border-slate-100 text-slate-600 hover:text-purple-700 transition-colors">
                                @php $val = $matrixSupplier[$row['nama']][$supp] ?? 0; @endphp
                                {{ $val > 0 ? number_format($val, 0, ',', '.') : '-' }}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $laporan->links() }}</div>
        </div>

        <div x-show="activeTab === 'produktifitas'" style="display: none;" x-transition>
            <div class="p-4 border-b border-slate-200 bg-blue-50/30">
                <h3 class="font-bold text-blue-800 text-sm">Produktifitas Sales</h3>
            </div>
            <div class="overflow-auto custom-scrollbar h-[70vh]">
                <table class="w-full text-sm text-left border-collapse">
                    <thead
                        class="bg-slate-50 text-slate-500 font-bold text-xs uppercase border-b border-blue-100 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="px-6 py-3.5 border-r border-slate-200 bg-slate-50">Nama Sales</th>
                            <th class="px-6 py-3.5 text-center border-r border-slate-200 bg-slate-50">Outlet Aktif (OA)
                            </th>
                            <th class="px-6 py-3.5 text-center bg-slate-50">Efektif Call (EC)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-blue-50/10 transition-colors odd:bg-white even:bg-slate-50/30">
                            <td class="px-6 py-3 border-r border-slate-100">
                                <div class="font-bold text-slate-700">{{ $row['nama'] }}</div>
                            </td>
                            <td
                                class="px-6 py-3 text-center font-bold text-blue-600 font-mono border-r border-slate-100">
                                {{ $row['real_oa'] }}</td>
                            <td class="px-6 py-3 text-center font-bold text-slate-600 font-mono">{{ $row['ec'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $laporan->links() }}</div>
        </div>

    </div>
</div>