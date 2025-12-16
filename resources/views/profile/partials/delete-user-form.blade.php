<section class="space-y-6">
    <header>
        <p class="mt-1 text-sm text-red-600">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. Harap unduh data penting sebelum melanjutkan.') }}
        </p>
    </header>

    <x-danger-button class="rounded-xl px-5 py-2.5 bg-red-600 hover:bg-red-700 shadow-lg shadow-red-200" x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">{{ __('Hapus Akun Saya') }}
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-slate-900">
                {{ __('Apakah Anda yakin ingin menghapus akun?') }}
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                {{ __('Aksi ini tidak dapat dibatalkan. Masukkan password Anda untuk konfirmasi.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                <x-text-input id="password" name="password" type="password"
                    class="mt-1 block w-3/4 rounded-xl border-slate-200" placeholder="{{ __('Password') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')" class="rounded-xl mr-3">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-danger-button class="ml-3 rounded-xl bg-red-600 hover:bg-red-700">
                    {{ __('Ya, Hapus Akun') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>