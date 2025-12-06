// ... code sebelumnya ...

public function import()
{
$this->validate([
'file' => 'required|file|mimes:xlsx,csv,xls|max:102400',
]);

try {
$filename = $this->file->store('temp-imports', 'local');
$fullPath = Storage::disk('local')->path($filename);

if (!file_exists($fullPath)) {
throw new \Exception("File gagal disimpan di server.");
}

// PROSES IMPORT
$importService = new ProdukImportService();
$stats = $importService->handle($fullPath);

// HAPUS FILE TEMP
if (Storage::disk('local')->exists($filename)) {
Storage::disk('local')->delete($filename);
}

$this->closeImportModal();

// BUAT PESAN NOTIFIKASI LENGKAP
$message = "Selesai! Diproses: " . number_format($stats['processed']) .
" baris. (Total Excel: " . number_format($stats['total_rows']) . ")";

if ($stats['skipped_empty'] > 0) {
$message .= " | Skipped (Empty SKU): " . number_format($stats['skipped_empty']);
}
if ($stats['skipped_error'] > 0) {
$message .= " | Error: " . number_format($stats['skipped_error']);
}

session()->flash('success', $message);

} catch (\Exception $e) {
if (isset($filename) && Storage::disk('local')->exists($filename)) {
Storage::disk('local')->delete($filename);
}
Log::error('Import Gagal: ' . $e->getMessage());
$this->addError('file', 'GAGAL: ' . $e->getMessage());
}
}

// ... code sesudahnya ...