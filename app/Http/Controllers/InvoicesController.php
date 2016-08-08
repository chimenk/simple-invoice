<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Invoice;
use App\InvoiceProduct;

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

    public function store(Request $req)
    {
      $this->validate($req, [
        'invoice_no' => 'required|alpha_dash|unique:invoices',
        'client' => 'required|max:255',
        'client_address' => 'required|max:255',
        'invoice_date' => 'required|date_format:Y-m-d',
        'due_date' => 'required|date_format:Y-m-d',
        'title' => 'required|max:255',
        'discount' => 'required|numeric|min:0',
        'products.*.name' => 'required|max:255',
        'products.*.price' => 'required|min:1|numeric',
        'products.*.qty' => 'required|integer|min:1'
      ]);

      $products = collect($req->products)->transform(function($product) {
        $product['total'] = $product['qty'] * $product['price'];
        return new InvoiceProduct($product);
      });

      if($products->isEmpty())
      {
        return response()->json([
          'products_empty' => ['One or more products is required']
        ], 422);
      }

      $data = $req->except('products');
      $data['sub_total'] = $products->sum('total');
      $data['grand_total'] = $data['sub_total'] - $data['discount'];

      $invoice = Invoice::create($data);

      $invoice->products()->saveMany($products);

      return response()->json([
        'created' => true,
        'id' => $invoice->id
      ]);
    }

    public function edit($id)
    {
      $invoice = Invoice::with('products')->findOrFail($id);

      return view('invoices.edit', compact('invoice'));
    }

    public function show($id)
    {
      $invoice = Invoice::with('products')->findOrFail($id);

      return view('invoices.show', compact('invoice'));
    }

    public function update(Request $req, $id)
    {
      $this->validate($req, [
        'invoice_no' => 'required|alpha_dash|unique:invoices,invoice_no,'.$id.',id',
        'client' => 'required|max:255',
        'client_address' => 'required|max:255',
        'invoice_date' => 'required|date_format:Y-m-d',
        'due_date' => 'required|date_format:Y-m-d',
        'title' => 'required|max:255',
        'discount' => 'required|numeric|min:0',
        'products.*.name' => 'required|max:255',
        'products.*.price' => 'required|min:1|numeric',
        'products.*.qty' => 'required|integer|min:1'
      ]);

      $invoice = Invoice::findOrFail($id);

      $products = collect($req->products)->transform(function($product) {
        $product['total'] = $product['qty'] * $product['price'];
        return new InvoiceProduct($product);
      });

      if($products->isEmpty())
      {
        return response()->json([
          'products_empty' => ['One or more products is required']
        ], 422);
      }

      $data = $req->except('products');
      $data['sub_total'] = $products->sum('total');
      $data['grand_total'] = $data['sub_total'] - $data['discount'];

      $invoice->update($data);

      InvoiceProduct::where('invoice_id', $invoice->id)->delete();

      $invoice->products()->saveMany($products);

      return response()->json([
        'updated' => true,
        'id' => $invoice->id
      ]);
    }

    public function destroy($id)
    {
      $invoice = Invoice::findOrFail($id);

      InvoiceProduct::where('invoice_id', $invoice->id)->delete();

      $invoice->delete();

      return redirect()->route('invoices.index'); 

    }
}
