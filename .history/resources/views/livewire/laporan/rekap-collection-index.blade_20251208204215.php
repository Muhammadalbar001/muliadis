<div class="space-y-4 font-jakarta">

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-blue-100">
        <div class="flex flex-col md:flex-row gap-3 items-end">
            <div class="w-full md:flex-1 relative">
                <i class="fas fa-search absolute left-3 top-2.5 text-blue-400"></i>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-9 w-full border-blue-100 rounded-xl text-xs focus:ring-blue-500"
                    placeholder="Bukti, Invoice...">
            </div>
            <button wire:click="resetFilter" class="px-3 py-2 bg-slate-100 rounded-xl"><i
                    class="fas fa-undo text-slate-500"></i></button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-blue-100 flex flex-col h-[75vh]">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-[10px] text-left border-collapse whitespace-nowrap min-w-max w-full">
                <thead class="bg-blue-50 text-blue-800 font-bold border-b border-blue-200 uppercase sticky top-0 z-20">
                    <tr>
                        <th class="px-3 py-2 border-r border-blue-200 sticky left-0 bg-blue-50 z-30">Cabang</th>
                        <th class="px-3 py-2 border-r border-blue-200">Receive No</th>
                        <th class="px-3 py-2 border-r border-blue-200">Status</th>
                        <th class="px-3 py-2 border-r border-blue-200">Tgl Bayar</th>
                        <th class="px-3 py-2 border-r border-blue-200">Penagih</th>
                        <th class="px-3 py-2 border-r border-blue-200">No Invoice</th>
                        <th class="px-3 py-2 border-r border-blue-200">Kode Customer</th>
                        <th class="px-3 py-2 border-r border-blue-200">Nama Outlet</th>
                        <th class="px-3 py-2 border-r border-blue-200">Sales Name</th>
                        <th class="px-3 py-2 border-r border-blue-200 text-right bg-blue-100 text-blue-900">Receive
                            Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-50">
                    @forelse($data as $item)
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td
                            class="px-3 py-1.5 border-r border-blue-50 text-blue-700 font-bold sticky left-0 bg-white z-20">
                            {{ $item->cabang }}</td>
                        <td class="px-3 py-1.5 border-r border-blue-50 font-mono">{{ $item->receive_no }}</td>
                        <td class="px-3 py-1.5 border-r border-blue-50">{{ $item->status }}</td>
                        <td class="px-3 py-1.5 border-r border-blue-50">{{ date('d/m/Y', strtotime($item->tanggal)) }}
                        </td>
                        <td class="px-3 py-1.5 border-r border-blue-50">{{ $item->penagih }}</td>
                        <td class="px-3 py-1.5 border-r border-blue-50 font-mono">{{ $item->invoice_no }}</td>
                        <td class="px-3 py-1.5 border-r border-blue-50 font-mono">{{ $item->code_customer }}</td>
                        <td class="px-3 py-1.5 border-r border-blue-50 font-medium truncate max-w-[200px]">
                            {{ $item->outlet_name }}</td>
                        <td class="px-3 py-1.5 border-r border-blue-50">{{ $item->sales_name }}</td>
                        <td
                            class="px-3 py-1.5 border-r border-blue-50 text-right font-bold text-emerald-600 bg-blue-50/30">
                            {{ number_format($item->receive_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-slate-400">Kosong.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-2 border-t bg-blue-50">{{ $data->links() }}</div>
    </div>
</div>