<div class="space-y-6">

    <div
        class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Cari No Bukti, Outlet, atau Invoice..."
                class="pl-10 pr-4 py-2.5 w-full border-gray-200 rounded-lg text-sm focus:border-green-500 focus:ring-green-500 placeholder-gray-400 transition-colors">
        </div>
        <button
            class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg transition-all shadow-sm hover:shadow-md">
            <i class="fas fa-file-excel mr-2"></i> Export Excel
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[80vh]">
        <div class="flex-1 overflow-auto">
            <table class="w-full text-xs text-left border-collapse">
                <thead class="text-xs text-gray-600 uppercase bg-gray-100 sticky top-0 z-20 shadow-sm">
                    <tr>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px] font-bold">Cabang</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[120px]">Receive No</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Status</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[100px]">Tanggal</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[120px]">Penagih</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[120px]">No Invoice</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[80px]">Code</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[200px]">Outlet Name</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-100 min-w-[120px]">Sales</th>
                        <th
                            class="px-3 py-3 border-b border-r bg-green-100 text-green-900 min-w-[120px] text-right font-bold">
                            Receive Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($collections as $item)
                    <tr class="hover:bg-green-50 transition-colors">
                        <td class="px-3 py-2 border-r font-medium text-green-600 whitespace-nowrap">{{ $item->cabang }}
                        </td>
                        <td class="px-3 py-2 border-r font-mono whitespace-nowrap">{{ $item->receive_no }}</td>

                        <td class="px-3 py-2 border-r text-center whitespace-nowrap">
                            <span
                                class="px-2 py-0.5 rounded-full text-[10px] font-bold border
                                {{ strtolower($item->status) == 'release' ? 'bg-green-100 text-green-800 border-green-200' : 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                {{ $item->status }}
                            </span>
                        </td>

                        <td class="px-3 py-2 border-r whitespace-nowrap">
                            {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') : '-' }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->penagih }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap font-mono text-gray-500">{{ $item->invoice_no }}
                        </td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->code_customer }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap font-medium">{{ $item->outlet_name }}</td>
                        <td class="px-3 py-2 border-r whitespace-nowrap">{{ $item->sales_name }}</td>

                        <td class="px-3 py-2 border-r text-right font-bold text-green-700 whitespace-nowrap">
                            Rp
                            {{ number_format((float) str_replace([',', '.'], '', str_replace('.00', '', $item->receive_amount)), 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                            <i class="fas fa-money-bill-wave fa-3x mb-3 text-gray-300"></i>
                            <p class="text-lg font-medium">Belum Ada Data Rekap Collection</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            {{ $collections->links() }}
        </div>
    </div>
</div>