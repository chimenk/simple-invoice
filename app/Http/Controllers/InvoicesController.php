<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Invoice;

class InvoicesController extends Controller
{
    public function index()
    {
      $invoices = Invoice::orderBy('created_at', 'desc')
                          ->paginate(8);

      return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
      return view('invoices.create');
    }
}
