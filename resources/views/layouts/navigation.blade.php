<nav class="bg-white border-b border-slate-200 sticky top-0 z-30 h-16 w-full">
    <div class="px-4 h-full flex justify-between items-center">

        <button @click="sidebarOpen = !sidebarOpen"
            class="text-slate-500 hover:text-indigo-600 focus:outline-none lg:hidden">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <div class="hidden lg:flex items-center text-sm font-medium text-slate-500 ml-4">
            <span class="text-slate-400">Aplikasi</span>
            <i class="fas fa-chevron-right text-[10px] mx-2 text-slate-300"></i>
            <span class="text-indigo-600 font-bold">{{ $header ?? 'Dashboard' }}</span>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-bold text-slate-700">{{ date('l, d F Y') }}</p>
                <p class="text-[10px] text-slate-400">PT. Mulia Anugerah</p>
            </div>
            <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-slate-400">
                <i class="far fa-bell"></i>
            </div>
        </div>
    </div>
</nav>