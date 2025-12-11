<div class="space-y-6 font-jakarta">

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-indigo-50 flex flex-col md:flex-row justify-between items-end gap-4">

        <div class="w-full md:w-3/4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="relative group">
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-10 pr-4 py-2.5 w-full border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 placeholder-slate-400 transition-all group-hover:border-indigo-300"
                    placeholder="Cari Nama Sales...">
                <div class="absolute left-3.5 top-3 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <div class="relative">
                <select wire:model.live="filterCity"
                    class="w-full pl-4 pr-10 py-2.5 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-600 appearance-none cursor-pointer hover:border-indigo-300 transition-colors bg-white">
                    <option value="">Semua Kota / Cabang</option>
                    @foreach($optCity as $c)
                    <option value="{{ $c }}">{{ $c }}</option>
                    @endforeach
                </select>
                <div class="absolute right-3.5 top-3.5 text-slate-400 pointer-events-none">
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
            </div>

            <div class="relative">
                <select wire:model.live="filterDivisi"
                    class="w-full pl-4 pr-10 py-2.5 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-slate-600 appearance-none cursor-pointer hover:border-indigo-300 transition-colors bg-white">
                    <option value="">Semua Divisi</option>
                    @foreach($optDivisi as $d)
                    <option value="{{ $d }}">{{ $d }}</option>
                    @endforeach
                </select>
                <div class="absolute right-3.5 top-3.5 text-slate-400 pointer-events-none">
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
            </div>
        </div>

        <div class="flex gap-2 w-full md:w-auto">
            <button wire:click="resetAllTargets"
                onclick="return confirm('Apakah Anda yakin ingin mereset SEMUA target sales menjadi 0?') || event.stopImmediatePropagation()"
                class="flex-1 md:flex-none inline-flex justify-center items-center px-4 py-2.5 bg-white text-rose-600 border border-rose-100 hover:bg-rose-50 hover:border-rose-200 text-sm font-bold rounded-xl shadow-sm transition-all"
                title="Hapus semua target IMS & OA">
                <i class="fas fa-eraser mr-2"></i> Reset
            </button>

            <button wire:click="openImportModal"
                class="flex-1 md:flex-none inline-flex justify-center items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-file-import mr-2"></i> Import
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 w-16 text-center font-bold text-slate-600 text-xs uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 font-bold text-slate-600 text-xs uppercase tracking-wider">Nama Sales</th>
                        <th class="px-6 py-4 font-bold text-slate-600 text-xs uppercase tracking-wider">Divisi</th>
                        <th class="px-6 py-4 text-center font-bold text-slate-600 text-xs uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center font-bold text-slate-600 text-xs uppercase tracking-wider">Target</th>
                        <th class="px-6 py-4 font-bold text-slate-600 text-xs uppercase tracking-wider">Kota</th>
                        <th class="px-6 py-4 text-center font-bold text-slate-600 text-xs uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sales as $index => $item)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4 text-center text-slate-500 text-xs font-mono">
                            {{ $sales->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">{{ $item->sales_name }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $item->divisi ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(strtolower($item->status) == 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> ACTIVE
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                {{ strtoupper($item->status) }}
                            </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <button wire:click="openTargetModal({{ $item->id }})"
                                class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg text-xs font-bold border border-indigo-100 transition-colors group/btn">
                                <i class="fas fa-bullseye mr-1.5 group-hover/btn:scale-110 transition-transform"></i> Set Target
                            </button>
                        </td>

                        <td class="px-6 py-4 text-slate-600 font-medium text-xs">
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-50 border border-slate-100">
                                <i class="fas fa-map-marker-alt text-slate-400"></i> {{ $item->city }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Yakin ingin menghapus sales ini?') || event.stopImmediatePropagation()"
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all"
                                title="Hapus Data">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-users-slash text-3xl text-slate-300"></i>
                                </div>
                                <h3 class="text-slate-900 font-bold text-lg">Tidak ada data sales</h3>
                                <p class="text-slate-500 text-sm mt-1 mb-4">Belum ada data sales yang sesuai dengan filter Anda.</p>
                                <button wire:click="openImportModal" class="text-indigo-600 font-bold hover:underline text-sm">
                                    Import Data Baru
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
            {{ $sales->links() }}
        </div>
        @endif
    </div>

    @if($isImportOpen)
    <div class="fixed inset-0 z-[60] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" wire:click="closeImportModal"></div>

            <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-white/20">
                
                <div class="bg-gradient-to-r from-indigo-600 to-violet-600 px-6 py-4 border-b border-white/10">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fas fa-file-import"></i> Import Master Sales
                    </h3>
                    <p class="text-indigo-100 text-xs mt-0.5">Upload file Excel data salesman.</p>
                </div>

                <div class="px-6 py-6">
                    <div class="mb-4">
                        <div class="w-full flex justify-center px-6 pt-8 pb-8 border-2 border-slate-300 border-dashed rounded-xl hover:bg-slate-50 cursor-pointer relative transition-all group hover:border-indigo-400 bg-slate-50/50">
                            <div class="space-y-2 text-center">
                                <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                                    <i class="fas fa-cloud-upload-alt text-indigo-500 text-xl"></i>
                                </div>
                                <div class="text-sm text-slate-600">
                                    <label for="file-upload-sales" class="relative cursor-pointer rounded-md font-bold text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                        <span>Klik Upload</span>
                                        <input id="file-upload-sales" wire:model="file" type="file" class="sr-only">
                                    </label>
                                    <span class="pl-1 font-medium">atau drag file</span>
                                </div>
                                <p class="text-xs text-slate-400">XLSX, CSV (Max 10MB)</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-rose-50 border border-rose-100 rounded-xl p-4 mb-4">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input id="resetData" type="checkbox" wire:model="resetData" class="mt-1 h-4 w-4 text-rose-600 focus:ring-rose-500 border-gray-300 rounded">
                            <div>
                                <span class="block text-sm font-bold text-rose-700">Reset Semua Data?</span>
                                <span class="block text-xs text-rose-600 mt-0.5">Jika dicentang, data sales lama akan dihapus total.</span>
                            </div>
                        </label>
                    </div>

                    <div wire:loading wire:target="file" class="w-full text-center py-2">
                        <span class="inline-flex items-center text-xs text-indigo-600 font-bold animate-pulse">
                            <i class="fas fa-spinner fa-spin mr-2"></i> Mengupload File...
                        </span>
                    </div>
                    <div wire:loading wire:target="import" class="w-full text-center py-2">
                        <span class="inline-flex items-center text-xs text-emerald-600 font-bold animate-pulse">
                            <i class="fas fa-cog fa-spin mr-2"></i> Memproses Data...
                        </span>
                    </div>

                    @error('file')
                    <div class="p-3 bg-red-50 border border-red-100 text-red-600 text-xs rounded-lg flex items-center gap-2">
                        <i class="fas fa-exclamation-circle text-lg"></i> {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-slate-200">
                    <button wire:click="import" wire:loading.attr="disabled"
                        class="w-full inline-flex justify-center items-center rounded-xl px-4 py-2.5 bg-indigo-600 text-sm font-bold text-white hover:bg-indigo-700 focus:outline-none shadow-lg shadow-indigo-500/30 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        Mulai Import
                    </button>
                    <button wire:click="closeImportModal"
                        class="w-full inline-flex justify-center items-center rounded-xl border border-slate-300 px-4 py-2.5 bg-white text-sm font-bold text-slate-700 hover:bg-slate-50 focus:outline-none transition-all">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($isTargetOpen)
    <div class="fixed inset-0 z-[60] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" wire:click="closeTargetModal"></div>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl w-full border border-white/20">

                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 flex justify-between items-center border-b border-white/10">
                    <div>
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <i class="fas fa-bullseye"></i> Set Target Sales
                        </h3>
                        <p class="text-blue-100 text-xs mt-0.5">Salesman: <span class="font-bold text-white bg-white/20 px-2 py-0.5 rounded ml-1">{{ $selectedSalesName }}</span></p>
                    </div>

                    <div class="relative">
                        <select wire:model.live="targetYear"
                            class="text-sm rounded-lg border-blue-400 bg-blue-700/50 text-white font-bold cursor-pointer hover:bg-blue-700 focus:ring-2 focus:ring-white pr-8 py-1.5">
                            @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++) 
                                <option value="{{ $y }}" class="text-slate-800">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="px-6 py-6 bg-slate-50 max-h-[65vh] overflow-y-auto custom-scrollbar">
                    <div class="grid grid-cols-12 gap-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 px-2">
                        <div class="col-span-2 pt-2">Bulan</div>
                        <div class="col-span-5 text-right">Target Omzet (Rp)</div>
                        <div class="col-span-5 text-right">Target Toko (OA)</div>
                    </div>

                    <div class="space-y-2.5">
                        @foreach(range(1, 12) as $m)
                        <div class="grid grid-cols-12 gap-4 items-center bg-white p-3 rounded-xl border border-slate-200 shadow-sm hover:border-indigo-300 hover:shadow-md transition-all group">

                            <div class="col-span-2 font-bold text-slate-700 text-sm flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs border border-slate-200 group-hover:bg-indigo-50 group-hover:text-indigo-600 group-hover:border-indigo-100 transition-colors">{{ $m }}</span>
                                <span class="hidden sm:inline">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('M') }}</span>
                            </div>

                            <div class="col-span-5">
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-slate-400 text-xs font-bold pointer-events-none group-focus-within:text-indigo-500">Rp</span>
                                    <input type="number" wire:model="targets.{{ $m }}.ims"
                                        class="w-full pl-9 pr-3 py-2 text-sm border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-right font-mono transition-all placeholder-slate-300"
                                        placeholder="0">
                                </div>
                            </div>

                            <div class="col-span-5">
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-slate-400 text-xs pointer-events-none group-focus-within:text-indigo-500"><i class="fas fa-store"></i></span>
                                    <input type="number" wire:model="targets.{{ $m }}.oa"
                                        class="w-full pl-9 pr-3 py-2 text-sm border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-right font-mono transition-all placeholder-slate-300"
                                        placeholder="0">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white px-6 py-4 border-t border-slate-200 flex justify-end gap-3">
                    <button wire:click="closeTargetModal" type="button"
                        class="px-5 py-2.5 bg-white text-slate-700 border border-slate-300 rounded-xl text-sm font-bold hover:bg-slate-50 focus:outline-none transition-all">
                        Batal
                    </button>
                    <button wire:click="saveTargets" type="button"
                        class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 focus:outline-none shadow-lg shadow-indigo-500/30 flex items-center gap-2 transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-save"></i> Simpan Target
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>