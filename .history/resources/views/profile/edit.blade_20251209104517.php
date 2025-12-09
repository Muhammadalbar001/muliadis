<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Pengaturan Akun') }}
        </h2>
    </x-slot>

    <div class="py-12 font-jakarta">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-2xl border border-indigo-100 h-fit">
                    <div class="max-w-xl">
                        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <span
                                class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm"><i
                                    class="fas fa-user-circle"></i></span>
                            Informasi Profil
                        </h3>
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-2xl border border-indigo-100 h-fit">
                    <div class="max-w-xl">
                        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <span
                                class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-sm"><i
                                    class="fas fa-key"></i></span>
                            Ganti Password
                        </h3>
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-red-50 shadow-sm sm:rounded-2xl border border-red-100 lg:col-span-2">
                    <div class="max-w-xl">
                        <h3 class="text-lg font-bold text-red-800 mb-4 flex items-center gap-2">
                            <span
                                class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center text-sm"><i
                                    class="fas fa-exclamation-triangle"></i></span>
                            Zona Bahaya
                        </h3>
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>