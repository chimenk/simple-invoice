<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
ues App\Invoice;

class InvoicesController extends Controller
{
    public function index()
    {
      $invoices = Invoice::orderBy('created_at', desc)
                          ->paginate(8);

      return view('invoices.index', compact('invoices'));
    }
}
