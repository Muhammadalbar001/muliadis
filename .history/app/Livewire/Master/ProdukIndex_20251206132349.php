// ... Bagian fungsi import() di ProdukIndex.php ...

public function import()
{
$this->validate([
'file' => 'required|file|mimes:xlsx,csv,xls|max:102400',
]);

try {
$filename = $this->file->store('temp-imports', 'local');
$fullPath = Storage::disk('local')->path($filename);

if (!file_exists($fullPath)) throw new \Exception("File gagal disimpan.");

$importService = new ProdukImportService();
$stats = $importService->handle($fullPath);

if (Storage::disk('local')->exists($filename)) {
Storage::disk('local')->delete($filename);
}

$this->closeImportModal();

// --- NOTIFIKASI DETAIL UNTUK USER ---

// Hitung Data Bersih (Tanpa Duplikat)
$uniqueCount = $stats['processed'] - $stats['duplicates'];

$message = "PROSES SELESAI!\n" .
"📊 Total Baris Excel: " . number_format($stats['total_rows']) . "\n" .
"✅ Data Masuk (Unik): " . number_format($uniqueCount) . "\n" .
"♻️ Data Duplikat (Digabung): " . number_format($stats['duplicates']) . "\n";

if ($stats['skipped_empty'] > 0) {
$message .= "⚠️ Skipped (SKU Kosong): " . number_format($stats['skipped_empty']);
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