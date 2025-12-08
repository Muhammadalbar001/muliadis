<nav class="bg-white border-b border-slate-200 sticky top-0 z-30 h-16">
    <div class="px-4 lg:px-8 h-full flex justify-between items-center">

        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen"
                class="text-slate-500 hover:text-indigo-600 focus:outline-none transition-transform active:scale-95">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <div class="hidden md:flex items-center text-sm font-medium text-slate-500">
                <span class="hover:text-indigo-600 cursor-default">App</span>
                <i class="fas fa-chevron-right text-[10px] mx-2 text-slate-300"></i>
                <span class="text-indigo-600 font-bold">{{ $header ?? 'Dashboard' }}</span>
            </div>
        </div>

        <div class="flex items-center gap-4">

            <div class="hidden md:block text-right mr-2">
                <p class="text-xs font-bold text-slate-700">{{ date('l, d F Y') }}</p>
                <p class="text-[10px] text-slate-400">PT. Mulia Anugerah Distribusindo</p>
            </div>

            <button class="relative p-2 text-slate-400 hover:text-indigo-600 transition-colors">
                <i class="far fa-bell text-lg"></i>
                <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
            </button>

        </div>
    </div>
</nav>