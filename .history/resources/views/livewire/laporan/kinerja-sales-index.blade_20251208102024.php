<div class="space-y-8">

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 sticky top-0 z-30">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Pilih Bulan</label>
                <input type="month" wire:model.live="bulan"
                    class="text-sm border-gray-200 rounded-lg focus:ring-indigo-500 w-full md:w-48">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Filter Cabang</label>
                <select wire:model.live="filterCabang"
                    class="text-sm border-gray-200 rounded-lg focus:ring-indigo-500 w-full md:w-48">
                    <option value="">Semua Cabang</option>
                    @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                </select>
            </div>

            <div class="ml-auto text-sm text-gray-500 flex items-center gap-2">
                <i class="fas fa-calendar-alt text-indigo-500"></i>
                Laporan Periode: <span
                    class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-indigo-50 flex justify-between items-center">
            <h3 class="font-bold text-indigo-800 flex items-center gap-2">
                <i class="fas fa-chart-line"></i> Kinerja Penjualan (IMS)
            </h3>
            <span class="text-xs text-indigo-500">Target vs Realisasi</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 border-b w-10 text-center">No</th>
                        <th class="px-6 py-3 border-b">Nama Sales</th>
                        <th class="px-6 py-3 border-b text-center">Cabang</th>
                        <th class="px-6 py-3 border-b text-right">Target IMS</th>
                        <th class="px-6 py-3 border-b text-right">Pencapaian (Real)</th>
                        <th class="px-6 py-3 border-b text-center">Ach (%)</th>
                        <th class="px-6 py-3 border-b text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($laporan as $index => $row)
                    <tr class="hover:bg-indigo-50/50 transition-colors">
                        <td class="px-6 py-3 text-center text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3 font-bold text-gray-800">{{ $row['nama'] }}</td>
                        <td class="px-6 py-3 text-center text-xs text-gray-500">{{ $row['cabang'] }}</td>
                        <td class="px-6 py-3 text-right text-gray-500">
                            {{ number_format($row['target_ims'], 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right font-bold text-indigo-700">
                            {{ number_format($row['real_ims'], 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-center">
                            <span
                                class="font-bold {{ $row['persen_ims'] >= 100 ? 'text-green-600' : ($row['persen_ims'] >= 80 ? 'text-orange-500' : 'text-red-500') }}">
                                {{ number_format($row['persen_ims'], 1) }}%
                            </span>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="w-24 bg-gray-200 rounded-full h-1.5 mx-auto">
                                <div class="h-1.5 rounded-full {{ $row['persen_ims'] >= 100 ? 'bg-green-500' : 'bg-indigo-500' }}"
                                    style="width: {{ min($row['persen_ims'], 100) }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-orange-50 flex justify-between items-center">
            <h3 class="font-bold text-orange-800 flex items-center gap-2">
                <i class="fas fa-file-invoice-dollar"></i> Kualitas Piutang (AR)
            </h3>
            <span class="text-xs text-orange-500">Lancar vs Macet (>30 Hari)</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 border-b">Nama Sales</th>
                        <th class="px-6 py-3 border-b text-right text-green-600">AR Lancar (Reguler)</th>
                        <th class="px-6 py-3 border-b text-right text-red-600 font-bold">AR > 30 Hari (Macet)</th>
                        <th class="px-6 py-3 border-b text-right">Total AR</th>
                        <th class="px-6 py-3 border-b text-center text-red-600">% Macet</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($laporan as $row)
                    <tr class="hover:bg-orange-50/30 transition-colors">
                        <td class="px-6 py-3 font-medium text-gray-800">{{ $row['nama'] }}</td>
                        <td class="px-6 py-3 text-right text-green-600">
                            {{ number_format($row['ar_lancar'], 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right font-bold text-red-600">
                            {{ number_format($row['ar_macet'], 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right font-bold text-gray-800">
                            {{ number_format($row['total_ar'], 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-center font-bold text-red-500">
                            {{ number_format($row['persen_macet'], 1) }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-emerald-50 flex justify-between items-center">
            <h3 class="font-bold text-emerald-800 flex items-center gap-2">
                <i class="fas fa-users"></i> Produktifitas (OA & EC)
            </h3>
            <span class="text-xs text-emerald-500">Outlet Active & Effective Call</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 border-b">Nama Sales</th>
                        <th class="px-6 py-3 border-b text-center">Target OA</th>
                        <th class="px-6 py-3 border-b text-center">Realisasi OA</th>
                        <th class="px-6 py-3 border-b text-center">Ach OA (%)</th>
                        <th class="px-6 py-3 border-b text-center">Efektif Call (Faktur)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($laporan as $row)
                    <tr class="hover:bg-emerald-50/30 transition-colors">
                        <td class="px-6 py-3 font-medium text-gray-800">{{ $row['nama'] }}</td>
                        <td class="px-6 py-3 text-center text-gray-500">{{ number_format($row['target_oa']) }}</td>
                        <td class="px-6 py-3 text-center font-bold text-emerald-700">
                            {{ number_format($row['real_oa']) }}</td>
                        <td class="px-6 py-3 text-center">
                            <span
                                class="px-2 py-1 rounded text-[10px] font-bold {{ $row['persen_oa'] >= 100 ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ number_format($row['persen_oa'], 0) }}%
                            </span>
                        </td>
                        <td class="px-6 py-3 text-center font-mono text-gray-600">{{ number_format($row['ec']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>