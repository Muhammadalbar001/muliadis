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

    <div class="flex flex-col sm:flex-row gap-2 bg-white p-1 rounded-xl border border-gray-100 shadow-sm">

        <button @click="activeTab = 'ims'"
            :class="activeTab === 'ims' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700'"
            class="flex-1 py-3 px-4 rounded-lg text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2">
            <i class="fas fa-chart-line text-lg"></i>
            <span>Penjualan (IMS)</span>
        </button>

        <button @click="activeTab = 'ar'"
            :class="activeTab === 'ar' ? 'bg-orange-500 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700'"
            class="flex-1 py-3 px-4 rounded-lg text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2">
            <i class="fas fa-file-invoice-dollar text-lg"></i>
            <span>Kualitas Piutang (AR)</span>
        </button>

        <button @click="activeTab = 'prod'"
            :class="activeTab === 'prod' ? 'bg-emerald-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700'"
            class="flex-1 py-3 px-4 rounded-lg text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2">
            <i class="fas fa-users text-lg"></i>
            <span>Produktifitas (OA/EC)</span>
        </button>
    </div>

    <div x-show="activeTab === 'ims'" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-indigo-50/50 flex justify-between items-center">
                <h3 class="font-bold text-indigo-900">Rapor Penjualan (In-Market Sales)</h3>
                <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded font-bold">Target vs
                    Realisasi</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4 w-10 text-center">No</th>
                            <th class="px-6 py-4">Nama Salesman</th>
                            <th class="px-6 py-4 text-center">Cabang</th>
                            <th class="px-6 py-4 text-right">Target (Rp)</th>
                            <th class="px-6 py-4 text-right bg-indigo-50 text-indigo-900">Realisasi (Rp)</th>
                            <th class="px-6 py-4 text-center w-32">Pencapaian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-indigo-50/30 transition-colors">
                            <td class="px-6 py-4 text-center text-gray-400 font-medium">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $row['nama'] }}</td>
                            <td class="px-6 py-4 text-center text-xs text-gray-500 bg-gray-50">{{ $row['cabang'] }}</td>
                            <td class="px-6 py-4 text-right text-gray-500 font-mono">
                                {{ number_format($row['target_ims'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-bold text-indigo-700 bg-indigo-50/30 font-mono">
                                {{ number_format($row['real_ims'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center gap-2 justify-center">
                                    <span
                                        class="text-xs font-bold {{ $row['persen_ims'] >= 100 ? 'text-green-600' : ($row['persen_ims'] >= 80 ? 'text-indigo-600' : 'text-red-500') }}">
                                        {{ number_format($row['persen_ims'], 1) }}%
                                    </span>
                                    @if($row['persen_ims'] >= 100)
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    @endif
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                    <div class="h-1.5 rounded-full {{ $row['persen_ims'] >= 100 ? 'bg-green-500' : ($row['persen_ims'] >= 80 ? 'bg-indigo-500' : 'bg-red-500') }}"
                                        style="width: {{ min($row['persen_ims'], 100) }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'ar'" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100"
        style="display: none;">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-orange-50/50 flex justify-between items-center">
                <h3 class="font-bold text-orange-900">Kualitas Piutang (Account Receivable)</h3>
                <span class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded font-bold">Lancar vs Macet</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4 w-10 text-center">No</th>
                            <th class="px-6 py-4">Nama Salesman</th>
                            <th class="px-6 py-4 text-right text-green-600">AR Lancar (<30 Hari)</th>
                            <th class="px-6 py-4 text-right text-red-600 bg-red-50">AR Macet (>30 Hari)</th>
                            <th class="px-6 py-4 text-right font-bold text-gray-800">Total AR</th>
                            <th class="px-6 py-4 text-center w-32">Kesehatan AR</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-orange-50/30 transition-colors">
                            <td class="px-6 py-4 text-center text-gray-400">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $row['nama'] }}</td>
                            <td class="px-6 py-4 text-right font-mono text-green-600">
                                {{ number_format($row['ar_lancar'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-mono text-red-600 font-bold bg-red-50/30">
                                {{ number_format($row['ar_macet'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-gray-800">
                                {{ number_format($row['total_ar'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <div
                                    class="text-xs font-bold mb-1 {{ $row['persen_macet'] > 0 ? 'text-red-500' : 'text-green-500' }}">
                                    {{ number_format($row['persen_macet'], 1) }}% Macet
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-red-500 h-1.5 rounded-full"
                                        style="width: {{ min($row['persen_macet'], 100) }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'prod'" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100"
        style="display: none;">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-emerald-50/50 flex justify-between items-center">
                <h3 class="font-bold text-emerald-900">Produktifitas Distribusi</h3>
                <span class="text-xs bg-emerald-100 text-emerald-800 px-2 py-1 rounded font-bold">Outlet Active
                    (OA)</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4 w-10 text-center">No</th>
                            <th class="px-6 py-4">Nama Salesman</th>
                            <th class="px-6 py-4 text-center">Target OA</th>
                            <th class="px-6 py-4 text-center bg-emerald-50 text-emerald-900">Realisasi OA</th>
                            <th class="px-6 py-4 text-center">Ach OA (%)</th>
                            <th class="px-6 py-4 text-center text-gray-500">Effective Call (Faktur)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($laporan as $row)
                        <tr class="hover:bg-emerald-50/30 transition-colors">
                            <td class="px-6 py-4 text-center text-gray-400">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $row['nama'] }}</td>
                            <td class="px-6 py-4 text-center font-mono text-gray-500">
                                {{ number_format($row['target_oa']) }}</td>
                            <td
                                class="px-6 py-4 text-center font-bold text-emerald-700 bg-emerald-50/30 font-mono text-lg">
                                {{ number_format($row['real_oa']) }}</td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold {{ $row['persen_oa'] >= 100 ? 'bg-green-100 text-green-700' : ($row['persen_oa'] >= 80 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ number_format($row['persen_oa'], 0) }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-500 border-l border-dashed border-gray-200">
                                {{ number_format($row['ec']) }}
                                <span class="text-[10px] block text-gray-400">Nota Terbit</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>