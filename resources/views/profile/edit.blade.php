<x-app-layout>
    <div class="space-y-6 font-sans">

        <div
            class="sticky top-0 z-40 backdrop-blur-md bg-white/90 p-5 rounded-xl shadow-sm border border-slate-200 mb-6">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <div
                        class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-900 text-white shadow-md">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-slate-800 tracking-tight">Pengaturan Akun</h1>
                        <p class="text-xs text-slate-500 font-medium">Kelola profil dan keamanan data Anda.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 pb-10">

            <div class="space-y-6">

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                        <i class="fas fa-id-card text-blue-900"></i>
                        <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Informasi Profil</h2>
                    </div>

                    <div class="p-6">
                        <p class="text-sm text-slate-500 mb-6">Perbarui nama lengkap dan alamat email akun Anda.</p>
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

            </div>

            <div class="space-y-6">

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                        <i class="fas fa-shield-alt text-blue-900"></i>
                        <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Ganti Password</h2>
                    </div>

                    <div class="p-6">
                        <p class="text-sm text-slate-500 mb-6">Pastikan akun Anda menggunakan password yang panjang dan
                            acak agar tetap aman.</p>
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-red-200 overflow-hidden relative group">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                        <i class="fas fa-exclamation-triangle text-6xl text-red-600"></i>
                    </div>

                    <div class="bg-red-50/50 px-6 py-4 border-b border-red-100 flex items-center gap-3">
                        <i class="fas fa-trash-alt text-red-600"></i>
                        <h2 class="text-sm font-bold text-red-700 uppercase tracking-wide">Hapus Akun</h2>
                    </div>

                    <div class="p-6 relative z-10">
                        <p class="text-sm text-slate-500 mb-6">
                            Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen.
                        </p>
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>