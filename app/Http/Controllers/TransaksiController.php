<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function indexPenjualan() {
        return view('transaksi.penjualan.index');
    }

    public function indexRetur() {
        return view('transaksi.retur.index');
    }

    public function indexAR() {
        return view('transaksi.ar.index');
    }

    public function indexCollection() {
        return view('transaksi.collection.index');
    }
}