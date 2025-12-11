<div class="space-y-6 font-jakarta" x-data="{ activeTab: 'penjualan' }">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-yellow-50/90 p-4 rounded-b-2xl shadow-sm border-b border-yellow-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">

            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2 bg-yellow-100 rounded-lg text-yellow-600 shadow-sm">
                    <i class="fas fa-trophy text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-yellow-900 tracking-tight">Rapor Kinerja Sales</h1>
                    <p class="text-xs text-yellow-700 font-medium mt-0.5">Analisa pencapaian target & KPI Tim.</p>
                </div>
                <div
                    class="hidden md:flex px-3 py-1 bg-white text-yellow-600 rounded-lg text-[10px] font-bold border border-yellow-100 items-center gap-2 shadow-sm">
                    <i class="fas fa-users"></i> {{ $laporan->total() }} Personil
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">

                <div class="relative w-full sm:w-auto">
                    <input type="month" wire:model.live="bulan"
                        class="w-full sm:w-36 border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-yellow-500 py-2 pl-3 shadow-sm hover:bg-yellow-50 cursor-pointer">
                </div>

                <div class="w-full sm:w-32">
                    <select wire:model.live="filterCabang"
                        class="w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-yellow-500 py-2 shadow-sm cursor-pointer bg-white hover:bg-yellow-50 transition-colors">
                        <option value="">Semua Cabang</option>
                        @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-32">
                    <select wire:model.live="filterDivisi"
                        class="w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-yellow-500 py-2 shadow-sm cursor-pointer bg-white hover:bg-yellow-50 transition-colors">
                        <option value="">Semua Divisi</option>
                        @foreach($optDivisi ?? [] as $d) <option value="{{ $d }}">{{ $d }}</option> @endforeach
                    </select>
                </div>

                <div class="hidden sm:block h-6 w-px bg-yellow-200 mx-1"></div>

                <button wire:click="export" wire:loading.attr="disabled"
                    class="px-3 py-2 bg-gradient-to-r from-yellow-500 to-amber-600 text-white rounded-lg text-xs font-bold hover:from-yellow-600 hover:to-amber-700 shadow-md shadow-yellow-500/20 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                    <span wire:loading.remove wire:target="export"><i class="fas fa-file-excel"></i> Export</span>
                    <span wire:loading wire:target="export"><i class="fas fa-spinner fa-spin"></i> Proses...</span>
                </button>

                <div wire:loading class="text-yellow-600 ml-1"><i class="fas fa-circle-notch fa-spin"></i></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-center">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Total Target</p>
            <h3 class="text-lg font-bold text-slate-800">Rp
                {{ number_format($globalSummary['total_target'], 0, ',', '.') }}</h3>
        </div>

        <div
            class="bg-gradient-to-br from-emerald-500 to-teal-600 p-4 rounded-2xl shadow-lg shadow-emerald-500/20 text-white relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-emerald-100 text-[10px] font-bold uppercase tracking-wider mb-0.5">Total Realisasi</p>
                <h3 class="text-xl font-extrabold">Rp {{ number_format($globalSummary['total_real'], 0, ',', '.') }}
                </h3>
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
            <h3 class="text-lg font-bold text-slate-800">Rp {{ number_format($globalSummary['total_ar'], 0, ',', '.') }}
            </h3>
        </div>

        <div
            class="bg-white p-4 rounded-2xl shadow-sm border border-red-100 flex flex-col justify-center group hover:border-red-300 transition-colors">
            <p class="text-[10px] font-bold text-red-400 uppercase tracking-wider mb-0.5">Macet (>30 Hari)</p>
            <h3 class="text-lg font-bold text-red-600">Rp
                {{ number_format($globalSummary['total_macet'], 0, ',', '.') }}</h3>
        </div>
    </div>

    <div class="flex space-x-2 bg-slate-100 p-1.5 rounded-xl w-fit overflow-x-auto">
        <button @click="activeTab = 'penjualan'"
            :class="activeTab === 'penjualan' ? 'bg-white text-emerald-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap"><i
                class="fas fa-chart-line"></i> Penjualan (IMS)</button>
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
                <h3 class="font-bold text-emerald-800 text-sm">Rapor Pencapaian Target (IMS)</h3>
            </div>

            <div class="overflow-auto custom-scrollbar h-[70vh]">
                <table class="w-full text-sm text-left border-collapse">
                    <thead
                        class="bg-slate-50 text-slate-500 font-bold text-xs uppercase border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="px-6 py-3.5 w-16 text-center border-r border-slate-200 bg-slate-50">Rank</th>
                            <th class="px-6 py-3.5 border-r border-slate-200 bg-slate-50">Nama Sales</th>
                            <th class="px-6 py-3.5 text-center border-r border-slate-200 bg-slate-50">Cabang</th>
                            <th class="px-6 py-3.5 text-right border-r border-slate-200 bg-slate-50">Target (Rp)</th>
                            <th
                                class="px-6 py-3.5 text-right text-emerald-700 border-r border-slate-200 bg-emerald-50/50">
                                Realisasi (Rp)</th>
                            <th class="px-6 py-3.5 text-right text-red-600 border-r border-slate-200 bg-slate-50">Kurang
                                (Defisit)</th>
                            <th class="px-6 py-3.5 text-center w-48 bg-slate-50">% Achievement</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($laporan as $index => $row)
                        <tr class="hover:bg-emerald-50/10 transition-colors odd:bg-white even:bg-slate-50/30">
                            <td class="px-6 py-3 text-center border-r border-slate-100 bg-white">
                                @if($laporan->firstItem() + $index == 1) <i
                                    class="fas fa-crown text-yellow-500 text-lg"></i>
                                @elseif($laporan->firstItem() + $index == 2) <i
                                    class="fas fa-medal text-slate-400 text-lg"></i>
                                @elseif($laporan->firstItem() + $index == 3) <i
                                    class="fas fa-medal text-amber-700 text-lg"></i>
                                @else <span
                                    class="font-bold text-slate-400">#{{ $laporan->firstItem() + $index }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 font-bold text-slate-700 border-r border-slate-100">{{ $row['nama'] }}
                            </td>
                            <td class="px-6 py-3 text-center text-xs text-slate-500 border-r border-slate-100">
                                {{ $row['cabang'] }}</td>
                            <td class="px-6 py-3 text-right text-slate-600 font-mono border-r border-slate-100">
                                {{ number_format($row['target_ims'], 0, ',', '.') }}</td>
                            <td
                                class="px-6 py-3 text-right font-bold text-emerald-700 font-mono border-r border-slate-100 bg-emerald-50/10">
                                {{ number_format($row['real_ims'], 0, ',', '.') }}</td>
                            <td
                                class="px-6 py-3 text-right font-medium text-red-500 font-mono border-r border-slate-100">
                                {{ $row['defisit'] > 0 ? '('.number_format($row['defisit'], 0, ',', '.').')' : '-' }}
                            </td>
                            <td class="px-6 py-3 align-middle">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-slate-200 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $row['persen_ims'] >= 100 ? 'bg-emerald-500' : ($row['persen_ims'] >= 80 ? 'bg-yellow-400' : 'bg-red-500') }}"
                                            style="width: {{ min($row['persen_ims'], 100) }}%"></div>
                                    </div>
                                    <span
                                        class="text-xs font-bold w-10 text-right">{{ number_format($row['persen_ims'], 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">Tidak ada data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $laporan->links() }}</div>
        </div>

        <div x-show="activeTab === 'ar'" style="display: none;" x-transition>
            <div class="p-4 border-b border-slate-200 bg-orange-50/30">
                <h3 class="font-bold text-orange-800 text-sm">Rapor Kualitas Kredit (Aging Schedule)</h3>
            </div>
            <div class="overflow-auto custom-scrollbar h-[70vh]">
                <table class="w-full text-sm text-left border-collapse">
                    <thead
                        class="bg-slate-50 text-slate-500 font-bold text-xs uppercase border-b border-orange-100 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="px-6 py-3.5 border-r border-slate-200 bg-slate-50">Nama Sales</th>
                            <th class="px-6 py-3.5 text-center border-r border-slate-200 bg-slate-50">Cabang</th>
                            <th class="px-6 py-3.5 text-right border-r border-slate-200 bg-slate-50">Total Piutang</th>
                            <th class="px-6 py-3.5 text-right text-emerald-600 border-r border-slate-200 bg-slate-50">AR
                                Lancar</th>
                            <th class="px-6 py-3.5 text-right text-red-600 border-r border-slate-200 bg-slate-50">AR
                                Macet (>30 Hari)</th>
                            <th class="px-6 py-3.5 text-center bg-slate-50">Rasio Macet</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-orange-50/10 transition-colors odd:bg-white even:bg-slate-50/30">
                            <td class="px-6 py-3 font-bold text-slate-700 border-r border-slate-100">{{ $row['nama'] }}
                            </td>
                            <td class="px-6 py-3 text-center text-xs border-r border-slate-100">{{ $row['cabang'] }}
                            </td>
                            <td class="px-6 py-3 text-right font-mono border-r border-slate-100">
                                {{ number_format($row['ar_total'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right font-mono text-emerald-600 border-r border-slate-100">
                                {{ number_format($row['ar_lancar'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right font-mono text-red-600 font-bold border-r border-slate-100">
                                {{ number_format($row['ar_macet'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-center">
                                <span
                                    class="px-2 py-1 rounded text-xs font-bold {{ $row['persen_macet'] > 5 ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ number_format($row['persen_macet'], 1) }}%
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
                <h3 class="font-bold text-purple-800 text-sm">Sebaran Penjualan per Principal (Lengkap)</h3>
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
                <h3 class="font-bold text-blue-800 text-sm">Rapor Outlet Active (OA) & Effective Call (EC)</h3>
            </div>
            <div class="overflow-auto custom-scrollbar h-[70vh]">
                <table class="w-full text-sm text-left border-collapse">
                    <thead
                        class="bg-slate-50 text-slate-500 font-bold text-xs uppercase border-b border-blue-100 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="px-6 py-3.5 border-r border-slate-200 bg-slate-50">Nama Sales</th>
                            <th class="px-6 py-3.5 text-center border-l border-r border-slate-200 bg-slate-50">Target OA
                            </th>
                            <th class="px-6 py-3.5 text-center text-blue-700 border-r border-slate-200 bg-blue-50/30">
                                Real OA</th>
                            <th class="px-6 py-3.5 text-center border-r border-slate-200 bg-slate-50">% Ach OA</th>
                            <th class="px-6 py-3.5 text-center bg-slate-50">Real EC (Faktur)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-blue-50/10 transition-colors odd:bg-white even:bg-slate-50/30">
                            <td class="px-6 py-3 font-bold text-slate-700 border-r border-slate-100">{{ $row['nama'] }}
                            </td>
                            <td class="px-6 py-3 text-center border-l border-r border-slate-100 font-mono">
                                {{ $row['target_oa'] }}</td>
                            <td
                                class="px-6 py-3 text-center font-bold text-blue-600 font-mono border-r border-slate-100 bg-blue-50/10">
                                {{ $row['real_oa'] }}</td>
                            <td class="px-6 py-3 text-center border-r border-slate-100">
                                <span
                                    class="font-bold {{ $row['persen_oa'] >= 100 ? 'text-emerald-600' : 'text-slate-600' }}">
                                    {{ number_format($row['persen_oa'], 0) }}%
                                </span>
                            </td>
                            <td class="px-6 py-3 text-center font-mono">{{ $row['ec'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $laporan->links() }}</div>
        </div>

    </div>
</div>