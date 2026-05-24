@extends('layouts.patient')

@section('title', 'Bill Details')
@section('header', 'Bill #' . $bill->bill_id)
@section('subheader', 'Appointment on ' . ($bill->appointment->appointment_date ?? 'N/A'))

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Bill Items</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr><th>Description</th><th>Quantity</th><th>Unit Price</th><th>Amount</th></tr>
                    </thead>
                    <tbody>
                        @foreach($bill->items as $item)
                        <tr>
                            <td>{{ $item->item_description }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₱{{ number_format($item->unit_price, 2) }}</td>
                            <td>₱{{ number_format($item->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr><td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                            <td>₱{{ number_format($bill->total_amount, 2) }}</td>
                        </tr>
                        <tr><td colspan="3" class="text-end"><strong>Discount:</strong></td>
                            <td>- ₱{{ number_format($bill->discount, 2) }}</td>
                        </tr>
                        <tr><td colspan="3" class="text-end"><strong>Tax:</strong></td>
                            <td>₱{{ number_format($bill->tax, 2) }}</td>
                        </tr>
                        <tr class="table-active"><td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td><strong>₱{{ number_format($bill->net_amount, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Payment Status</div>
                <div class="card-body">
                    <p><strong>Status:</strong> 
                        @if($bill->payment_status == 'paid')
                            <span class="badge bg-success">Paid</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </p>
                    <p><strong>Bill Date:</strong> {{ $bill->bill_date }}</p>
                    <a href="{{ route('patient.billing.index') }}" class="btn btn-secondary w-100">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection