<div class="space-y-6 font-jakarta">

    <div
        class="bg-white p-5 rounded-2xl shadow-sm border border-indigo-100 flex flex-col md:flex-row justify-between items-end gap-4">

        <div class="flex gap-3 w-full md:w-auto">
            <div class="w-full md:w-48">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Periode Laporan</label>
                <input type="month" wire:model.live="bulan"
                    class="w-full border-indigo-100 rounded-xl text-sm focus:ring-indigo-500 font-bold text-indigo-700">
            </div>

            <div class="w-full md:w-48">
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Cabang</label>
                <select wire:model.live="filterCabang"
                    class="w-full border-indigo-100 rounded-xl text-sm focus:ring-indigo-500 text-slate-700">
                    <option value="">Semua Cabang</option>
                    @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                </select>
            </div>
        </div>

        <div class="text-right">
            <p class="text-xs text-slate-400">Total Salesman: <span
                    class="font-bold text-indigo-600">{{ count($laporan) }} Orang</span></p>
        </div>
    </div>

    <div class="flex space-x-1 bg-slate-100 p-1 rounded-xl w-fit overflow-x-auto">
        <button wire:click="setTab('penjualan')"
            class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2 {{ $activeTab === 'penjualan' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
            <i class="fas fa-chart-line"></i> Penjualan (IMS)
        </button>

        <button wire:click="setTab('ar')"
            class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2 {{ $activeTab === 'ar' ? 'bg-white text-orange-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
            <i class="fas fa-money-bill-wave"></i> Piutang (AR)
        </button>

        <button wire:click="setTab('supplier')"
            class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2 {{ $activeTab === 'supplier' ? 'bg-white text-purple-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
            <i class="fas fa-boxes-stacked"></i> By Supplier
        </button>

        <button wire:click="setTab('produktifitas')"
            class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2 {{ $activeTab === 'produktifitas' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
            <i class="fas fa-users-viewfinder"></i> Produktifitas
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden min-h-[400px]">

        @if($activeTab === 'penjualan')
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-emerald-50 text-emerald-800 font-bold text-xs uppercase border-b border-emerald-100">
                    <tr>
                        <th class="px-6 py-4 w-12 text-center">Rank</th>
                        <th class="px-6 py-4">Nama Sales</th>
                        <th class="px-6 py-4 text-center">Cabang</th>
                        <th class="px-6 py-4 text-right">Target (IMS)</th>
                        <th class="px-6 py-4 text-right">Realisasi (IMS)</th>
                        <th class="px-6 py-4 text-center w-32">% Ach</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($laporan as $index => $row)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-3 text-center font-bold text-slate-400">#{{ $index + 1 }}</td>
                        <td class="px-6 py-3 font-bold text-slate-700">{{ $row['nama'] }}</td>
                        <td class="px-6 py-3 text-center text-xs text-slate-500">{{ $row['cabang'] }}</td>
                        <td class="px-6 py-3 text-right text-slate-600 font-mono">
                            {{ number_format($row['t_ims'], 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right font-bold text-emerald-700 font-mono">
                            {{ number_format($row['r_ims'], 0, ',', '.') }}</td>
                        <td class="px-6 py-3 align-middle">
                            <div class="w-full bg-slate-200 rounded-full h-2 mb-1">
                                <div class="h-2 rounded-full {{ $row['p_ims'] >= 100 ? 'bg-emerald-500' : 'bg-yellow-500' }}"
                                    style="width: {{ min($row['p_ims'], 100) }}%"></div>
                            </div>
                            <div class="text-xs font-bold text-center">{{ number_format($row['p_ims'], 1) }}%</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400">Data Penjualan Kosong. Cek filter
                            tanggal.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif

        @if($activeTab === 'ar')
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-orange-50 text-orange-800 font-bold text-xs uppercase border-b border-orange-100">
                    <tr>
                        <th class="px-6 py-4">Nama Sales</th>
                        <th class="px-6 py-4 text-center">Cabang</th>
                        <th class="px-6 py-4 text-right">Total Piutang</th>
                        <th class="px-6 py-4 text-right text-emerald-700">AR Lancar</th>
                        <th class="px-6 py-4 text-right text-red-700">AR Macet (>30 Hari)</th>
                        <th class="px-6 py-4 text-center">% Bad Debt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($laporan as $row)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-3 font-bold text-slate-700">{{ $row['nama'] }}</td>
                        <td class="px-6 py-3 text-center text-xs text-slate-500">{{ $row['cabang'] }}</td>
                        <td class="px-6 py-3 text-right font-mono">{{ number_format($row['ar_tot'], 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right font-mono text-emerald-600">
                            {{ number_format($row['ar_reg'], 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right font-mono text-red-600 font-bold">
                            {{ number_format($row['ar_bad'], 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-center">
                            <span
                                class="px-2 py-1 rounded text-xs font-bold {{ $row['p_bad'] > 5 ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ number_format($row['p_bad'], 1) }}%
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400">Data AR Kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif

        @if($activeTab === 'supplier')
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left whitespace-nowrap">
                <thead class="bg-purple-50 text-purple-800 font-bold uppercase border-b border-purple-100">
                    <tr>
                        <th class="px-4 py-3 sticky left-0 bg-purple-50 border-r">Nama Sales</th>
                        @foreach($topSuppliers as $supp)
                        <th class="px-4 py-3 text-right border-r">{{ Str::limit($supp, 10) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($laporan as $row)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-2 font-bold text-slate-700 sticky left-0 bg-white border-r">
                            {{ $row['nama'] }}</td>
                        @foreach($topSuppliers as $supp)
                        <td class="px-4 py-2 text-right border-r text-slate-600">
                            @php $val = $matrixSupplier[$row['nama']][$supp] ?? 0; @endphp
                            {{ $val > 0 ? number_format($val/1000000, 1, ',', '.') : '-' }}
                        </td>
                        @endforeach
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($topSuppliers) + 1 }}" class="p-8 text-center text-slate-400">Data
                            Supplier Kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-2 text-[10px] text-slate-400 italic text-right">*Angka dalam Jutaan (x1.000.000)</div>
        </div>
        @endif

        @if($activeTab === 'produktifitas')
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-blue-50 text-blue-800 font-bold text-xs uppercase border-b border-blue-100">
                    <tr>
                        <th class="px-6 py-4">Nama Sales</th>
                        <th class="px-6 py-4 text-center border-l">Target OA</th>
                        <th class="px-6 py-4 text-center">Real OA</th>
                        <th class="px-6 py-4 text-center border-r bg-blue-100 text-blue-900">% Ach OA</th>

                        <th class="px-6 py-4 text-center">Real EC</th>
                        <th class="px-6 py-4 text-center text-slate-500">Avg EC/Hari</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($laporan as $row)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-3 font-bold text-slate-700">{{ $row['nama'] }}</td>

                        <td class="px-6 py-3 text-center border-l">{{ $row['t_oa'] }}</td>
                        <td class="px-6 py-3 text-center font-bold text-blue-600">{{ $row['r_oa'] }}</td>
                        <td class="px-6 py-3 text-center border-r bg-blue-50/30">
                            <span
                                class="font-bold {{ $row['p_oa'] >= 100 ? 'text-emerald-600' : 'text-slate-600' }}">{{ number_format($row['p_oa'], 0) }}%</span>
                        </td>

                        <td class="px-6 py-3 text-center font-mono">{{ $row['r_ec'] }}</td>
                        <td class="px-6 py-3 text-center text-slate-500">{{ number_format($row['r_ec'] / 25, 1) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400">Data Produktifitas Kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif

    </div>

</div>