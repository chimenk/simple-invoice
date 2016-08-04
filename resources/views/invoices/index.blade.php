@extends('layouts.app')

@section('content')
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

        </table>
      @else
        <div class="invoice-empty">
          <p class="invoice-empty-title">
            No invoices were cerated.

          </p>
        </div>
      @endif
    </div>
  </div>
@endsection
