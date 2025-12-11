<div class="fixed inset-0 z-[60] overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

        <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" wire:click="closeImportModal">
        </div>

        <div
            class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-white/20">

            <div
                class="bg-gradient-to-r from-{{ $color }}-600 to-{{ $color === 'rose' ? 'pink' : ($color === 'orange' ? 'amber' : ($color === 'cyan' ? 'blue' : 'teal')) }}-600 px-6 py-4 border-b border-white/10">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fas fa-file-import"></i> {{ $title }}
                </h3>
                <p class="text-{{ $color }}-100 text-xs mt-0.5">Upload file Excel untuk memperbarui data.</p>
            </div>

            <div class="px-6 py-6">
                <div class="mb-4">
                    <div
                        class="w-full flex justify-center px-6 pt-8 pb-8 border-2 border-slate-300 border-dashed rounded-xl hover:bg-slate-50 cursor-pointer relative transition-all group hover:border-{{ $color }}-400 bg-slate-50/50">
                        <div class="space-y-2 text-center">
                            <div
                                class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                                <i class="fas fa-cloud-upload-alt text-{{ $color }}-500 text-xl"></i>
                            </div>
                            <div class="text-sm text-slate-600">
                                <label for="file-upload"
                                    class="relative cursor-pointer rounded-md font-bold text-{{ $color }}-600 hover:text-{{ $color }}-500 focus-within:outline-none">
                                    <span>Klik Upload</span>
                                    <input id="file-upload" wire:model="file" type="file" class="sr-only">
                                </label>
                                <span class="pl-1 font-medium">atau drag file</span>
                            </div>
                            <p class="text-xs text-slate-400">XLSX, CSV (Max 150MB)</p>
                        </div>
                    </div>
                </div>

                @if(property_exists($this, 'resetData'))
                <div class="bg-{{ $color }}-50 border border-{{ $color }}-100 rounded-xl p-4 mb-4">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="resetData"
                            class="mt-1 h-4 w-4 text-{{ $color }}-600 focus:ring-{{ $color }}-500 border-gray-300 rounded">
                        <div>
                            <span class="block text-sm font-bold text-{{ $color }}-700">Hapus Data Lama?</span>
                            <span class="block text-xs text-{{ $color }}-600 mt-0.5">Jika dicentang, semua data
                                sebelumnya akan dihapus.</span>
                        </div>
                    </label>
                </div>
                @endif

                <div wire:loading wire:target="file" class="w-full text-center py-2">
                    <span class="inline-flex items-center text-xs text-{{ $color }}-600 font-bold animate-pulse">
                        <i class="fas fa-spinner fa-spin mr-2"></i> Mengupload File...
                    </span>
                </div>

                <div wire:loading wire:target="import"
                    class="w-full text-center py-4 bg-yellow-50 border border-yellow-100 rounded-lg mt-2">
                    <div class="flex flex-col items-center justify-center text-yellow-700">
                        <i class="fas fa-cog fa-spin text-2xl mb-2"></i>
                        <span class="font-bold text-sm">Sedang Memproses Data Besar...</span>
                        <span class="text-xs mt-1">Mohon <b>JANGAN TUTUP</b> halaman ini.</span>
                    </div>
                </div>

                @if($file)
                <div
                    class="p-3 bg-{{ $color }}-50 border border-{{ $color }}-100 text-{{ $color }}-700 text-xs rounded-lg flex items-center gap-2 mb-4">
                    <i class="fas fa-file-excel text-lg"></i> {{ $file->getClientOriginalName() }}
                </div>
                @endif

                @error('file')
                <div
                    class="p-3 bg-red-50 border border-red-100 text-red-600 text-xs rounded-lg flex items-center gap-2 mt-2">
                    <i class="fas fa-exclamation-circle text-lg"></i> {{ $message }}
                </div>
                @enderror
            </div>

            <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-slate-200">

                <button wire:click="import" wire:loading.attr="disabled"
                    class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-bold text-sm text-white shadow-lg transition-all transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed bg-{{ $color }}-600 hover:bg-{{ $color }}-700 focus:ring-2 focus:ring-{{ $color }}-500/50">

                    <span wire:loading.remove wire:target="import">
                        Import Sekarang
                    </span>

                    <span wire:loading wire:target="import" class="flex items-center justify-center gap-2">
                        <i class="fas fa-spinner fa-spin"></i> Proses...
                    </span>

                </button>

                <button wire:click="closeImportModal"
                    class="w-full sm:w-auto bg-white border border-slate-300 text-slate-700 px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-50 transition">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>