<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-gradient-to-r from-green-600 to-green-500 rounded-xl p-5 text-white shadow-lg">
            <p class="text-green-100 text-xs font-bold uppercase">Total Uang Masuk</p>
            <h3 class="text-2xl font-bold mt-1">Rp {{ number_format($summary->total_uang_masuk ?? 0, 0, ',', '.') }}
            </h3>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-100 flex gap-4 items-center">
            <input type="date" wire:model.live="startDate" class="text-sm border-gray-200 rounded-lg">
            <span class="text-gray-400">-</span>
            <input type="date" wire:model.live="endDate" class="text-sm border-gray-200 rounded-lg">
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[75vh]">
        <div class="flex-1 overflow-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="text-gray-600 uppercase bg-gray-50 sticky top-0 z-20 shadow-sm font-bold">
                    <tr>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 min-w-[100px]">Cabang</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 min-w-[120px]">RECEIVE NO</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">STATUS</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">TANGGAL</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">PENAGIH</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">INVOICE</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">CODE</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50 min-w-[150px]">OUTLET</th>
                        <th class="px-3 py-3 border-b border-r bg-gray-50">SALES</th>
                        <th class="px-3 py-3 border-b border-r bg-emerald-100 text-emerald-900 text-right">RECEIVE</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($collection as $item)
                    <tr class="hover:bg-green-50 transition-colors">
                        <td class="px-3 py-2 border-r text-green-600 font-bold">{{ $item->cabang }}</td>
                        <td class="px-3 py-2 border-r font-mono">{{ $item->receive_no }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->status }}</td>
                        <td class="px-3 py-2 border-r">
                            {{ $item->tanggal ? date('d-m-Y', strtotime($item->tanggal)) : '-' }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->penagih }}</td>
                        <td class="px-3 py-2 border-r font-mono text-gray-600">{{ $item->invoice_no }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->code_customer }}</td>
                        <td class="px-3 py-2 border-r font-medium">{{ $item->outlet_name }}</td>
                        <td class="px-3 py-2 border-r">{{ $item->sales_name }}</td>
                        <td class="px-3 py-2 border-r text-right font-bold text-green-700 bg-green-50/50">
                            {{ number_format((float)$item->receive_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-gray-400">Data Kosong</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-gray-50">{{ $collection->links() }}</div>
    </div>
</div>