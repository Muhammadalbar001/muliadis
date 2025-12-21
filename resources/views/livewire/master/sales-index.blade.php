<div class="min-h-screen space-y-6 pb-10 transition-colors duration-300 font-jakarta">

    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/5 bg-white/80 border-slate-200 shadow-sm">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2.5 rounded-xl shadow-lg bg-blue-600 text-white">
                    <i class="fas fa-user-tie text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-800">
                        Master <span class="text-blue-500">Salesman</span></h1>
                    <p
                        class="text-[9px] font-bold uppercase tracking-[0.3em] opacity-50 mt-1.5 dark:text-slate-400 text-slate-500">
                        Personnel & Target Management</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 items-center justify-end">
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="px-4 py-2 rounded-xl border text-[11px] font-bold uppercase dark:bg-black/40 dark:border-white/10 dark:text-white bg-slate-100 border-slate-200"
                    placeholder="Cari Sales / Kode...">

                <button wire:click="autoDiscover"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20">
                    <i class="fas fa-magic mr-1" wire:loading.class="fa-spin" wire:target="autoDiscover"></i> Fix Data
                </button>

                <button wire:click="create"
                    class="px-5 py-2 bg-blue-600 text-white rounded-xl text-[10px] font-black uppercase shadow-lg shadow-blue-600/20 transition-all active:scale-95">
                    <i class="fas fa-plus mr-1"></i> Baru
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        <div
            class="rounded-[2.5rem] border overflow-hidden dark:bg-slate-900/40 dark:border-white/5 bg-white border-slate-200 shadow-2xl">
            <table class="w-full text-sm text-left border-collapse uppercase">
                <thead>
                    <tr
                        class="dark:bg-white/5 bg-slate-50 text-slate-500 font-black text-[10px] tracking-widest border-b dark:border-white/5 border-slate-100">
                        <th class="px-6 py-5 w-32">Kode Sales</th>
                        <th class="px-6 py-5">Nama Salesman</th>
                        <th class="px-6 py-5">Kota / Regional</th>
                        <th class="px-6 py-5 text-center">Status</th>
                        <th class="px-6 py-5 text-center">Kontrol</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-white/5 divide-slate-100">
                    @forelse($sales as $item)
                    <tr class="hover:bg-blue-500/[0.02] group transition-colors">
                        <td class="px-6 py-4 font-mono font-bold text-xs">
                            @if($item->sales_code)
                            <span class="text-indigo-500">{{ $item->sales_code }}</span>
                            @else
                            <span
                                class="px-2 py-0.5 bg-rose-50 text-rose-500 rounded text-[9px] font-black animate-pulse">MISSING</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 font-black dark:text-white text-slate-800 text-xs">{{ $item->sales_name }}
                        </td>

                        <td class="px-6 py-4 text-[10px] font-bold dark:text-slate-400 text-slate-500">
                            {{ $item->city ?: '-' }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span
                                class="px-3 py-1 rounded-full text-[9px] font-black border {{ $item->status == 'Active' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-50 text-slate-400 border-slate-100' }}">
                                {{ strtoupper($item->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div
                                class="flex justify-center gap-1.5 opacity-60 group-hover:opacity-100 transition-opacity">
                                <button wire:click="manageTargets({{ $item->id }})"
                                    class="w-8 h-8 rounded-lg border border-slate-200 text-purple-500 hover:bg-purple-500 hover:text-white transition-all shadow-sm"
                                    title="Atur Target">
                                    <i class="fas fa-crosshairs text-[10px]"></i>
                                </button>

                                <button wire:click="edit({{ $item->id }})"
                                    class="w-8 h-8 rounded-lg border border-slate-200 text-blue-500 hover:bg-blue-500 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-edit text-[10px]"></i>
                                </button>

                                <button onclick="confirm('Hapus Salesman ini?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $item->id }})"
                                    class="w-8 h-8 rounded-lg border border-slate-200 text-rose-500 hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-trash-alt text-[10px]"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center opacity-20 font-black uppercase text-xs">Belum ada
                            data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-5 border-t dark:border-white/5 border-slate-100">{{ $sales->links() }}</div>
        </div>
    </div>

    @if($isOpen)
    <div class="fixed inset-0 z-[160] overflow-y-auto" role="dialog">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md" wire:click="closeModal"></div>
            <div
                class="relative dark:bg-[#0a0a0a] bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden border dark:border-white/10 border-slate-200">
                <div class="bg-blue-600 px-8 py-6 text-white flex justify-between items-center shadow-lg">
                    <h3 class="font-black uppercase tracking-widest text-sm">
                        {{ $salesId ? 'Edit Identitas Sales' : 'Tambah Sales Baru' }}</h3>
                    <button wire:click="closeModal" class="opacity-70 hover:opacity-100"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="p-8 space-y-5">
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 block mb-1">Kode Sales
                            (Wajib)</label>
                        <input type="text" wire:model="sales_code"
                            class="w-full rounded-xl border-slate-200 dark:bg-white/5 dark:text-white text-xs font-bold uppercase focus:ring-blue-500"
                            placeholder="CONTOH: SLS001">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 block mb-1">Nama Salesman (Sesuai
                            KTP/Sistem)</label>
                        <input type="text" wire:model="sales_name"
                            class="w-full rounded-xl border-slate-200 dark:bg-white/5 dark:text-white text-xs font-bold uppercase focus:ring-blue-500">
                        @error('sales_name') <span
                            class="text-rose-500 text-[10px] uppercase font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 block mb-1">Kota/Area</label>
                            <input type="text" wire:model="city"
                                class="w-full rounded-xl border-slate-200 dark:bg-white/5 dark:text-white text-xs font-bold uppercase">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 block mb-1">Status</label>
                            <select wire:model="status"
                                class="w-full rounded-xl border-slate-200 dark:bg-white/5 dark:text-white text-xs font-bold uppercase">
                                <option value="Active">ACTIVE</option>
                                <option value="Inactive">INACTIVE</option>
                            </select>
                        </div>
                    </div>
                    <p class="text-[10px] text-amber-500 italic mt-2"><i class="fas fa-info-circle mr-1"></i> Mengubah
                        Nama Salesman akan otomatis memperbarui seluruh riwayat transaksi (Penjualan/Retur/AR) dari nama
                        lama ke nama baru.</p>
                </div>
                <div
                    class="dark:bg-white/[0.02] bg-slate-50 px-8 py-6 flex justify-end gap-3 border-t dark:border-white/5 border-slate-100">
                    <button wire:click="closeModal"
                        class="px-6 py-2.5 text-[10px] font-black uppercase text-slate-400">Batal</button>
                    <button wire:click="store"
                        class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[10px] font-black uppercase shadow-lg">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($isTargetOpen)
    <div class="fixed inset-0 z-[170] overflow-y-auto" role="dialog">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md" wire:click="closeModal"></div>
            <div
                class="relative dark:bg-[#0a0a0a] bg-white rounded-[2.5rem] shadow-2xl w-full max-w-2xl overflow-hidden border dark:border-white/10 border-slate-200">
                <div class="bg-purple-600 px-8 py-6 text-white flex justify-between items-center shadow-lg">
                    <div>
                        <h3 class="font-black uppercase tracking-widest text-sm">Target Penjualan</h3>
                        <p class="text-[10px] font-bold opacity-70 mt-1 uppercase">{{ $selectedSalesNameForTarget }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <select wire:model.live="targetYear"
                            class="bg-white/10 border-none text-white text-xs font-bold rounded-lg focus:ring-0">
                            @for($y = date('Y')-1; $y <= date('Y')+1; $y++) <option value="{{ $y }}"
                                class="text-slate-800">{{ $y }}</option>
                                @endfor
                        </select>
                        <button wire:click="closeModal" class="opacity-70 hover:opacity-100"><i
                                class="fas fa-times"></i></button>
                    </div>
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        @php $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                        'September', 'Oktober', 'November', 'Desember']; @endphp
                        @foreach($months as $index => $month)
                        <div>
                            <label
                                class="text-[9px] font-black uppercase text-slate-400 block mb-1">{{ $month }}</label>
                            <div class="relative">
                                <span
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-slate-400">Rp</span>
                                <input type="number" wire:model="monthlyTargets.{{ $index + 1 }}"
                                    class="w-full pl-8 pr-3 py-2 rounded-xl border-slate-200 dark:bg-white/5 dark:text-white text-xs font-bold focus:ring-purple-500"
                                    placeholder="0">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div
                    class="dark:bg-white/[0.02] bg-slate-50 px-8 py-6 flex justify-end gap-3 border-t dark:border-white/5 border-slate-100">
                    <button wire:click="closeModal"
                        class="px-6 py-2.5 text-[10px] font-black uppercase text-slate-400">Tutup</button>
                    <button wire:click="saveTargets"
                        class="px-8 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-[10px] font-black uppercase shadow-lg">Simpan
                        Target</button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>