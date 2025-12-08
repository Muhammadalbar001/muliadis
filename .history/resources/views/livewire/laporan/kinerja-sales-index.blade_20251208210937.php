<div class="space-y-6 font-jakarta" x-data="{ activeTab: 'penjualan' }">

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex flex-col md:flex-row gap-4 justify-between items-end">
            <div class="flex gap-3 w-full md:w-auto">
                <div class="w-1/2 md:w-32">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Bulan</label>
                    <select wire:model.live="filterBulan"
                        class="w-full border-slate-200 rounded-xl text-sm focus:ring-indigo-500 font-bold text-slate-700">
                        @for($i=1; $i<=12; $i++) <option value="{{ sprintf('%02d', $i) }}">
                            {{ date('F', mktime(0, 0, 0, $i, 10)) }}</option> @endfor
                    </select>
                </div>
                <div class="w-1/2 md:w-24">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Tahun</label>
                    <select wire:model.live="filterTahun"
                        class="w-full border-slate-200 rounded-xl text-sm focus:ring-indigo-500 font-bold text-slate-700">
                        @for($y=date('Y'); $y>=2023; $y--) <option value="{{ $y }}">{{ $y }}</option> @endfor
                    </select>
                </div>
                <div class="w-full md:w-48">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Cabang</label>
                    <select wire:model.live="filterCabang"
                        class="w-full border-slate-200 rounded-xl text-sm focus:ring-indigo-500 text-slate-700">
                        <option value="">Semua Cabang</option>
                        @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                    </select>
                </div>
            </div>
            <button
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-lg shadow-indigo-200 text-sm font-bold transition">
                <i class="fas fa-print mr-2"></i> Cetak Rapor
            </button>
        </div>
    </div>

    <div class="flex space-x-1 bg-slate-100 p-1 rounded-xl w-fit">
        <button @click="activeTab = 'penjualan'"
            :class="activeTab === 'penjualan' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2">
            <i class="fas fa-chart-line"></i> Penjualan (Omzet)
        </button>

        <button @click="activeTab = 'ar'"
            :class="activeTab === 'ar' ? 'bg-white text-orange-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2">
            <i class="fas fa-money-bill-wave"></i> Piutang (AR)
        </button>

        <button @click="activeTab = 'supplier'"
            :class="activeTab === 'supplier' ? 'bg-white text-purple-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2">
            <i class="fas fa-boxes-stacked"></i> Per Supplier
        </button>

        <button @click="activeTab = 'produktifitas'"
            :class="activeTab === 'produktifitas' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2">
            <i class="fas fa-users-viewfinder"></i> Produktifitas
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden min-h-[500px]">

        <div x-show="activeTab === 'penjualan'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="px-6 py-4 border-b border-slate-100 bg-emerald-50/30 flex justify-between items-center">
                <h3 class="font-bold text-emerald-800">Rapor Pencapaian Omzet (IMS)</h3>
                <span class="text-xs text-emerald-600 bg-white px-2 py-1 rounded border border-emerald-200">Ranked by
                    Ach %</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50 font-bold">
                        <tr>
                            <th class="px-6 py-3 w-12 text-center">Rank</th>
                            <th class="px-6 py-3">Nama Sales</th>
                            <th class="px-6 py-3 text-center">Cabang</th>
                            <th class="px-6 py-3 text-right">Target (Rp)</th>
                            <th class="px-6 py-3 text-right">Realisasi (Rp)</th>
                            <th class="px-6 py-3 text-center">Achievement</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($data as $idx => $row)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3 text-center font-bold text-slate-400">#{{ $loop->iteration }}</td>
                            <td class="px-6 py-3 font-bold text-slate-700">{{ $row['sales_name'] }}</td>
                            <td class="px-6 py-3 text-center text-xs text-slate-500">{{ $row['cabang'] }}</td>
                            <td class="px-6 py-3 text-right font-mono text-slate-600">
                                {{ number_format($row['t_omzet'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right font-mono font-bold text-emerald-700">
                                {{ number_format($row['r_omzet'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 align-middle w-48">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-slate-100 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $row['ach_omzet'] >= 100 ? 'bg-emerald-500' : ($row['ach_omzet'] >= 80 ? 'bg-yellow-400' : 'bg-red-500') }}"
                                            style="width: {{ min($row['ach_omzet'], 100) }}%"></div>
                                    </div>
                                    <span
                                        class="text-xs font-bold w-12 text-right">{{ number_format($row['ach_omzet'], 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="activeTab === 'ar'" style="display: none;" x-transition:enter="transition ease-out duration-300">
            <div class="px-6 py-4 border-b border-slate-100 bg-orange-50/30">
                <h3 class="font-bold text-orange-800">Rapor Kualitas Kredit (Aging AR)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50 font-bold">
                        <tr>
                            <th class="px-6 py-3">Nama Sales</th>
                            <th class="px-6 py-3 text-center">Cabang</th>
                            <th class="px-6 py-3 text-right">Total Piutang</th>
                            <th class="px-6 py-3 text-right text-emerald-600">AR Lancar</th>
                            <th class="px-6 py-3 text-right text-red-600">AR Macet (>30)</th>
                            <th class="px-6 py-3 text-center">% Macet (Bad Debt)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($data as $row)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3 font-bold text-slate-700">{{ $row['sales_name'] }}</td>
                            <td class="px-6 py-3 text-center text-xs">{{ $row['cabang'] }}</td>
                            <td class="px-6 py-3 text-right font-mono">
                                {{ number_format($row['ar_total'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right font-mono text-emerald-600">
                                {{ number_format($row['ar_lancar'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right font-mono text-red-600 font-bold">
                                {{ number_format($row['ar_macet'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-center">
                                <span
                                    class="px-2 py-1 rounded text-xs font-bold {{ $row['ar_pct'] > 5 ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ number_format($row['ar_pct'], 1) }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="activeTab === 'supplier'" style="display: none;"
            x-transition:enter="transition ease-out duration-300">
            <div class="px-6 py-4 border-b border-slate-100 bg-purple-50/30">
                <h3 class="font-bold text-purple-800">Kontribusi Penjualan per Principal</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left whitespace-nowrap">
                    <thead class="text-slate-500 uppercase bg-slate-50 font-bold">
                        <tr>
                            <th class="px-4 py-3 sticky left-0 bg-slate-50 z-10 border-r">Nama Sales</th>
                            @foreach($topSuppliers as $supp)
                            <th class="px-4 py-3 text-right border-r min-w-[120px]">{{ Str::limit($supp, 12) }}</th>
                            @endforeach
                            <th class="px-4 py-3 text-right bg-purple-50 text-purple-800">Lainnya</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($data as $row)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-2 font-bold text-slate-700 sticky left-0 bg-white border-r">
                                {{ $row['sales_name'] }}</td>
                            @foreach($topSuppliers as $supp)
                            <td class="px-4 py-2 text-right border-r text-slate-600">
                                {{ isset($row['suppliers'][$supp]) && $row['suppliers'][$supp] > 0 ? number_format($row['suppliers'][$supp]/1000000, 1, ',', '.') . ' Jt' : '-' }}
                            </td>
                            @endforeach
                            <td class="px-4 py-2 text-right bg-purple-50/30 font-bold text-purple-700">
                                {{ number_format($row['suppliers']['Others']/1000000, 1, ',', '.') }} Jt
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="activeTab === 'produktifitas'" style="display: none;"
            x-transition:enter="transition ease-out duration-300">
            <div class="px-6 py-4 border-b border-slate-100 bg-blue-50/30">
                <h3 class="font-bold text-blue-800">Rapor Produktifitas (OA & EC)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50 font-bold">
                        <tr>
                            <th class="px-6 py-3">Nama Sales</th>
                            <th class="px-6 py-3 text-center border-l">Target OA</th>
                            <th class="px-6 py-3 text-center">Real OA</th>
                            <th class="px-6 py-3 text-center border-r bg-blue-50">% Ach OA</th>

                            <th class="px-6 py-3 text-center">Target EC</th>
                            <th class="px-6 py-3 text-center">Real EC (Faktur)</th>
                            <th class="px-6 py-3 text-center bg-blue-50">% Ach EC</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($data as $row)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3 font-bold text-slate-700">{{ $row['sales_name'] }}</td>

                            <td class="px-6 py-3 text-center border-l font-mono">{{ $row['t_oa'] }}</td>
                            <td class="px-6 py-3 text-center font-bold text-blue-600 font-mono">{{ $row['r_oa'] }}</td>
                            <td class="px-6 py-3 text-center border-r bg-blue-50/20">
                                <span
                                    class="px-2 py-1 rounded text-xs font-bold {{ $row['ach_oa'] >= 100 ? 'bg-blue-100 text-blue-700' : 'bg-slate-200 text-slate-600' }}">
                                    {{ number_format($row['ach_oa'], 0) }}%
                                </span>
                            </td>

                            <td class="px-6 py-3 text-center font-mono">{{ $row['t_ec'] }}</td>
                            <td class="px-6 py-3 text-center font-bold text-indigo-600 font-mono">{{ $row['r_ec'] }}
                            </td>
                            <td class="px-6 py-3 text-center bg-blue-50/20">
                                <span
                                    class="px-2 py-1 rounded text-xs font-bold {{ $row['ach_ec'] >= 100 ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-200 text-slate-600' }}">
                                    {{ number_format($row['ach_ec'], 0) }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>