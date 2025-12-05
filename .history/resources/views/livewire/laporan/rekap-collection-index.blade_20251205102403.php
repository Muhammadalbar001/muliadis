<div class="space-y-4">

    <!-- HEADER CONTROLS -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">

            <!-- Search Bar -->
            <div class="w-full md:w-1/2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Cari No Bukti, Outlet, atau Invoice..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm transition">
            </div>

            <!-- Tombol Export -->
            <button
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-bold shadow flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>
    </div>

    <!-- TABEL DATA 10 KOLOM -->
    <div class="bg-white border border-gray-300 shadow-sm rounded-lg flex flex-col">
        <div class="flex-1 overflow-x-auto overflow-y-auto min-w-full" style="max-height: calc(100vh - 200px);">
            <table class="min-w-max text-xs text-left border-collapse table-auto">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-3 border-r bg-gray-100 whitespace-nowrap">Cabang</th>
                        <th class="px-4 py-3 border-r bg-gray-100 whitespace-nowrap">Receive No</th>
                        <th class="px-4 py-3 border-r bg-gray-100 whitespace-nowrap">Status</th>
                        <th class="px-4 py-3 border-r bg-gray-100 whitespace-nowrap">Tanggal</th>
                        <th class="px-4 py-3 border-r bg-gray-100 whitespace-nowrap">Penagih</th>
                        <th class="px-4 py-3 border-r bg-gray-100 whitespace-nowrap">No Invoice</th>
                        <th class="px-4 py-3 border-r bg-gray-100 whitespace-nowrap">Code</th>
                        <th class="px-4 py-3 border-r bg-gray-100 min-w-[200px] whitespace-nowrap">Outlet Name</th>
                        <th class="px-4 py-3 border-r bg-gray-100 whitespace-nowrap">Sales</th>
                        <th class="px-4 py-3 border-r bg-gray-100 text-right whitespace-nowrap">Receive Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($collections as $item)
                    <tr class="hover:bg-blue-50 transition duration-150">
                        <td class="px-4 py-2 border-r font-bold text-blue-800 whitespace-nowrap">{{ $item->cabang }}
                        </td>
                        <td class="px-4 py-2 border-r font-mono whitespace-nowrap">{{ $item->receive_no }}</td>

                        <td class="px-4 py-2 border-r text-center whitespace-nowrap">
                            <span
                                class="px-2 py-0.5 rounded text-[10px] font-bold border
                                {{ strtolower($item->status) == 'release' ? 'bg-green-100 text-green-800 border-green-200' : 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                {{ $item->status }}
                            </span>
                        </td>

                        <td class="px-4 py-2 border-r whitespace-nowrap text-gray-600">
                            {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-4 py-2 border-r whitespace-nowrap">{{ $item->penagih }}</td>
                        <td class="px-4 py-2 border-r whitespace-nowrap text-gray-600 font-mono">{{ $item->invoice_no }}
                        </td>
                        <td class="px-4 py-2 border-r whitespace-nowrap">{{ $item->code_customer }}</td>
                        <td class="px-4 py-2 border-r font-medium text-gray-900 truncate max-w-[250px]"
                            title="{{ $item->outlet_name }}">
                            {{ $item->outlet_name }}
                        </td>
                        <td class="px-4 py-2 border-r whitespace-nowrap">{{ $item->sales_name }}</td>

                        <td class="px-4 py-2 border-r text-right font-bold text-green-700 whitespace-nowrap">
                            {{ number_format((float) str_replace([',', '.'], '', str_replace('.00', '', $item->receive_amount)), 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-gray-400 bg-gray-50">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <i class="fas fa-money-bill-wave fa-3x text-gray-300"></i>
                                <h3 class="text-lg font-bold text-gray-600">Belum Ada Data Collection</h3>
                                <p class="text-sm max-w-sm">Silakan lakukan Import di menu **Transaksi > Collection**.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-3 border-t bg-gray-50">
            {{ $collections->links() }}
        </div>
    </div>
</div>