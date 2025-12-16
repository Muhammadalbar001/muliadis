<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterController extends Controller
{
    public function indexProduk() {
        return view('master.produk.index');
    }

    public function indexSupplier() {
        return view('master.supplier.index');
    }

    public function indexSales() {
        return view('master.sales.index');
    }

    public function indexUser() {
        return view('master.user.index');
    }
}