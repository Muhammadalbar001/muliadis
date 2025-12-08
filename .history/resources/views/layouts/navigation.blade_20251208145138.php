<nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30 h-16 w-full shadow-sm">
    <div class="px-4 lg:px-8 h-full flex justify-between items-center">

        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen"
                class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-indigo-600 focus:outline-none transition-all">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <div class="hidden md:flex items-center text-sm font-medium text-slate-400">
                <span class="hover:text-indigo-600 cursor-default"><i class="fas fa-home mr-1"></i> App</span>
                <i class="fas fa-chevron-right text-[10px] mx-3 text-slate-300"></i>
                <span
                    class="text-indigo-600 font-bold bg-indigo-50 px-2 py-1 rounded-md">{{ $header ?? 'Halaman' }}</span>
            </div>
        </div>

        <div class="flex items-center gap-6">

            <div class="hidden md:block text-right">
                <p class="text-xs font-bold text-slate-700">{{ date('l, d F Y') }}</p>
                <p class="text-[10px] text-slate-400">PT. Mulia Anugerah Distribusindo</p>
            </div>

            <button
                class="relative p-2 text-slate-400 hover:text-indigo-600 hover:bg-slate-50 rounded-full transition-colors">
                <i class="far fa-bell text-xl"></i>
                <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
            </button>

        </div>
    </div>
</nav>