<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /* Supplier Home Page */
    public function HomePage()
    {
        return view('page/supplier_home');
    }

    public function New()
    {
        return view('page/supplier_new');
    }
}
