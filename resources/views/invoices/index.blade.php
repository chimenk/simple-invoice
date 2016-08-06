@extends('layouts.app') @section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="clearfix">
            <span class="panel-title">Invoices</span>
            <a href="{{ route('invoices.create') }}" class="btn btn-success pull-right">Create</a>
        </div>
    </div>

    <div class="panel-body">
        @if ($invoices->count())
        <table class="table table-stripped">
            <thead>
                <th>Invoice No.</th>
                <th>Grand Total</th>
                <th>Client</th>
                <th>Invoice Date</th>
                <th>Due Date</th>
                <th colspan="2">Created At</th>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->invoice_no }}</td>
                    <td>{{ $invoice->grand_total }}</td>
                    <td>{{ $invoice->client }}</td>
                    <td>{{ $invoice->invoice_date }}</td>
                    <td>{{ $invoice->due_date }}</td>
                    <td>{{ $invoice->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="invoice-empty">
            <p class="invoice-empty-title">
                No invoices were created.

            </p>
        </div>
        @endif
    </div>
</div>
@endsection