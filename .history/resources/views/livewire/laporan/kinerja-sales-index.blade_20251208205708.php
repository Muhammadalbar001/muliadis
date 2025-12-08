<div class="space-y-6 font-jakarta">

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-indigo-100">
        <div class="flex flex-col md:flex-row gap-4 justify-between items-end">

            <div class="flex gap-3 w-full md:w-auto">
                <div class="w-1/2 md:w-32">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Bulan</label>
                    <select wire:model.live="filterBulan"
                        class="w-full border-indigo-100 rounded-xl text-sm focus:ring-indigo-500 font-bold text-indigo-700">
                        @for($i=1; $i<=12; $i++) <option value="{{ sprintf('%02d', $i) }}">
                            {{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                            @endfor
                    </select>
                </div>

                <div class="w-1/2 md:w-24">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Tahun</label>
                    <select wire:model.live="filterTahun"
                        class="w-full border-indigo-100 rounded-xl text-sm focus:ring-indigo-500 font-bold text-indigo-700">
                        @for($y=date('Y'); $y>=2023; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="w-full md:w-48" x-data="{ open: false }">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Cabang</label>
                    <div class="relative">
                        <button @click="open = !open" @click.outside="open = false"
                            class="w-full flex justify-between items-center bg-white border border-indigo-100 px-3 py-2 rounded-xl text-sm text-slate-600">
                            <span
                                class="truncate">{{ count($filterCabang) ? count($filterCabang).' Cabang' : 'Semua Cabang' }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div x-show="open"
                            class="absolute z-50 mt-1 w-full bg-white border border-indigo-100 rounded-xl shadow-xl p-1 max-h-48 overflow-y-auto">
                            @foreach($optCabang as $c)
                            <label class="flex items-center px-2 py-1.5 hover:bg-indigo-50 rounded cursor-pointer">
                                <input type="checkbox" value="{{ $c }}" wire:model.live="filterCabang"
                                    class="rounded border-indigo-300 text-indigo-600 mr-2 h-4 w-4">
                                <span class="text-xs text-slate-700">{{ $c }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                <div class="px-4 py-2 bg-indigo-50 rounded-xl border border-indigo-100 min-w-[140px]">
                    <p class="text-[10px] text-indigo-500 font-bold uppercase">Total Target</p>
                    <p class="text-sm font-bold text-indigo-800">
                        {{ number_format($summary['total_target'] / 1000000, 1, ',', '.') }} M</p>
                </div>
                <div class="px-4 py-2 bg-emerald-50 rounded-xl border border-emerald-100 min-w-[140px]">
                    <p class="text-[10px] text-emerald-500 font-bold uppercase">Realisasi</p>
                    <p class="text-sm font-bold text-emerald-800">
                        {{ number_format($summary['total_real'] / 1000000, 1, ',', '.') }} M</p>
                </div>
                <div class="px-4 py-2 bg-yellow-50 rounded-xl border border-yellow-100 min-w-[140px]">
                    <p class="text-[10px] text-yellow-600 font-bold uppercase">Rata2 Ach</p>
                    <p class="text-sm font-bold text-yellow-800">{{ number_format($summary['avg_ach'], 1) }}%</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50 font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-4 text-center w-12">Rank</th>
                        <th class="px-4 py-4">Salesman</th>
                        <th class="px-4 py-4 text-center">Cabang</th>

                        <th class="px-4 py-4 text-right bg-emerald-50/50 text-emerald-800 border-l border-emerald-100">
                            Target Omzet</th>
                        <th class="px-4 py-4 text-right bg-emerald-50/50 text-emerald-800">Realisasi</th>
                        <th
                            class="px-4 py-4 text-center bg-emerald-50/50 text-emerald-800 border-r border-emerald-100 w-32">
                            % Ach</th>

                        <th class="px-4 py-4 text-center bg-blue-50/50 text-blue-800">Target OA</th>
                        <th class="px-4 py-4 text-center bg-blue-50/50 text-blue-800">Real OA</th>
                        <th class="px-4 py-4 text-center bg-blue-50/50 text-blue-800 w-24">% Ach</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($data as $index => $row)
                    @php $rank = $data->firstItem() + $index; @endphp
                    <tr class="hover:bg-slate-50 transition-colors group">

                        <td class="px-4 py-3 text-center">
                            @if($rank == 1) <span
                                class="w-6 h-6 rounded-full bg-yellow-400 text-white flex items-center justify-center text-xs font-bold shadow-sm mx-auto">1</span>
                            @elseif($rank == 2) <span
                                class="w-6 h-6 rounded-full bg-slate-300 text-white flex items-center justify-center text-xs font-bold shadow-sm mx-auto">2</span>
                            @elseif($rank == 3) <span
                                class="w-6 h-6 rounded-full bg-orange-300 text-white flex items-center justify-center text-xs font-bold shadow-sm mx-auto">3</span>
                            @else <span class="text-slate-400 font-mono text-xs">#{{ $rank }}</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 font-bold text-slate-700">{{ $row['sales_name'] }}</td>
                        <td class="px-4 py-3 text-center text-xs text-slate-500">{{ $row['cabang'] }}</td>

                        <td
                            class="px-4 py-3 text-right bg-emerald-50/10 border-l border-slate-50 text-slate-500 font-mono text-xs">
                            {{ number_format($row['target_omzet'], 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-right bg-emerald-50/10 font-bold text-slate-700 font-mono text-xs">
                            {{ number_format($row['real_omzet'], 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 bg-emerald-50/10 border-r border-slate-50 align-middle">
                            <div class="flex flex-col justify-center h-full">
                                <div class="flex justify-between items-center text-[10px] mb-1">
                                    <span
                                        class="font-bold {{ $row['ach_omzet'] >= 100 ? 'text-emerald-600' : ($row['ach_omzet'] >= 80 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ number_format($row['ach_omzet'], 1) }}%
                                    </span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-1.5 rounded-full {{ $row['ach_omzet'] >= 100 ? 'bg-emerald-500' : ($row['ach_omzet'] >= 80 ? 'bg-yellow-400' : 'bg-red-500') }}"
                                        style="width: {{ min($row['ach_omzet'], 100) }}%"></div>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-3 text-center bg-blue-50/10 text-slate-500 text-xs">
                            {{ $row['target_oa'] }}
                        </td>
                        <td class="px-4 py-3 text-center bg-blue-50/10 font-bold text-slate-700 text-xs">
                            {{ $row['real_oa'] }}
                        </td>
                        <td class="px-4 py-3 text-center bg-blue-50/10">
                            <span
                                class="px-2 py-1 rounded text-[10px] font-bold {{ $row['ach_oa'] >= 100 ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ number_format($row['ach_oa'], 0) }}%
                            </span>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-chart-bar text-3xl mb-3 text-slate-200"></i>
                                <p class="text-sm">Belum ada data kinerja untuk periode ini.</p>
                                <p class="text-xs text-slate-400 mt-1">Pastikan Target Sales sudah diinput & Transaksi
                                    Penjualan tersedia.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t bg-slate-50">
            <div class="flex justify-between items-center text-xs text-slate-500">
                <span>Menampilkan 1-20 Salesman Terbaik</span>
            </div>
        </div>
    </div>
</div>