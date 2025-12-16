<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function rekapPenjualan() {
        return view('laporan.penjualan.index');
    }

    public function rekapRetur() {
        return view('laporan.retur.index');
    }

    public function rekapAR() {
        return view('laporan.ar.index');
    }

    public function rekapCollection() {
        return view('laporan.collection.index');
    }
}