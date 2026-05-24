
@extends('layouts.patient')

@section('title', 'My Bills')
@section('header', 'Billing History')
@section('subheader', 'View and manage your bills')

@section('content')
<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr><th>Bill ID</th><th>Date</th><th>Appointment Date</th><th>Doctor</th><th>Amount</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
                @forelse($bills as $bill)
                <tr>
                    <td>#{{ $bill->bill_id }}</td>
                    <td>{{ $bill->bill_date }}</td>
                    <td>{{ $bill->appointment->appointment_date ?? 'N/A' }}</td>
                    <td>Dr. {{ $bill->appointment->doctor->firstname ?? 'N/A' }} {{ $bill->appointment->doctor->lastname ?? '' }}</td>
                    <td>₱{{ number_format($bill->net_amount, 2) }}</td>
                    <td>
                        @if($bill->payment_status == 'paid')
                            <span class="badge bg-success">Paid</span>
                        @elseif($bill->payment_status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @else
                            <span class="badge bg-danger">{{ $bill->payment_status }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('patient.billing.show', $bill->bill_id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                     </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">No bills found</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $bills->links() }}
    </div>
</div>
@endsection