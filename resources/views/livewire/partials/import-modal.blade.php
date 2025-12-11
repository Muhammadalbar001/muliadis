@php
// Mapping warna agar Tailwind bisa mendeteksi class-nya saat compile
$theme = match($color ?? 'indigo') {
'emerald' => [
'header' => 'from-emerald-600 to-teal-600',
'text_sub' => 'text-emerald-100',
'border_hover' => 'hover:border-emerald-400',
'icon' => 'text-emerald-500',
'label_text' => 'text-emerald-600 hover:text-emerald-500',
'loading' => 'text-emerald-600',
'file_bg' => 'bg-emerald-50',
'file_border' => 'border-emerald-100',
'file_text' => 'text-emerald-700',
'checkbox' => 'text-emerald-600 focus:ring-emerald-500',
'checkbox_bg' => 'bg-emerald-50 border-emerald-100',
'checkbox_txt' => 'text-emerald-700',
'btn_submit' => 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/30',
],
'rose' => [
'header' => 'from-rose-600 to-pink-600',
'text_sub' => 'text-rose-100',
'border_hover' => 'hover:border-rose-400',
'icon' => 'text-rose-500',
'label_text' => 'text-rose-600 hover:text-rose-500',
'loading' => 'text-rose-600',
'file_bg' => 'bg-rose-50',
'file_border' => 'border-rose-100',
'file_text' => 'text-rose-700',
'checkbox' => 'text-rose-600 focus:ring-rose-500',
'checkbox_bg' => 'bg-rose-50 border-rose-100',
'checkbox_txt' => 'text-rose-700',
'btn_submit' => 'bg-rose-600 hover:bg-rose-700 shadow-rose-500/30',
],
'orange' => [
'header' => 'from-orange-500 to-amber-600',
'text_sub' => 'text-orange-100',
'border_hover' => 'hover:border-orange-400',
'icon' => 'text-orange-500',
'label_text' => 'text-orange-600 hover:text-orange-500',
'loading' => 'text-orange-600',
'file_bg' => 'bg-orange-50',
'file_border' => 'border-orange-100',
'file_text' => 'text-orange-700',
'checkbox' => 'text-orange-600 focus:ring-orange-500',
'checkbox_bg' => 'bg-orange-50 border-orange-100',
'checkbox_txt' => 'text-orange-700',
'btn_submit' => 'bg-orange-500 hover:bg-orange-600 shadow-orange-500/30',
],
'cyan' => [
'header' => 'from-cyan-600 to-blue-600',
'text_sub' => 'text-cyan-100',
'border_hover' => 'hover:border-cyan-400',
'icon' => 'text-cyan-500',
'label_text' => 'text-cyan-600 hover:text-cyan-500',
'loading' => 'text-cyan-600',
'file_bg' => 'bg-cyan-50',
'file_border' => 'border-cyan-100',
'file_text' => 'text-cyan-700',
'checkbox' => 'text-cyan-600 focus:ring-cyan-500',
'checkbox_bg' => 'bg-cyan-50 border-cyan-100',
'checkbox_txt' => 'text-cyan-700',
'btn_submit' => 'bg-cyan-600 hover:bg-cyan-700 shadow-cyan-500/30',
],
default => [ // Fallback ke Indigo
'header' => 'from-indigo-600 to-purple-600',
'text_sub' => 'text-indigo-100',
'border_hover' => 'hover:border-indigo-400',
'icon' => 'text-indigo-500',
'label_text' => 'text-indigo-600 hover:text-indigo-500',
'loading' => 'text-indigo-600',
'file_bg' => 'bg-indigo-50',
'file_border' => 'border-indigo-100',
'file_text' => 'text-indigo-700',
'checkbox' => 'text-indigo-600 focus:ring-indigo-500',
'checkbox_bg' => 'bg-indigo-50 border-indigo-100',
'checkbox_txt' => 'text-indigo-700',
'btn_submit' => 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-500/30',
]
};
@endphp

<div class="fixed inset-0 z-[60] overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

        <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" wire:click="closeImportModal">
        </div>

        <div
            class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-white/20">

            <div class="bg-gradient-to-r {{ $theme['header'] }} px-6 py-4 border-b border-white/10">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fas fa-file-import"></i> {{ $title }}
                </h3>
                <p class="{{ $theme['text_sub'] }} text-xs mt-0.5">Upload file Excel untuk memperbarui data.</p>
            </div>

            <div class="px-6 py-6">
                <div class="mb-4">
                    <div
                        class="w-full flex justify-center px-6 pt-8 pb-8 border-2 border-slate-300 border-dashed rounded-xl hover:bg-slate-50 cursor-pointer relative transition-all group {{ $theme['border_hover'] }} bg-slate-50/50">
                        <div class="space-y-2 text-center">
                            <div
                                class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                                <i class="fas fa-cloud-upload-alt {{ $theme['icon'] }} text-xl"></i>
                            </div>
                            <div class="text-sm text-slate-600">
                                <label for="file-upload"
                                    class="relative cursor-pointer rounded-md font-bold {{ $theme['label_text'] }} focus-within:outline-none">
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
                <div class="{{ $theme['checkbox_bg'] }} rounded-xl p-4 mb-4">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="resetData"
                            class="mt-1 h-4 w-4 {{ $theme['checkbox'] }} border-gray-300 rounded">
                        <div>
                            <span class="block text-sm font-bold {{ $theme['checkbox_txt'] }}">Hapus Data Lama?</span>
                            <span class="block text-xs {{ $theme['checkbox_txt'] }} opacity-80 mt-0.5">Jika dicentang,
                                semua data sebelumnya akan dihapus.</span>
                        </div>
                    </label>
                </div>
                @endif

                <div wire:loading wire:target="file" class="w-full text-center py-2">
                    <span class="inline-flex items-center text-xs {{ $theme['loading'] }} font-bold animate-pulse">
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
                    class="p-3 {{ $theme['file_bg'] }} border {{ $theme['file_border'] }} {{ $theme['file_text'] }} text-xs rounded-lg flex items-center gap-2 mb-4">
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
                    class="w-full sm:w-auto {{ $theme['btn_submit'] }} text-white px-5 py-2.5 rounded-xl font-bold text-sm transition shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                    Import Sekarang
                </button>
                <button wire:click="closeImportModal"
                    class="w-full sm:w-auto bg-white border border-slate-300 text-slate-700 px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-50 transition">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>