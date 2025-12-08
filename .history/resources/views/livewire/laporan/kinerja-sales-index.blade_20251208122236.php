<div class="space-y-6" x-data="{ activeTab: 'ims' }">

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <div class="flex flex-col md:flex-row justify-between items-end gap-4">
            <div class="flex flex-col md:flex-row gap-4 w-full md:w-3/4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Periode Bulan</label>
                    <input type="month" wire:model.live="bulan"
                        class="text-sm border-gray-200 rounded-lg focus:ring-indigo-500 w-full md:w-48 shadow-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Filter Cabang</label>
                    <select wire:model.live="filterCabang"
                        class="text-sm border-gray-200 rounded-lg focus:ring-indigo-500 w-full md:w-48 shadow-sm cursor-pointer">
                        <option value="">Semua Cabang</option>
                        @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                    </select>
                </div>
            </div>
            <div class="text-xs font-bold text-indigo-600" wire:loading>
                <i class="fas fa-circle-notch fa-spin mr-1"></i> Memuat Data...
            </div>
        </div>
    </div>

    <div
        class="flex flex-col sm:flex-row gap-2 bg-white p-1 rounded-xl border border-gray-100 shadow-sm overflow-x-auto">

        <button @click="activeTab = 'ims'"
            :class="activeTab === 'ims' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700'"
            class="flex-1 py-3 px-4 rounded-lg text-sm font-bold transition-all whitespace-nowrap flex items-center justify-center gap-2">
            <i class="fas fa-chart-line"></i> Penjualan (IMS)
        </button>

        <button @click="activeTab = 'ar'"
            :class="activeTab === 'ar' ? 'bg-orange-500 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700'"
            class="flex-1 py-3 px-4 rounded-lg text-sm font-bold transition-all whitespace-nowrap flex items-center justify-center gap-2">
            <i class="fas fa-file-invoice-dollar"></i> Piutang (AR)
        </button>

        <button @click="activeTab = 'prod'"
            :class="activeTab === 'prod' ? 'bg-emerald-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700'"
            class="flex-1 py-3 px-4 rounded-lg text-sm font-bold transition-all whitespace-nowrap flex items-center justify-center gap-2">
            <i class="fas fa-users"></i> Produktifitas (OA)
        </button>

        <button @click="activeTab = 'supp'"
            :class="activeTab === 'supp' ? 'bg-purple-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700'"
            class="flex-1 py-3 px-4 rounded-lg text-sm font-bold transition-all whitespace-nowrap flex items-center justify-center gap-2">
            <i class="fas fa-boxes"></i> Sales by Supplier
        </button>
    </div>

    <div x-show="activeTab === 'ims'" x-transition.opacity>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-indigo-50/50 flex justify-between items-center">
                <h3 class="font-bold text-indigo-900">Rapor Penjualan (IMS)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 w-10 text-center">No</th>
                            <th class="px-6 py-3">Nama Salesman</th>
                            <th class="px-6 py-3 text-center">Cabang</th>
                            <th class="px-6 py-3 text-right">Target (Rp)</th>
                            <th class="px-6 py-3 text-right bg-indigo-50 text-indigo-900">Realisasi (Rp)</th>
                            <th class="px-6 py-3 text-center">Ach %</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-indigo-50/30">
                            <td class="px-6 py-3 text-center text-gray-400">{{ $loop->iteration }}</td>
                            <td class="px-6 py-3 font-bold text-gray-800">{{ $row['nama'] }}</td>
                            <td class="px-6 py-3 text-center text-xs text-gray-500">{{ $row['cabang'] }}</td>
                            <td class="px-6 py-3 text-right font-mono text-gray-500">
                                {{ number_format($row['target_ims'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right font-mono font-bold text-indigo-700 bg-indigo-50/30">
                                {{ number_format($row['real_ims'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-center">
                                <span
                                    class="px-2 py-1 rounded text-xs font-bold {{ $row['persen_ims'] >= 100 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ number_format($row['persen_ims'], 1) }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'ar'" x-transition.opacity style="display: none;">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-orange-50/50 flex justify-between items-center">
                <h3 class="font-bold text-orange-900">Kualitas Piutang (AR)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">Nama Salesman</th>
                            <th class="px-6 py-3 text-right text-green-600">Lancar (<30)< /th>
                            <th class="px-6 py-3 text-right text-red-600 bg-red-50">Macet (>30)</th>
                            <th class="px-6 py-3 text-right font-bold">Total AR</th>
                            <th class="px-6 py-3 text-center">% Macet</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-orange-50/30">
                            <td class="px-6 py-3 font-medium text-gray-800">{{ $row['nama'] }}</td>
                            <td class="px-6 py-3 text-right font-mono text-green-600">
                                {{ number_format($row['ar_lancar'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right font-mono text-red-600 bg-red-50/30">
                                {{ number_format($row['ar_macet'], 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right font-mono font-bold">
                                {{ number_format($row['total_ar'], 0, ',', '.') }}</td>
                            <td
                                class="px-6 py-3 text-center font-bold text-xs {{ $row['persen_macet'] > 0 ? 'text-red-500' : 'text-green-500' }}">
                                {{ number_format($row['persen_macet'], 1) }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'prod'" x-transition.opacity style="display: none;">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-emerald-50/50">
                <h3 class="font-bold text-emerald-900">Produktifitas (OA)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">Nama Salesman</th>
                            <th class="px-6 py-3 text-center">Target OA</th>
                            <th class="px-6 py-3 text-center bg-emerald-50 text-emerald-900">Real OA</th>
                            <th class="px-6 py-3 text-center">Ach OA (%)</th>
                            <th class="px-6 py-3 text-center">Eff. Call</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-emerald-50/30">
                            <td class="px-6 py-3 font-medium text-gray-800">{{ $row['nama'] }}</td>
                            <td class="px-6 py-3 text-center font-mono">{{ number_format($row['target_oa']) }}</td>
                            <td class="px-6 py-3 text-center font-bold text-emerald-700 bg-emerald-50/30 font-mono">
                                {{ number_format($row['real_oa']) }}</td>
                            <td class="px-6 py-3 text-center">
                                <span
                                    class="px-2 py-1 rounded text-xs font-bold {{ $row['persen_oa'] >= 100 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ number_format($row['persen_oa'], 0) }}%
                                </span>
                            </td>
                            <td class="px-6 py-3 text-center font-mono text-gray-500">{{ number_format($row['ec']) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'supp'" x-transition.opacity style="display: none;">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-[70vh]">
            <div class="px-6 py-4 border-b border-gray-100 bg-purple-50/50 flex justify-between items-center">
                <h3 class="font-bold text-purple-900">Penjualan per Supplier</h3>
                <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded font-bold">Pivot View</span>
            </div>

            <div class="flex-1 overflow-auto">
                <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                    <thead class="text-gray-600 bg-gray-50 sticky top-0 z-20 font-bold uppercase shadow-sm">
                        <tr>
                            <th class="px-4 py-3 border-b border-r bg-gray-50 sticky left-0 z-30 min-w-[150px]">Nama
                                Salesman</th>

                            @foreach($listSuppliers as $supp)
                            <th class="px-4 py-3 border-b border-r min-w-[120px] text-right">{{ $supp }}</th>
                            @endforeach

                            <th
                                class="px-4 py-3 border-b border-r bg-purple-50 text-purple-900 text-right min-w-[120px]">
                                TOTAL</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-purple-50/30 transition-colors">
                            <td class="px-4 py-2 border-r font-bold text-gray-800 sticky left-0 bg-inherit z-10">
                                {{ $row['nama'] }}
                            </td>

                            @php $rowTotal = 0; @endphp
                            @foreach($listSuppliers as $supp)
                            @php
                            $val = $matrixSupplier[$row['nama']][$supp] ?? 0;
                            $rowTotal += $val;
                            @endphp
                            <td
                                class="px-4 py-2 border-r text-right font-mono {{ $val > 0 ? 'text-gray-700' : 'text-gray-300' }}">
                                {{ $val > 0 ? number_format($val, 0, ',', '.') : '-' }}
                            </td>
                            @endforeach

                            <td
                                class="px-4 py-2 border-r text-right font-bold text-purple-700 bg-purple-50/30 font-mono">
                                {{ number_format($rowTotal, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>