<div class="space-y-6 font-jakarta" x-data="{ activeTab: 'penjualan' }">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Rapor Kinerja Sales</h2>
            <p class="text-sm text-slate-500 mt-1">Analisa pencapaian target, kualitas kredit, dan produktifitas tim.
            </p>
        </div>
        <button wire:click="export" wire:loading.attr="disabled"
            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
            <span wire:loading.remove wire:target="export"><i class="fas fa-file-excel mr-2"></i> Download Rapor</span>
            <span wire:loading wire:target="export"><i class="fas fa-spinner fa-spin mr-2"></i> Processing...</span>
        </button>
    </div>

    <div
        class="bg-white p-5 rounded-2xl shadow-sm border border-indigo-100 flex flex-col md:flex-row justify-between items-end gap-4">
        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
            <div class="w-full md:w-40">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Periode Bulan</label>
                <input type="month" wire:model.live="bulan"
                    class="w-full border-indigo-100 rounded-xl text-sm focus:ring-indigo-500 font-bold text-indigo-700">
            </div>
            <div class="w-full md:w-40">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Filter Cabang</label>
                <select wire:model.live="filterCabang"
                    class="w-full border-indigo-100 rounded-xl text-sm focus:ring-indigo-500 text-slate-700">
                    <option value="">Semua Cabang</option>
                    @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                </select>
            </div>
            <div class="w-full md:w-40">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Filter Divisi</label>
                <select wire:model.live="filterDivisi"
                    class="w-full border-indigo-100 rounded-xl text-sm focus:ring-indigo-500 text-slate-700">
                    <option value="">Semua Divisi</option>
                    @foreach($optDivisi ?? [] as $d) <option value="{{ $d }}">{{ $d }}</option> @endforeach
                </select>
            </div>
        </div>
        <div class="text-right">
            <p class="text-xs text-slate-500">Salesman Terdaftar</p>
            <p class="text-2xl font-bold text-indigo-600">{{ $laporan->total() }} <span
                    class="text-sm font-normal text-slate-400">Orang</span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Target</p>
            <h3 class="text-lg font-bold text-slate-800">Rp
                {{ number_format($globalSummary['total_target'], 0, ',', '.') }}</h3>
        </div>

        <div
            class="bg-gradient-to-br from-emerald-500 to-teal-600 p-4 rounded-2xl shadow-lg shadow-emerald-500/20 text-white">
            <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider">Total Realisasi</p>
            <h3 class="text-lg font-extrabold">Rp {{ number_format($globalSummary['total_real'], 0, ',', '.') }}</h3>
            <div class="mt-1 text-xs font-medium bg-white/20 inline-block px-2 py-0.5 rounded">
                Ach:
                {{ $globalSummary['total_target'] > 0 ? number_format(($globalSummary['total_real'] / $globalSummary['total_target']) * 100, 1) : 0 }}%
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Piutang</p>
            <h3 class="text-lg font-bold text-orange-600">Rp
                {{ number_format($globalSummary['total_ar'], 0, ',', '.') }}</h3>
        </div>

        <div class="bg-white p-4 rounded-2xl shadow-sm border border-red-100">
            <p class="text-xs font-bold text-red-400 uppercase tracking-wider">Total Macet (>30 Hari)</p>
            <h3 class="text-lg font-bold text-red-600">Rp
                {{ number_format($globalSummary['total_macet'], 0, ',', '.') }}</h3>
        </div>
    </div>

    <div class="flex space-x-2 bg-slate-100 p-1.5 rounded-xl w-fit overflow-x-auto">
        <button @click="activeTab = 'penjualan'"
            :class="activeTab === 'penjualan' ? 'bg-white text-emerald-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2"><i
                class="fas fa-chart-line"></i> Penjualan (IMS)</button>
        <button @click="activeTab = 'ar'"
            :class="activeTab === 'ar' ? 'bg-white text-orange-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2"><i
                class="fas fa-money-bill-wave"></i> Piutang (AR)</button>
        <button @click="activeTab = 'supplier'"
            :class="activeTab === 'supplier' ? 'bg-white text-purple-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2"><i
                class="fas fa-boxes-stacked"></i> Per Supplier</button>
        <button @click="activeTab = 'produktifitas'"
            :class="activeTab === 'produktifitas' ? 'bg-white text-blue-600 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2"><i
                class="fas fa-users-viewfinder"></i> Produktifitas</button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden min-h-[500px]">

        <div x-show="activeTab === 'penjualan'" x-transition>
            <div class="p-4 border-b border-slate-100 bg-emerald-50/20">
                <h3 class="font-bold text-emerald-800">Rapor Pencapaian Target (IMS)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase border-b border-emerald-100">
                        <tr>
                            <th class="px-6 py-4 w-12 text-center">Rank</th>
                            <th class="px-6 py-4">Nama Sales</th>
                            <th class="px-6 py-4 text-center">Cabang</th>
                            <th class="px-6 py-4 text-right">Target (Rp)</th>
                            <th class="px-6 py-4 text-right text-emerald-700">Realisasi (Rp)</th>
                            <th class="px-6 py-4 text-right text-red-600">Kurang (Defisit)</th>
                            <th class="px-6 py-4 text-center w-40">% Achievement</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($laporan as $index => $row)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-3 text-center">
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
                            <td class="px-6 py-3 font-bold text-slate-700">{{ $row['nama'] }}</td>
                            <td class="px-6 py-3 text-center text-xs text-slate-500">{{ $row['cabang'] }}</td>
                            <td class="px-6 py-3 text-right text-slate-600 font-mono">
                                {{ number_format($row['target_ims'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right font-bold text-emerald-700 font-mono">
                                {{ number_format($row['real_ims'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right font-medium text-red-500 font-mono">
                                {{ $row['defisit'] > 0 ? '('.number_format($row['defisit'], 0, ',', '.').')' : '-' }}
                            </td>
                            <td class="px-6 py-3 align-middle">
                                <div class="w-full bg-slate-200 rounded-full h-2.5 mb-1">
                                    <div class="h-2.5 rounded-full {{ $row['persen_ims'] >= 100 ? 'bg-emerald-500' : ($row['persen_ims'] >= 80 ? 'bg-yellow-400' : 'bg-red-500') }}"
                                        style="width: {{ min($row['persen_ims'], 100) }}%"></div>
                                </div>
                                <div class="text-xs font-bold text-center">{{ number_format($row['persen_ims'], 1) }}%
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
            <div class="px-6 py-4 border-t border-slate-200">{{ $laporan->links() }}</div>
        </div>

        <div x-show="activeTab === 'ar'" style="display: none;" x-transition>
            <div class="p-4 border-b border-slate-100 bg-orange-50/20">
                <h3 class="font-bold text-orange-800">Rapor Kualitas Kredit (Aging Schedule)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase border-b border-orange-100">
                        <tr>
                            <th class="px-6 py-4">Nama Sales</th>
                            <th class="px-6 py-4 text-center">Cabang</th>
                            <th class="px-6 py-4 text-right">Total Piutang</th>
                            <th class="px-6 py-4 text-right text-emerald-600">AR Lancar</th>
                            <th class="px-6 py-4 text-right text-red-600">AR Macet (>30 Hari)</th>
                            <th class="px-6 py-4 text-center">Rasio Macet</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3 font-bold text-slate-700">{{ $row['nama'] }}</td>
                            <td class="px-6 py-3 text-center text-xs">{{ $row['cabang'] }}</td>
                            <td class="px-6 py-3 text-right font-mono">
                                {{ number_format($row['ar_total'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right font-mono text-emerald-600">
                                {{ number_format($row['ar_lancar'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right font-mono text-red-600 font-bold">
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
            <div class="px-6 py-4 border-t border-slate-200">{{ $laporan->links() }}</div>
        </div>

        <div x-show="activeTab === 'supplier'" style="display: none;" x-transition>
            <div class="p-4 border-b border-slate-100 bg-purple-50/20">
                <h3 class="font-bold text-purple-800">Sebaran Penjualan per Principal (Lengkap)</h3>
            </div>
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-xs text-left whitespace-nowrap">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase">
                        <tr>
                            <th class="px-4 py-3 sticky left-0 bg-slate-50 border-r z-10 shadow-sm">Nama Sales</th>
                            @foreach($topSuppliers as $supp)
                            <th class="px-4 py-3 text-right border-r min-w-[120px]">{{ $supp }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($laporan as $row)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td
                                class="px-4 py-2 font-bold text-slate-700 sticky left-0 bg-white border-r z-10 shadow-sm">
                                {{ $row['nama'] }}
                            </td>
                            @foreach($topSuppliers as $supp)
                            <td
                                class="px-4 py-2 text-right border-r text-slate-600 hover:text-purple-700 transition-colors">
                                @php $val = $matrixSupplier[$row['nama']][$supp] ?? 0; @endphp
                                {{ $val > 0 ? number_format($val, 0, ',', '.') : '-' }}
                            </td>
                            @endforeach
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ count($topSuppliers)+1 }}" class="p-8 text-center text-slate-400">Tidak ada
                                data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="activeTab === 'produktifitas'" style="display: none;" x-transition>
            <div class="p-4 border-b border-slate-100 bg-blue-50/20">
                <h3 class="font-bold text-blue-800">Rapor Outlet Active (OA) & Effective Call (EC)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase border-b border-blue-100">
                        <tr>
                            <th class="px-6 py-4">Nama Sales</th>
                            <th class="px-6 py-4 text-center border-l">Target OA</th>
                            <th class="px-6 py-4 text-center text-blue-700">Real OA</th>
                            <th class="px-6 py-4 text-center bg-blue-50 border-r">% Ach OA</th>
                            <th class="px-6 py-4 text-center">Real EC (Faktur)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3 font-bold text-slate-700">{{ $row['nama'] }}</td>
                            <td class="px-6 py-3 text-center border-l font-mono">{{ $row['target_oa'] }}</td>
                            <td class="px-6 py-3 text-center font-bold text-blue-600 font-mono">{{ $row['real_oa'] }}
                            </td>
                            <td class="px-6 py-3 text-center bg-blue-50/20 border-r">
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
            <div class="px-6 py-4 border-t border-slate-200">{{ $laporan->links() }}</div>
        </div>

    </div>
</div>