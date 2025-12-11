<div class="space-y-6 font-jakarta">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-cyan-50/90 p-4 rounded-b-2xl shadow-sm border-b border-cyan-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2 bg-cyan-100 rounded-lg text-cyan-600 shadow-sm"><i
                        class="fas fa-hand-holding-usd text-xl"></i></div>
                <div>
                    <h1 class="text-xl font-extrabold text-cyan-900 tracking-tight">Rekap Collection</h1>
                    <p class="text-xs text-cyan-600 font-medium mt-0.5">Detail pelunasan (Excel Style).</p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">
                <div class="relative w-full sm:w-48">
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-3 w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-cyan-500 py-2 shadow-sm placeholder-slate-400"
                        placeholder="No Bukti / Pelanggan...">
                </div>
                <div
                    class="flex items-center gap-1 bg-white border border-white rounded-lg px-2 py-1 shadow-sm h-[34px]">
                    <input type="date" wire:model.live="startDate"
                        class="border-none text-[10px] font-bold text-slate-700 focus:ring-0 p-0 bg-transparent w-20 cursor-pointer">
                    <span class="text-slate-300 text-[10px]">-</span>
                    <input type="date" wire:model.live="endDate"
                        class="border-none text-[10px] font-bold text-slate-700 focus:ring-0 p-0 bg-transparent w-20 cursor-pointer">
                </div>
                <div class="w-full sm:w-32">
                    <select wire:model.live="filterCabang"
                        class="w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-cyan-500 py-2 shadow-sm cursor-pointer bg-white hover:bg-cyan-50">
                        <option value="">Semua Cabang</option>
                        @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                    </select>
                </div>
                <div class="hidden sm:block h-6 w-px bg-cyan-200 mx-1"></div>
                <button wire:click="export" wire:loading.attr="disabled"
                    class="px-3 py-2 bg-cyan-600 text-white rounded-lg text-xs font-bold hover:bg-cyan-700 shadow-md shadow-cyan-500/20 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                    <span wire:loading.remove wire:target="export"><i class="fas fa-file-excel"></i> Export</span>
                    <span wire:loading wire:target="export"><i class="fas fa-spinner fa-spin"></i> Proses...</span>
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[85vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-[10px] text-left border-collapse whitespace-nowrap min-w-max w-full">
                <thead
                    class="bg-slate-50 font-bold text-slate-500 uppercase border-b border-slate-200 sticky top-0 z-20">
                    <tr>
                        <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-0 z-30 shadow-sm">Cabang
                        </th>
                        <th class="px-3 py-3 border-r border-slate-200 bg-slate-50 sticky left-[60px] z-30 shadow-sm">No
                            Bukti</th>

                        <th class="px-3 py-3 border-r border-slate-200">Status</th>
                        <th class="px-3 py-3 border-r border-slate-200">Tanggal</th>
                        <th class="px-3 py-3 border-r border-slate-200">Penagih</th>
                        <th class="px-3 py-3 border-r border-slate-200">No Invoice</th>
                        <th class="px-3 py-3 border-r border-slate-200">Code Customer</th>
                        <th class="px-3 py-3 border-r border-slate-200 min-w-[200px]">Outlet Name</th>
                        <th class="px-3 py-3 border-r border-slate-200">Sales Name</th>
                        <th class="px-3 py-3 border-r border-slate-200 text-right bg-cyan-50 text-cyan-700">Receive
                            Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($collections as $item)
                    <tr class="hover:bg-cyan-50/20 transition-colors odd:bg-white even:bg-slate-50/30">
                        <td class="px-3 py-2 border-r border-slate-100 font-bold sticky left-0 bg-inherit z-10">
                            {{ $item->cabang }}</td>
                        <td
                            class="px-3 py-2 border-r border-slate-100 font-mono text-cyan-600 sticky left-[60px] bg-inherit z-10">
                            {{ $item->receive_no }}</td>

                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->status }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ date('d/m/Y', strtotime($item->tanggal)) }}
                        </td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->penagih }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 font-mono">{{ $item->invoice_no }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->code_customer }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 font-bold text-slate-700 truncate max-w-[200px]"
                            title="{{ $item->outlet_name }}">{{ $item->outlet_name }}</td>
                        <td class="px-3 py-2 border-r border-slate-100">{{ $item->sales_name }}</td>
                        <td class="px-3 py-2 border-r border-slate-100 text-right font-bold text-cyan-700">
                            {{ number_format($item->receive_amount, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-slate-400">Data tidak ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50">{{ $collections->links() }}</div>
    </div>
</div>