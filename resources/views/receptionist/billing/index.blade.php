@extends('layouts.receptionist')

@section('title', 'Billing')
@section('header', 'Billing Management')
@section('subheader', 'View and manage patient bills')

@section('content')
<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr><th>Bill ID</th><th>Patient</th><th>Date</th><th>Total</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($bills as $bill)
                <tr>
                    <td>{{ $bill->bill_id }}</td>
                    <td>{{ $bill->patient->firstname ?? 'N/A' }} {{ $bill->patient->lastname ?? '' }}</td>
                    <td>{{ $bill->bill_date }}</td>
                    <td>₱{{ number_format($bill->net_amount, 2) }}</td>
                    <td>
                        <span class="badge bg-{{ $bill->payment_status == 'paid' ? 'success' : 'warning' }}">
                            {{ ucfirst($bill->payment_status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('receptionist.billing.show', $bill->bill_id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('receptionist.billing.mark-paid', $bill->bill_id) }}" class="btn btn-sm btn-success" onclick="return confirm('Mark as paid?')">Mark Paid</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">No bills found</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $bills->links() }}
    </div>
</div>
@endsection