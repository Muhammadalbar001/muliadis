<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2">
            <i class="fas fa-user-cog text-indigo-500"></i> {{ __('Pengaturan Akun') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-2xl border border-indigo-50 h-full">
                    <div class="max-w-xl">
                        <h3 class="text-lg font-bold text-slate-800 mb-1 flex items-center gap-2">
                            <span
                                class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm"><i
                                    class="fas fa-id-card"></i></span>
                            Informasi Profil
                        </h3>
                        <p class="text-sm text-slate-500 mb-6 ml-10">Ubah nama akun dan alamat email Anda.</p>
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-2xl border border-indigo-50">
                    <div class="max-w-xl">
                        <h3 class="text-lg font-bold text-slate-800 mb-1 flex items-center gap-2">
                            <span
                                class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-sm"><i
                                    class="fas fa-key"></i></span>
                            Ganti Password
                        </h3>
                        <p class="text-sm text-slate-500 mb-6 ml-10">Pastikan menggunakan password yang panjang.</p>
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-red-50/50 shadow-sm sm:rounded-2xl border border-red-100">
                    <div class="max-w-xl">
                        <h3 class="text-lg font-bold text-red-700 mb-1 flex items-center gap-2">
                            <span
                                class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center text-sm"><i
                                    class="fas fa-triangle-exclamation"></i></span>
                            Zona Bahaya
                        </h3>
                        <div class="ml-10 mt-4">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>