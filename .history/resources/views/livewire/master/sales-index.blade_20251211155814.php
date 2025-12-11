<div class="space-y-6 font-jakarta">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-white/90 p-4 rounded-b-2xl shadow-sm border-b border-slate-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Data Salesman</h1>
                <p class="text-xs text-slate-500 mt-0.5">Kelola tim penjualan dan target.</p>
            </div>

            <button wire:click="create"
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-plus mr-2"></i> Sales Baru
            </button>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="w-full md:w-1/2 relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-10 w-full border-slate-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400 transition-all"
                    placeholder="Cari Nama, Kode, atau Cabang...">
            </div>
            <div class="w-full md:w-auto">
                <select wire:model.live="filterStatus"
                    class="w-full border-slate-200 rounded-xl text-sm focus:ring-blue-500 text-slate-600">
                    <option value="Active">Status: Aktif</option>
                    <option value="Inactive">Status: Non-Aktif</option>
                    <option value="">Semua Status</option>
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 font-bold text-slate-500 uppercase border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Nama Sales</th>
                        <th class="px-6 py-4">Kode</th>
                        <th class="px-6 py-4">Cabang</th>
                        <th class="px-6 py-4">Divisi</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($sales as $item)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-3 font-bold text-slate-700">{{ $item->sales_name }}</td>
                        <td class="px-6 py-3 font-mono text-slate-500 text-xs">{{ $item->sales_code }}</td>
                        <td class="px-6 py-3 text-slate-600">{{ $item->city }}</td>
                        <td class="px-6 py-3 text-slate-600">{{ $item->divisi }}</td>
                        <td class="px-6 py-3 text-center">
                            <span
                                class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $item->status == 'Active' ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div
                                class="flex justify-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                <button wire:click="edit({{ $item->id }})"
                                    class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all"><i
                                        class="fas fa-edit"></i></button>
                                <button wire:click="delete({{ $item->id }})"
                                    onclick="return confirm('Hapus sales ini?') || event.stopImmediatePropagation()"
                                    class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all"><i
                                        class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-400">Belum ada data sales.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $sales->links() }}</div>
    </div>

    @if($isOpen)
    <div class="fixed inset-0 z-[60] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" wire:click="closeModal">
            </div>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div
                    class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 border-b border-white/10 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">{{ $salesId ? 'Edit Salesman' : 'Tambah Salesman' }}</h3>
                    <button wire:click="closeModal" class="text-white/70 hover:text-white"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Sales</label>
                        <input type="text" wire:model="sales_name"
                            class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500">
                        @error('sales_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kode</label>
                            <input type="text" wire:model="sales_code"
                                class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Cabang (City)</label>
                            <input type="text" wire:model="city"
                                class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Divisi</label>
                            <input type="text" wire:model="divisi"
                                class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Status</label>
                            <select wire:model="status"
                                class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-2">
                    <button wire:click="closeModal"
                        class="px-4 py-2 bg-white border border-slate-300 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Batal</button>
                    <button wire:click="store"
                        class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/30">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>