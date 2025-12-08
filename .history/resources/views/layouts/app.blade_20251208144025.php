<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

<body>
    <script>
        document.addEventListener('livewire:init', () => {
            
            // 1. Toast Notifikasi (Sukses/Gagal)
            Livewire.on('show-toast', (data) => {
                // Handle jika data dikirim sebagai array atau object
                const event = Array.isArray(data) ? data[0] : data;
                
                Swal.fire({
                    toast: true,
                    position: 'top-end', // Muncul di pojok kanan atas
                    icon: event.type,    // success, error, warning
                    title: event.title,
                    text: event.message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: '#fff',
                    iconColor: event.type === 'success' ? '#10b981' : '#ef4444',
                    customClass: {
                        popup: 'shadow-xl rounded-xl border border-gray-100',
                        title: 'text-sm font-bold text-gray-800',
                        htmlContainer: 'text-xs text-gray-500'
                    }
                });
            });

            // 2. Konfirmasi Hapus (SweetAlert)
            Livewire.on('confirm-delete', (event) => {
                // Kita akan panggil ini nanti di tombol hapus
                Swal.fire({
                    title: 'Yakin hapus data ini?',
                    text: "Data yang dihapus tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5', // Warna Indigo
                    cancelButtonColor: '#ef4444',  // Warna Merah
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-lg px-4 py-2',
                        cancelButton: 'rounded-lg px-4 py-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Panggil metode delete di komponen Livewire
                        // Pastikan event.id dikirim dari controller
                        Livewire.dispatch(event.method, { id: event.id }); 
                    }
                });
            });
        });
    </script>
</body>