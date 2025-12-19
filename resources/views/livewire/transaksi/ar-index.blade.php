<div class="min-h-screen space-y-6 pb-10 transition-colors duration-300 font-jakarta" x-data="{ filterOpen: false }">

    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/5 bg-white/80 border-slate-200 shadow-sm">

        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div
                    class="p-2.5 rounded-xl shadow-lg dark:bg-orange-500/20 bg-orange-500 text-white dark:text-orange-400">
                    <i class="fas fa-file-invoice-dollar text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-800">
                        Monitoring <span class="text-orange-500">Piutang</span>
                    </h1>
                    <p
                        class="text-[9px] font-bold uppercase tracking-[0.3em] opacity-50 mt-1.5 dark:text-slate-400 text-slate-500">
                        Outstanding Invoices & AR Aging</p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-3 items-center w-full xl:w-auto justify-end">

                <div class="relative w-full sm:w-48 group">
                    <i
                        class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-orange-500 transition-colors text-xs"></i>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="w-full pl-9 pr-4 py-2 rounded-xl border text-[11px] font-bold uppercase tracking-widest focus:ring-2 focus:ring-orange-500/20 transition-all
                        dark:bg-black/40 dark:border-white/10 dark:text-white bg-slate-100 border-slate-200 shadow-inner" placeholder="No Invoice / Pelanggan...">
                </div>

                <div class="relative w-full sm:w-40" x-data="{ open: false, selected: @entangle('filterCabang').live }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between border px-4 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm
                        dark:bg-black/40 dark:border-white/5 dark:text-slate-300 bg-white border-slate-200 text-slate-700 hover:border-orange-400">
                        <span class="truncate"
                            x-text="selected.length > 0 ? selected.length + ' Regional' : 'Regional Hub'"></span>
                        <i class="fas fa-chevron-down opacity-40 text-[10px] transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition class="absolute z-50 mt-2 w-full border rounded-2xl shadow-2xl p-2 max-h-60 overflow-y-auto custom-scrollbar
                        dark:bg-slate-900 border-slate-800 bg-white border-slate-200" style="display: none;">
                        <div @click="selected = []"
                            class="px-3 py-2 text-[10px] text-rose-500 font-black uppercase tracking-widest cursor-pointer hover:bg-rose-500/10 rounded-xl mb-1 flex items-center gap-2">
                            <i class="fas fa-times-circle"></i> Reset Filter
                        </div>
                        @foreach($optCabang as $c)
                        <label
                            class="flex items-center px-3 py-2.5 hover:bg-orange-500/10 rounded-xl cursor-pointer transition-colors group">
                            <input type="checkbox" value="{{ $c }}" x-model="selected"
                                class="rounded-full border-slate-500 text-orange-600 focus:ring-orange-500 h-3.5 w-3.5">
                            <span
                                class="ml-3 text-[10px] font-bold uppercase tracking-tight group-hover:text-orange-400 dark:text-slate-400 text-slate-600">{{ $c }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="w-full sm:w-32">
                    <select wire:model.live="filterUmur" class="w-full border px-4 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-orange-500/20 transition-all shadow-sm cursor-pointer
                        dark:bg-black/40 dark:border-white/10 dark:text-white bg-white border-slate-200">
                        <option value="">All Aging</option>
                        <option value="lancar">Lancar (<=30)< /option>
                        <option value="macet">Macet (>30)</option>
                    </select>
                </div>

                <div
                    class="flex items-center gap-1.5 p-1.5 dark:bg-rose-500/10 bg-rose-50 border dark:border-rose-500/20 border-rose-100 rounded-2xl shadow-sm">
                    <input type="date" wire:model="deleteStartDate"
                        class="text-[9px] rounded-lg border-none py-1.5 px-2 bg-white dark:bg-black/40 font-black uppercase text-slate-700 dark:text-slate-200 focus:ring-rose-500">
                    <span class="text-rose-300 text-[9px] font-black uppercase">To</span>
                    <input type="date" wire:model="deleteEndDate"
                        class="text-[9px] rounded-lg border-none py-1.5 px-2 bg-white dark:bg-black/40 font-black uppercase text-slate-700 dark:text-slate-200 focus:ring-rose-500">
                    <button onclick="confirm('Hapus PERIODE data piutang ini?') || event.stopImmediatePropagation()"
                        wire:click="deleteByPeriod"
                        class="p-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-all shadow-lg shadow-rose-600/20">
                        <i class="fas fa-trash-alt text-[10px]"></i>
                    </button>
                </div>

                <button wire:click="openImportModal"
                    class="flex items-center gap-2 px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-orange-600/20 transition-all transform active:scale-95">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span class="hidden sm:inline">Import</span>
                </button>

                <div wire:loading
                    class="w-10 h-10 rounded-xl flex items-center justify-center dark:bg-slate-800 bg-white border dark:border-white/5 border-slate-200 shadow-sm animate-pulse">
                    <i class="fas fa-circle-notch fa-spin text-orange-500"></i>
                </div>
            </div>
        </div>
    </div>

    <div wire:loading.class="opacity-50 pointer-events-none"
        class="transition-opacity duration-300 px-4 sm:px-6 lg:px-8">

        @if(isset($summary))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div
                class="relative p-6 rounded-[2.5rem] border transition-all duration-500 group overflow-hidden dark:bg-orange-500/10 dark:border-orange-500/20 bg-orange-500 text-white shadow-xl shadow-orange-600/20">
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Remaining Receivables</p>
                <h3 class="text-3xl font-black mt-2 tracking-tighter">Rp
                    {{ number_format($summary['total_piutang'], 0, ',', '.') }}</h3>
                <i
                    class="fas fa-hand-holding-usd absolute -right-4 -bottom-4 text-7xl opacity-10 rotate-12 transition-transform group-hover:scale-110"></i>
            </div>

            <div
                class="relative p-6 rounded-[2.5rem] border transition-all dark:bg-rose-500/10 dark:border-rose-500/20 bg-white border-rose-100 shadow-xl flex items-center justify-between group hover:border-rose-300">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-rose-500">Bad Debt (>30 Days)</p>
                    <h3 class="text-2xl font-black mt-1 tracking-tighter dark:text-rose-400 text-rose-600">Rp
                        {{ number_format($summary['total_macet'], 0, ',', '.') }}</h3>
                    <span
                        class="text-[8px] font-black uppercase tracking-widest bg-rose-50 dark:bg-rose-500/10 text-rose-600 px-2 py-0.5 rounded border border-rose-100 dark:border-rose-500/20">Action
                        Required</span>
                </div>
                <div
                    class="w-14 h-14 rounded-2xl bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center text-rose-500 shadow-inner">
                    <i class="fas fa-bell text-xl animate-bounce"></i>
                </div>
            </div>

            <div
                class="p-6 rounded-[2.5rem] border transition-all dark:bg-slate-900/40 dark:border-white/5 bg-white border-slate-200 shadow-xl flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest dark:text-slate-400 text-slate-400">
                        Outstanding Invoices</p>
                    <h3 class="text-2xl font-black mt-1 tracking-tighter dark:text-white text-slate-800">
                        {{ number_format($summary['total_faktur'], 0, ',', '.') }}</h3>
                </div>
                <div
                    class="w-14 h-14 rounded-2xl dark:bg-indigo-500/10 bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-inner">
                    <i class="fas fa-file-invoice text-xl"></i>
                </div>
            </div>
        </div>
        @endif

        <div class="rounded-[2.5rem] border overflow-hidden transition-all duration-300 flex flex-col h-[70vh]
            dark:bg-slate-900/40 dark:border-white/5 bg-white border-slate-200 shadow-2xl dark:shadow-black/60">

            <div class="overflow-auto flex-1 w-full custom-scrollbar">
                <table class="w-full text-xs text-left border-collapse uppercase">
                    <thead>
                        <tr
                            class="dark:bg-white/5 bg-slate-50 text-slate-500 dark:text-slate-400 font-black text-[10px] tracking-[0.15em] border-b dark:border-white/5 border-slate-100">
                            <th class="px-6 py-5 border-r dark:border-white/5 border-slate-100">Invoice Date</th>
                            <th class="px-6 py-5 border-r dark:border-white/5 border-slate-100">Reference No</th>
                            <th class="px-6 py-5 border-r dark:border-white/5 border-slate-100 min-w-[200px]">Customer
                                Name</th>
                            <th class="px-6 py-5 border-r dark:border-white/5 border-slate-100">Salesperson</th>
                            <th class="px-6 py-5 border-r dark:border-white/5 border-slate-100">Due Date</th>
                            <th class="px-6 py-5 border-r dark:border-white/5 border-slate-100 text-center">Aging</th>
                            <th
                                class="px-6 py-5 border-r dark:border-white/5 border-slate-100 text-right dark:bg-orange-600/10 bg-orange-50/50 text-orange-600">
                                Balance Amount</th>
                            <th
                                class="px-6 py-5 text-center bg-slate-50/50 dark:bg-white/5 border-l dark:border-white/5 border-slate-100 sticky right-0 z-20">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-white/5 divide-slate-100">
                        @forelse($ars as $item)
                        <tr class="hover:bg-orange-500/[0.02] transition-colors group">
                            <td
                                class="px-6 py-4 dark:text-slate-400 text-slate-500 font-bold border-r dark:border-white/5 border-slate-50 italic">
                                {{ date('d/m/Y', strtotime($item->tgl_penjualan)) }}
                            </td>
                            <td
                                class="px-6 py-4 font-mono font-black text-orange-600 dark:text-orange-400 border-r dark:border-white/5 border-slate-50">
                                {{ $item->no_penjualan }}
                            </td>
                            <td class="px-6 py-4 font-black dark:text-white text-slate-800 border-r dark:border-white/5 border-slate-50 truncate max-w-[250px]"
                                title="{{ $item->pelanggan_name }}">
                                {{ $item->pelanggan_name }}
                            </td>
                            <td
                                class="px-6 py-4 text-[10px] font-bold dark:text-slate-400 text-slate-500 border-r dark:border-white/5 border-slate-50">
                                {{ $item->sales_name }}
                            </td>
                            <td
                                class="px-6 py-4 font-bold border-r dark:border-white/5 border-slate-50 {{ $item->jatuh_tempo && strtotime($item->jatuh_tempo) < time() ? 'text-rose-500 animate-pulse' : 'text-slate-500' }}">
                                {{ $item->jatuh_tempo ? date('d/m/Y', strtotime($item->jatuh_tempo)) : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-center border-r dark:border-white/5 border-slate-50">
                                @php $umur = (int)$item->umur_piutang; @endphp
                                <span
                                    class="px-3 py-1 rounded-lg text-[9px] font-black tracking-widest border shadow-sm
                                    {{ $umur > 30 ? 'bg-rose-500/10 text-rose-500 border-rose-500/20' : 
                                      ($umur > 15 ? 'bg-amber-500/10 text-amber-500 border-amber-500/20' : 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20') }}">
                                    {{ $umur }} DAYS
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 text-right font-black dark:text-white text-slate-900 border-r dark:border-white/5 border-slate-50 bg-orange-500/[0.01]">
                                {{ number_format($item->nilai, 0, ',', '.') }}
                            </td>
                            <td
                                class="px-6 py-4 text-center bg-slate-50/30 dark:bg-[#0a0a0a] border-l dark:border-white/5 border-slate-50 sticky right-0 z-10 shadow-xl shadow-black/5">
                                <button wire:click="delete({{ $item->id }})"
                                    onclick="return confirm('Hapus record piutang ini?') || event.stopImmediatePropagation()"
                                    class="w-8 h-8 rounded-xl flex items-center justify-center text-slate-300 hover:text-white hover:bg-rose-500 transition-all shadow-sm">
                                    <i class="fas fa-trash-alt text-[10px]"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-24 text-center opacity-20">
                                <i class="fas fa-hand-holding-usd text-6xl mb-4"></i>
                                <p class="text-xs font-black tracking-[0.4em]">No Outstanding Receivables</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div
                class="px-6 py-5 border-t dark:border-white/5 border-slate-100 dark:bg-white/[0.02] bg-slate-50/50 uppercase font-black text-[10px]">
                {{ $ars->links() }}
            </div>
        </div>
    </div>

    @if($isImportOpen)
    @include('livewire.partials.import-modal', ['title' => 'Sync AR Ledger', 'color' => 'orange'])
    @endif
</div>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
    height: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(249, 115, 22, 0.2);
    border-radius: 10px;
}

tbody tr {
    animation: fadeIn 0.3s ease-out forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(4px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>