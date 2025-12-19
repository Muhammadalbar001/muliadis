<div class="min-h-screen space-y-6 pb-20 transition-colors duration-300 font-jakarta" x-data="{ filterOpen: false }">

    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/5 bg-white/80 border-slate-200 shadow-sm">

        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2.5 rounded-xl shadow-lg dark:bg-cyan-500/20 bg-cyan-600 text-white dark:text-cyan-400">
                    <i class="fas fa-hand-holding-usd text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-800">
                        Payment <span class="text-cyan-500">Collection</span>
                    </h1>
                    <p
                        class="text-[9px] font-bold uppercase tracking-[0.3em] opacity-50 mt-1.5 dark:text-slate-400 text-slate-500">
                        Accounts Receivable Settlement</p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-3 items-center w-full xl:w-auto justify-end">

                <div class="relative w-full sm:w-48 group">
                    <i
                        class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-cyan-500 transition-colors text-xs"></i>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="w-full pl-9 pr-4 py-2.5 rounded-xl border text-[11px] font-bold uppercase tracking-widest focus:ring-2 focus:ring-cyan-500/20 transition-all
                        dark:bg-black/40 dark:border-white/10 dark:text-white bg-slate-100 border-slate-200 shadow-inner" placeholder="No Bukti / Pelanggan...">
                </div>

                <div class="relative w-full sm:w-40" x-data="{ open: false, selected: @entangle('filterCabang').live }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between border px-4 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm
                        dark:bg-black/40 dark:border-white/5 dark:text-slate-300 bg-white border-slate-200 text-slate-700 hover:border-cyan-400">
                        <span class="truncate"
                            x-text="selected.length > 0 ? selected.length + ' Regional' : 'Regional Hub'"></span>
                        <i class="fas fa-chevron-down opacity-40 text-[10px] transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition class="absolute z-50 mt-2 w-full border rounded-2xl shadow-2xl p-2 max-h-60 overflow-y-auto custom-scrollbar
                        dark:bg-slate-900 border-slate-800 bg-white border-slate-200" style="display: none;">
                        <div @click="selected = []"
                            class="px-3 py-2 text-[10px] text-rose-500 font-black uppercase tracking-widest cursor-pointer hover:bg-rose-500/10 rounded-xl mb-1 flex items-center gap-2">
                            <i class="fas fa-times-circle"></i> Reset Cabang
                        </div>
                        @foreach($optCabang as $c)
                        <label
                            class="flex items-center px-3 py-2.5 hover:bg-cyan-500/10 rounded-xl cursor-pointer transition-colors group">
                            <input type="checkbox" value="{{ $c }}" x-model="selected"
                                class="rounded-full border-slate-500 text-cyan-600 focus:ring-cyan-500 h-3.5 w-3.5">
                            <span
                                class="ml-3 text-[10px] font-bold uppercase tracking-tight group-hover:text-cyan-400 dark:text-slate-400 text-slate-600">{{ $c }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="w-full sm:w-36">
                    <select wire:model.live="filterPenagih" class="w-full border px-4 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-cyan-500/20 transition-all shadow-sm cursor-pointer
                        dark:bg-black/40 dark:border-white/10 dark:text-white bg-white border-slate-200">
                        <option value="">All Collectors</option>
                        @foreach($optPenagih as $p) <option value="{{ $p }}">{{ $p }}</option> @endforeach
                    </select>
                </div>

                <button wire:click="resetFilter"
                    class="px-4 py-2.5 dark:bg-white/5 bg-white border dark:border-white/10 border-slate-200 dark:text-slate-300 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-cyan-500 hover:text-white transition-all shadow-sm"
                    title="Reset Semua Filter">
                    <i class="fas fa-undo"></i>
                </button>

                <div
                    class="flex items-center gap-1.5 p-1.5 dark:bg-rose-500/10 bg-rose-50 border dark:border-rose-500/20 border-rose-100 rounded-2xl shadow-sm">
                    <input type="date" wire:model="deleteStartDate"
                        class="text-[9px] rounded-lg border-none py-1.5 px-2 bg-white dark:bg-black/40 font-black uppercase text-slate-700 dark:text-slate-200 focus:ring-rose-500">
                    <span class="text-rose-300 text-[9px] font-black uppercase">To</span>
                    <input type="date" wire:model="deleteEndDate"
                        class="text-[9px] rounded-lg border-none py-1.5 px-2 bg-white dark:bg-black/40 font-black uppercase text-slate-700 dark:text-slate-200 focus:ring-rose-500">
                    <button onclick="confirm('Hapus PERIODE collection ini?') || event.stopImmediatePropagation()"
                        wire:click="deleteByPeriod"
                        class="p-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-all shadow-lg shadow-rose-600/20">
                        <i class="fas fa-trash-alt text-[10px]"></i>
                    </button>
                </div>

                <button wire:click="openImportModal"
                    class="flex items-center gap-2 px-6 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-cyan-600/20 transition-all transform active:scale-95">
                    <i class="fas fa-file-import"></i>
                    <span class="hidden sm:inline">Import</span>
                </button>

                <div wire:loading
                    class="w-10 h-10 rounded-xl flex items-center justify-center dark:bg-slate-800 bg-white border dark:border-white/5 border-slate-200 shadow-sm animate-pulse">
                    <i class="fas fa-circle-notch fa-spin text-cyan-500"></i>
                </div>
            </div>
        </div>
    </div>

    <div wire:loading.class="opacity-50 pointer-events-none"
        class="transition-opacity duration-300 px-4 sm:px-6 lg:px-8">

        @if(isset($summary))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div
                class="relative p-6 rounded-[2.5rem] border transition-all duration-500 group overflow-hidden dark:bg-cyan-500/10 dark:border-cyan-500/20 bg-cyan-600 text-white shadow-xl shadow-cyan-600/20">
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Total Cash Inflow</p>
                <h3 class="text-3xl font-black mt-2 tracking-tighter">Rp
                    {{ number_format($summary['total_cair'], 0, ',', '.') }}</h3>
                <i
                    class="fas fa-money-bill-wave absolute -right-4 -bottom-4 text-7xl opacity-10 rotate-12 transition-transform group-hover:scale-110"></i>
            </div>

            <div
                class="p-6 rounded-[2.5rem] border transition-all dark:bg-slate-900/40 dark:border-white/5 bg-white border-slate-200 shadow-xl flex items-center justify-between group hover:border-cyan-400">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest dark:text-slate-400 text-slate-400">Bukti
                        Potong Tax</p>
                    <h3 class="text-2xl font-black mt-1 tracking-tighter dark:text-white text-slate-800">
                        {{ number_format($summary['total_bukti'], 0, ',', '.') }}</h3>
                </div>
                <div
                    class="w-14 h-14 rounded-2xl dark:bg-indigo-500/10 bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-inner">
                    <i class="fas fa-receipt text-xl"></i>
                </div>
            </div>

            <div
                class="p-6 rounded-[2.5rem] border transition-all dark:bg-slate-900/40 dark:border-white/5 bg-white border-slate-200 shadow-xl flex items-center justify-between group hover:border-green-400">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest dark:text-slate-400 text-slate-400">
                        Faktur Lunas</p>
                    <h3 class="text-2xl font-black mt-1 tracking-tighter dark:text-white text-slate-800">
                        {{ number_format($summary['total_faktur'], 0, ',', '.') }}</h3>
                </div>
                <div
                    class="w-14 h-14 rounded-2xl dark:bg-emerald-500/10 bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-inner">
                    <i class="fas fa-check-double text-xl"></i>
                </div>
            </div>
        </div>
        @endif

        <div class="rounded-[2.5rem] border overflow-hidden transition-all duration-300 flex flex-col min-h-[80vh] mb-10
            dark:bg-slate-900/40 dark:border-white/5 bg-white border-slate-200 shadow-2xl dark:shadow-black/60">

            <div class="overflow-auto flex-1 w-full custom-scrollbar">
                <table class="w-full text-xs text-left border-collapse uppercase">
                    <thead>
                        <tr
                            class="dark:bg-white/5 bg-slate-50 text-slate-500 dark:text-slate-400 font-black text-[10px] tracking-[0.15em] border-b dark:border-white/5 border-slate-100">
                            <th class="px-6 py-6 border-r dark:border-white/5 border-slate-100">Payment Date</th>
                            <th class="px-6 py-6 border-r dark:border-white/5 border-slate-100">Receipt No</th>
                            <th class="px-6 py-6 border-r dark:border-white/5 border-slate-100 min-w-[200px]">Outlet
                                Name</th>
                            <th class="px-6 py-6 border-r dark:border-white/5 border-slate-100">Invoice No</th>
                            <th class="px-6 py-6 border-r dark:border-white/5 border-slate-100">Collector</th>
                            <th class="px-6 py-6 border-r dark:border-white/5 border-slate-100 text-center">Hub</th>
                            <th
                                class="px-6 py-6 border-r dark:border-white/5 border-slate-100 text-right dark:bg-cyan-600/10 bg-cyan-50/50 text-cyan-600">
                                Settled Amount</th>
                            <th
                                class="px-6 py-6 text-center bg-slate-50/50 dark:bg-white/5 border-l dark:border-white/5 border-slate-100 sticky right-0 z-20">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-white/5 divide-slate-100">
                        @forelse($collections as $item)
                        <tr class="hover:bg-cyan-500/[0.02] transition-colors group">
                            <td
                                class="px-6 py-5 dark:text-slate-400 text-slate-500 font-bold border-r dark:border-white/5 border-slate-50 italic">
                                {{ date('d/m/Y', strtotime($item->tanggal)) }}
                            </td>
                            <td
                                class="px-6 py-5 font-mono font-black text-cyan-600 dark:text-cyan-400 border-r dark:border-white/5 border-slate-50">
                                {{ $item->receive_no }}
                            </td>
                            <td class="px-6 py-5 font-black dark:text-white text-slate-800 border-r dark:border-white/5 border-slate-50 truncate max-w-[200px]"
                                title="{{ $item->outlet_name }}">
                                {{ $item->outlet_name }}
                            </td>
                            <td
                                class="px-6 py-5 font-mono text-[10px] font-bold dark:text-slate-400 text-slate-500 border-r dark:border-white/5 border-slate-50">
                                {{ $item->invoice_no }}
                            </td>
                            <td
                                class="px-6 py-5 font-bold dark:text-slate-300 text-slate-600 border-r dark:border-white/5 border-slate-50">
                                {{ $item->penagih ?: 'System' }}
                            </td>
                            <td class="px-6 py-5 text-center border-r dark:border-white/5 border-slate-50">
                                <span
                                    class="px-2.5 py-1 rounded-lg text-[9px] font-black tracking-widest border dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20 bg-indigo-50 text-indigo-600 border-indigo-100 uppercase">
                                    {{ $item->cabang }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-5 text-right font-black dark:text-white text-slate-900 border-r dark:border-white/5 border-slate-50 bg-cyan-500/[0.01]">
                                {{ number_format($item->receive_amount, 0, ',', '.') }}
                            </td>
                            <td
                                class="px-6 py-5 text-center bg-slate-50/30 dark:bg-[#0a0a0a] border-l dark:border-white/5 border-slate-50 sticky right-0 z-10 shadow-xl shadow-black/5">
                                <button wire:click="delete({{ $item->id }})"
                                    onclick="return confirm('Hapus record pelunasan ini?') || event.stopImmediatePropagation()"
                                    class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-300 hover:text-white hover:bg-rose-500 transition-all shadow-sm">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-32 text-center opacity-20">
                                <i class="fas fa-hand-holding-usd text-8xl mb-4"></i>
                                <p class="text-sm font-black tracking-[0.4em]">No Settlement Records Found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div
                class="px-6 py-6 border-t dark:border-white/5 border-slate-100 dark:bg-white/[0.02] bg-slate-50/50 uppercase font-black text-[10px]">
                {{ $collections->links() }}
            </div>
        </div>
    </div>

    @if($isImportOpen)
    @include('livewire.partials.import-modal', ['title' => 'Sync Collection Ledger', 'color' => 'cyan'])
    @endif
</div>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 5px;
    height: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(6, 182, 212, 0.3);
    border-radius: 10px;
}

tbody tr {
    animation: fadeIn 0.4s ease-out forwards;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(8px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>