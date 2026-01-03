<div class="min-h-screen space-y-6 pb-10 transition-colors duration-300 font-jakarta bg-slate-50 dark:bg-[#050505]">

    {{-- STICKY HEADER --}}
    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/5 bg-white/95 border-slate-200 shadow-sm">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div
                    class="p-3 rounded-2xl shadow-xl bg-gradient-to-tr from-blue-600 to-indigo-500 text-white ring-4 ring-blue-500/10">
                    <i class="fas fa-user-tie text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-900">
                        Master <span class="text-blue-600">Salesman</span>
                    </h1>
                    <p
                        class="text-[9px] font-bold uppercase tracking-[0.3em] dark:text-slate-400 text-slate-600 mt-1.5 opacity-80">
                        Personnel & Target Management
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 items-center justify-end w-full xl:w-auto">
                <div class="relative group">
                    <i
                        class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors text-xs"></i>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-10 pr-4 py-2.5 w-64 rounded-2xl border text-[11px] font-bold uppercase transition-all
                        dark:bg-white/5 dark:border-white/10 dark:text-white dark:focus:border-blue-500/50
                        bg-white border-slate-300 text-slate-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 outline-none" placeholder="Cari Sales / Kode...">
                </div>

                <div class="flex gap-2">
                    <button wire:click="autoDiscover"
                        class="px-5 py-2.5 bg-slate-800 dark:bg-white dark:text-slate-900 text-white rounded-2xl text-[10px] font-black uppercase hover:scale-105 transition-all shadow-lg flex items-center gap-2">
                        <i class="fas fa-sync-alt" wire:loading.class="fa-spin" wire:target="autoDiscover"></i> Fix Data
                    </button>

                    <button wire:click="create"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase shadow-xl shadow-blue-600/20 hover:bg-blue-700 hover:scale-105 transition-all active:scale-95 flex items-center gap-2">
                        <i class="fas fa-plus"></i> Baru
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN TABLE AREA --}}
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        <div
            class="rounded-[2.5rem] border overflow-hidden dark:bg-[#0f0f0f] dark:border-white/5 bg-white border-slate-200 shadow-2xl shadow-slate-200/60 dark:shadow-none">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr
                            class="dark:bg-white/[0.02] bg-blue-600 text-white font-black text-[9px] uppercase tracking-[0.2em] border-b dark:border-white/5 border-blue-700">
                            <th class="px-8 py-6 w-40 text-center">Identity</th>
                            <th class="px-8 py-6">Salesman Details</th>
                            <th class="px-8 py-6">Operational Area</th>
                            <th class="px-8 py-6 text-center">Status</th>
                            <th class="px-8 py-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-white/5 divide-slate-100">
                        @forelse($sales as $item)
                        <tr class="hover:bg-blue-600/[0.04] dark:hover:bg-blue-400/[0.02] group transition-all">
                            <td class="px-8 py-5 text-center">
                                @if($item->sales_code)
                                <span
                                    class="px-3 py-1.5 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 font-mono font-black text-[11px] border border-blue-200 dark:border-blue-500/20">
                                    {{ $item->sales_code }}
                                </span>
                                @else
                                <span
                                    class="px-3 py-1.5 bg-rose-50 dark:bg-rose-500/10 text-rose-600 rounded-xl text-[9px] font-black border border-rose-200 dark:border-rose-500/20 animate-pulse">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> MISSING
                                </span>
                                @endif
                            </td>

                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-full bg-blue-600 dark:bg-white/5 flex items-center justify-center font-black text-white dark:text-blue-400 text-xs border border-blue-700 dark:border-white/10 group-hover:scale-110 transition-all shadow-md shadow-blue-500/20">
                                        {{ substr($item->sales_name, 0, 1) }}
                                    </div>
                                    <p
                                        class="font-black dark:text-white text-slate-900 text-xs uppercase tracking-tight">
                                        {{ $item->sales_name }}</p>
                                </div>
                            </td>

                            <td class="px-8 py-5">
                                <div
                                    class="flex items-center gap-2 text-slate-700 dark:text-slate-400 font-black text-[10px] uppercase tracking-wide">
                                    <i class="fas fa-map-marker-alt text-blue-600/70 dark:text-blue-500/50 text-xs"></i>
                                    {{ $item->city ?: 'KOTA TIDAK DISET' }}
                                </div>
                            </td>

                            <td class="px-8 py-5 text-center">
                                <div
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border {{ $item->status == 'Active' ? 'bg-emerald-500/10 text-emerald-700 border-emerald-500/30' : 'bg-slate-500/10 text-slate-600 border-slate-500/30' }}">
                                    <div
                                        class="w-1.5 h-1.5 rounded-full {{ $item->status == 'Active' ? 'bg-emerald-600 animate-pulse' : 'bg-slate-500' }}">
                                    </div>
                                    <span
                                        class="text-[9px] font-black uppercase tracking-widest">{{ $item->status }}</span>
                                </div>
                            </td>

                            <td class="px-8 py-5">
                                <div
                                    class="flex justify-end gap-2 opacity-50 group-hover:opacity-100 transition-all transform translate-x-2 group-hover:translate-x-0">
                                    <button wire:click="manageTargets({{ $item->id }})"
                                        class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 dark:bg-purple-500/10 dark:text-purple-400 hover:bg-purple-600 hover:text-white dark:hover:bg-purple-500 transition-all flex items-center justify-center shadow-sm border border-purple-100 dark:border-none"
                                        title="Atur Target">
                                        <i class="fas fa-crosshairs text-xs"></i>
                                    </button>
                                    <button wire:click="edit({{ $item->id }})"
                                        class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-500 transition-all flex items-center justify-center shadow-sm border border-blue-100 dark:border-none">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button onclick="confirm('Hapus Salesman ini?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $item->id }})"
                                        class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400 hover:bg-rose-600 hover:text-white dark:hover:bg-rose-500 transition-all flex items-center justify-center shadow-sm border border-rose-100 dark:border-none">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-32 text-center text-slate-400 opacity-20">
                                <i class="fas fa-user-friends text-6xl mb-4"></i>
                                <p class="font-black uppercase text-sm tracking-[0.3em]">No Sales Data Found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($sales->hasPages())
            <div class="px-8 py-6 border-t dark:border-white/5 border-slate-100 bg-slate-50/50 dark:bg-transparent">
                {{ $sales->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- MODAL TARGET (DENGAN FITUR BULK FILL) --}}
    @if($isTargetOpen)
    <div class="fixed inset-0 z-[170] overflow-y-auto px-4" role="dialog">
        <div class="flex items-center justify-center min-h-screen">
            <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md transition-opacity" wire:click="closeModal">
            </div>

            <div
                class="relative dark:bg-[#0d0d0d] bg-white rounded-[2.5rem] shadow-[0_0_60px_rgba(0,0,0,0.5)] w-full max-w-2xl overflow-hidden border dark:border-white/10 border-slate-200 transform transition-all">
                <div
                    class="bg-gradient-to-r from-purple-700 to-indigo-600 px-10 py-8 text-white flex justify-between items-center shadow-lg relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 opacity-10"><i class="fas fa-bullseye text-8xl"></i></div>
                    <div class="relative z-10">
                        <h3 class="font-black uppercase tracking-widest text-sm">Monthly Performance Target</h3>
                        <p class="text-[10px] font-bold opacity-70 mt-1 uppercase tracking-[0.2em]">
                            {{ $selectedSalesNameForTarget }}</p>
                    </div>
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="flex flex-col items-end">
                            <span class="text-[8px] font-black uppercase opacity-60 mb-1">Fiscal Year</span>
                            <select wire:model.live="targetYear"
                                class="bg-white/10 border-none text-white text-[11px] font-black rounded-xl focus:ring-0 cursor-pointer pl-4 pr-8 py-1.5 appearance-none">
                                @for($y = date('Y')-1; $y <= date('Y')+1; $y++) <option value="{{ $y }}"
                                    class="text-slate-800">{{ $y }}</option> @endfor
                            </select>
                        </div>
                        <button wire:click="closeModal"
                            class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all"><i
                                class="fas fa-times text-xs"></i></button>
                    </div>
                </div>

                <div class="p-10">
                    {{-- BULK FILL TOOLBAR --}}
                    <div
                        class="mb-8 p-6 bg-indigo-50 dark:bg-indigo-500/5 rounded-[2rem] border border-indigo-100 dark:border-indigo-500/10">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <h4
                                    class="text-[11px] font-black text-indigo-700 dark:text-indigo-400 uppercase tracking-widest">
                                    Bulk Target Filler</h4>
                                <p class="text-[9px] text-slate-500 mt-0.5">Set nilai ke semua bulan secara otomatis.
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <div class="relative">
                                    <input type="text" wire:model="bulkTargetValue" wire:keydown.enter="applyBulkTarget"
                                        placeholder="E.g: 150j atau 1.2m"
                                        class="pl-4 pr-12 py-2.5 w-48 rounded-xl border-slate-300 dark:bg-[#080808] dark:border-white/10 dark:text-white text-xs font-black uppercase focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner">
                                    <div
                                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-slate-100 dark:bg-white/5 px-1.5 py-0.5 rounded text-[8px] font-black text-slate-400 border border-slate-200 dark:border-white/10">
                                        GO</div>
                                </div>
                                <button wire:click="applyBulkTarget"
                                    class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-[9px] font-black uppercase shadow-lg shadow-indigo-600/20 transition-all active:scale-95">
                                    Apply
                                </button>
                            </div>
                        </div>
                        <div
                            class="mt-4 flex flex-wrap gap-x-4 gap-y-1 border-t border-indigo-100 dark:border-indigo-500/10 pt-3">
                            <span class="text-[8px] font-bold text-indigo-500 uppercase">Shortcuts:</span>
                            <span
                                class="text-[8px] font-medium text-slate-400 italic underline decoration-indigo-300">100rb
                                = 100.000</span>
                            <span
                                class="text-[8px] font-medium text-slate-400 italic underline decoration-indigo-300">50j
                                = 50.000.000</span>
                            <span
                                class="text-[8px] font-medium text-slate-400 italic underline decoration-indigo-300">1.5m
                                = 1.500.000.000</span>
                        </div>
                    </div>

                    {{-- MONTHLY GRID --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-6">
                        @php $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                        'September', 'Oktober', 'November', 'Desember']; @endphp
                        @foreach($months as $index => $month)
                        <div class="group">
                            <label
                                class="text-[9px] font-black uppercase text-slate-500 dark:text-slate-400 group-focus-within:text-purple-600 transition-colors block mb-2 ml-1 tracking-wider">{{ $month }}</label>
                            <div class="relative">
                                <div
                                    class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[10px] font-black text-slate-500 group-focus-within:text-purple-600">
                                    Rp</div>
                                <input type="number" wire:model="monthlyTargets.{{ $index + 1 }}"
                                    class="w-full pl-9 pr-4 py-3 rounded-2xl border-slate-300 dark:bg-white/5 dark:border-white/10 dark:text-white text-[11px] font-black focus:ring-4 focus:ring-purple-500/10 focus:border-purple-600 transition-all shadow-inner bg-slate-50"
                                    placeholder="0">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div
                    class="dark:bg-white/[0.02] bg-slate-100 px-10 py-8 flex justify-end gap-3 border-t dark:border-white/5 border-slate-200">
                    <button wire:click="closeModal"
                        class="px-6 py-2.5 text-[10px] font-black uppercase text-slate-500 hover:text-slate-800 transition-colors tracking-widest">Tutup</button>
                    <button wire:click="saveTargets"
                        class="px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-2xl text-[10px] font-black uppercase shadow-xl shadow-purple-600/30 transition-all active:scale-95 tracking-widest">Set
                        Target Tahunan</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL CRUD SALES (Tetap Sama Seperti Sebelumnya) --}}
    @if($isOpen)
    {{-- ... (Kodingan modal isOpen Anda yang sudah oke) ... --}}
    @endif

</div>