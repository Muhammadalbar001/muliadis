<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Pengaturan Akun') }}
        </h2>
    </x-slot>

    <div class="py-12 font-jakarta">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-2xl border border-indigo-100">
                <div class="max-w-xl">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-circle text-indigo-500"></i> Informasi Profil
                    </h3>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-2xl border border-indigo-100">
                <div class="max-w-xl">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-key text-indigo-500"></i> Ganti Password
                    </h3>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-red-50 shadow-sm sm:rounded-2xl border border-red-100">
                <div class="max-w-xl">
                    <h3 class="text-lg font-bold text-red-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle text-red-500"></i> Hapus Akun
                    </h3>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>