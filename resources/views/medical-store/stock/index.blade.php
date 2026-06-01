@extends('layouts.medical-store')

@section('title', 'Stock Management')
@section('header', 'Medicine Stock Management')
@section('subheader', 'Manage inventory and stock levels')

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('medical-store.stock.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Stock
        </a>
        <a href="{{ route('medical-store.stock.low-stock') }}" class="btn btn-warning">
            <i class="fas fa-exclamation-triangle"></i> Low Stock Alerts
        </a>
        <a href="{{ route('medical-store.stock.expired') }}" class="btn btn-danger">
            <i class="fas fa-calendar-times"></i> Expired Stock
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Medicine Name</th>
                        <th>Category</th>
                        <th>Batch #</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Expiry Date</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stock as $item)
                    <tr>
                        <td>{{ $item->stock_id }}</td>
                        <td>{{ $item->medicine->medicine_name ?? 'N/A' }}</td>
                        <td>{{ $item->medicine->category ?? 'N/A' }}</td>
                        <td>{{ $item->batch_number }}</td>
                        <td class="{{ $item->quantity < 10 ? 'text-danger fw-bold' : '' }}">
                            {{ $item->quantity }} pcs
                        </td>
                        <td>₱{{ number_format($item->unit_price, 2) }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($item->expiry_date)->format('M d, Y') }}
                            @if(\Carbon\Carbon::parse($item->expiry_date)->isPast())
                                <span class="badge bg-danger">EXPIRED</span>
                            @elseif(\Carbon\Carbon::parse($item->expiry_date)->diffInDays(now()) <= 30)
                                <span class="badge bg-warning">Expiring soon</span>
                            @endif
                         </div>
                        <td>{{ $item->location ?? 'N/A' }}</td>
                        <td>
                            @if($item->quantity < 10)
                                <span class="badge bg-warning">Low Stock</span>
                            @elseif(\Carbon\Carbon::parse($item->expiry_date)->isPast())
                                <span class="badge bg-danger">Expired</span>
                            @else
                                <span class="badge bg-success">Good</span>
                            @endif
                         
                        <td>
                            <a href="{{ route('medical-store.stock.edit', $item->stock_id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addStockModal{{ $item->stock_id }}">
                                <i class="fas fa-plus-circle"></i> Add
                            </button>
                            @if(\Carbon\Carbon::parse($item->expiry_date)->isPast())
                                <form action="{{ route('medical-store.stock.remove-expired', $item->stock_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove expired stock?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                         </div>
                    </tr>
                    @empty
                        <tr><td colspan="10" class="text-center py-4">
                            <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                            <p>No stock found</p>
                            <a href="{{ route('medical-store.stock.create') }}" class="btn btn-primary">Add First Stock</a>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
        
        <div class="d-flex justify-content-center mt-3">
            {{ $stock->links() }}
        </div>
</div>



@foreach($stock as $item)
<!-- Add Stock Modal -->
<div class="modal fade" id="addStockModal{{ $item->stock_id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('medical-store.stock.add-stock', $item->stock_id) }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add Stock for {{ $item->medicine->medicine_name ?? 'N/A' }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Current Quantity:</strong> {{ $item->quantity }} pcs</p>
                    <div class="mb-3">
                        <label>Additional Quantity *</label>
                        <input type="number" name="additional_quantity" class="form-control" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection