<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
                Master Salesman
            </h2>
            <p class="text-slate-500 text-sm">Kelola data tim penjualan Anda di sini.</p>
        </div>

        <div class="flex-shrink-0">
            <button wire:click="create"
                class="inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/20 hover:-translate-y-0.5">
                <i class="fas fa-user-plus mr-2"></i> Tambah Sales Baru
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden relative">

        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 to-white/0 pointer-events-none"></div>

        <div
            class="relative z-10 p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100">

            <div class="relative w-full sm:w-72">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400"></i>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 sm:text-sm transition-all"
                    placeholder="Cari nama sales...">
            </div>

            <div class="flex items-center gap-2 text-sm text-slate-500">
                <span>Tampilkan:</span>
                <select wire:model.live="perPage"
                    class="py-2 pl-3 pr-8 border border-slate-200 bg-slate-50 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 sm:text-sm transition-all cursor-pointer">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        <div class="relative z-10 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead>
                    <tr class="bg-slate-50/80">
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                            ID Sales
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Nama Lengkap
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Jumlah Outlet (OA)
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($sales as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">
                            #{{ $item->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-9 w-9 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm">
                                    {{ substr($item->nama_sales, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-slate-900">{{ $item->nama_sales }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-emerald-100 text-emerald-700">
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                            <span class="flex items-center gap-1">
                                <i class="fas fa-store text-slate-400"></i> - Outlet
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end items-center gap-2">
                                <button wire:click="edit({{ $item->id }})"
                                    class="p-2 rounded-lg text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-all"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="confirmDelete({{ $item->id }})"
                                    class="p-2 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition-all"
                                    title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-users-slash text-4xl text-slate-300"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Belum ada Data Sales</h3>
                                <p class="text-sm text-slate-500 mb-4 max-w-xs">Data salesman Anda masih kosong. Silakan
                                    tambahkan data baru.</p>
                                @if($search)
                                <p class="text-sm text-indigo-600 font-medium">
                                    Tidak ditemukan hasil untuk "{{ $search }}"
                                </p>
                                @else
                                <button wire:click="create"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition-all shadow-md hover:-translate-y-0.5">
                                    <i class="fas fa-plus mr-1"></i> Tambah Data
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
        <div class="relative z-10 px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $sales->links() }} {{-- Pastikan Anda sudah publish vendor pagination tailwind --}}
        </div>
        @endif
    </div>

    <x-modal wire:model="isOpen">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-900">
                    {{ $salesId ? 'Edit Data Sales' : 'Tambah Sales Baru' }}
                </h3>
                <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form wire:submit.prevent="store" class="space-y-5">
                <div>
                    <label for="nama_sales" class="block text-sm font-bold text-slate-700 mb-2">
                        Nama Lengkap Salesman <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span
                            class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 pointer-events-none">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" id="nama_sales" wire:model.defer="nama_sales"
                            class="block w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 bg-slate-50 font-medium text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all @error('nama_sales') border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:ring-red-200 @enderror"
                            placeholder="Contoh: Budi Santoso">
                    </div>
                    @error('nama_sales')
                    <p class="mt-2 text-sm text-red-600 font-bold flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" wire:click="closeModal"
                        class="px-5 py-2.5 text-sm font-bold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-all">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all shadow-md shadow-indigo-500/20 hover:-translate-y-0.5 flex items-center gap-2">
                        <span wire:loading.remove wire:target="store">
                            <i class="fas fa-save"></i> Simpan Data
                        </span>
                        <span wire:loading flex wire:target="store" class="items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

</div>