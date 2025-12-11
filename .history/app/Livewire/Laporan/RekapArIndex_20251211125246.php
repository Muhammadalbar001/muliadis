// ...
    public function export()
    {
        $query = AccountReceivable::query();
        $this->applyFilters($query);

        $writer = SimpleExcelWriter::streamDownload('Rekap_AR.xlsx');
        $query->chunk(1000, function ($items) use ($writer) {
            foreach ($items as $item) {
                $writer->addRow([
                    'Cabang'       => $item->cabang,
                    'No Invoice'   => $item->no_penjualan,
                    'Pelanggan'    => $item->pelanggan_name,
                    'Jatuh Tempo'  => $item->jatuh_tempo,
                    'Umur (Hari)'  => $item->umur_piutang,
                    'Sisa Piutang' => $item->nilai,
                ]);
            }
        });
        return $writer->toBrowser();
    }
    
    public function render() {
        // ... Logic Query ...
        $ars = $query->orderBy('umur_piutang', 'desc')->paginate(20);
        // ... Return View dengan compact('ars') ...
    }
// ...