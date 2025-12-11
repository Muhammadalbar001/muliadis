<div x-data="{ 
        show: false, 
        message: '', 
        type: 'success',
        timeout: null 
    }" x-init="
        window.addEventListener('show-toast', event => { 
            let data = event.detail;
            // Handle jika event dikirim dalam array (Livewire 3 behavior)
            if (Array.isArray(data) && data.length > 0) data = data[0];

            message = data.message;
            type = data.type || 'success';
            show = true;

            // Clear timeout lama jika ada spam klik
            if(timeout) clearTimeout(timeout);

            // Auto hide setelah 3 detik
            timeout = setTimeout(() => { show = false }, 3000);
        })
    " class="fixed top-6 right-6 z-[100] flex flex-col gap-2 pointer-events-none" style="display: none;" x-show="show"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2 scale-90"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 translate-y-2 scale-90">

    <div class="pointer-events-auto flex items-center w-full max-w-xs p-4 space-x-4 text-gray-500 bg-white rounded-2xl shadow-2xl border border-slate-100 ring-1 ring-black/5"
        role="alert">

        <div x-show="type === 'success'"
            class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-emerald-500 bg-emerald-100 rounded-lg">
            <i class="fas fa-check"></i>
        </div>

        <div x-show="type === 'error'"
            class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-rose-500 bg-rose-100 rounded-lg">
            <i class="fas fa-times"></i>
        </div>

        <div x-show="type === 'info'"
            class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg">
            <i class="fas fa-info"></i>
        </div>

        <div class="pl-2 text-sm font-bold text-slate-700" x-text="message"></div>

        <button @click="show = false" type="button"
            class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8">
            <i class="fas fa-times"></i>
        </button>
    </div>

</div>