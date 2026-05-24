@extends('layouts.medical-store')

@section('title', 'Low Stock Alert')
@section('header', 'Low Stock Alert')
@section('subheader', 'Medicines that need reordering')

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('medical-store.stock.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Stock
        </a>
        <a href="{{ route('medical-store.stock.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Stock
        </a>
    </div>
    <div class="card-body">
        @if(isset($lowstock) && $lowstock->count() > 0)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> 
                <strong>{{ $lowstock->count() }} items</strong> are running low and need reordering.
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Medicine Name</th>
                            <th>Category</th>
                            <th>Batch #</th>
                            <th>Current Stock</th>
                            <th>Min. Required</th>
                            <th>Status</th>
                            <th>Expiry Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowstock as $item)
                        @php
                            $status = $item->quantity == 0 ? 'Out of Stock' : ($item->quantity < 5 ? 'Critical' : 'Low');
                            $badgeClass = $item->quantity == 0 ? 'danger' : ($item->quantity < 5 ? 'danger' : 'warning');
                        @endphp
                        <tr>
                            <td>{{ $item->stock_id }}</td>
                            <td>
                                {{ $item->medicine->medicine_name ?? 'N/A' }}
                                <br><small class="text-muted">{{ $item->medicine->manufacturer ?? '' }}</small>
                             </div>
                            <td>{{ $item->medicine->category ?? 'N/A' }}</div>
                            <td>{{ $item->batch_number }}</div>
                            <td>
                                <span class="fw-bold text-danger">{{ $item->quantity }} pcs</span>
                             </div>
                            <td>50 pcs</div>
                            <td>
                                <span class="badge bg-{{ $badgeClass }}">
                                    {{ $status }}
                                </span>
                             </div>
                            <td>
                                @if(\Carbon\Carbon::parse($item->expiry_date)->isPast())
                                    <span class="badge bg-danger">EXPIRED</span>
                                @else
                                    {{ \Carbon\Carbon::parse($item->expiry_date)->format('M d, Y') }}
                                @endif
                             </div>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#reorderModal{{ $item->stock_id }}">
                                    <i class="fas fa-shopping-cart"></i> Reorder
                                </button>
                             </div>
                        </tr>
                        
                        <!-- Reorder Modal -->
                        <div class="modal fade" id="reorderModal{{ $item->stock_id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('medical-store.stock.add-stock', $item->stock_id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Reorder Stock</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Medicine:</strong> {{ $item->medicine->medicine_name ?? 'N/A' }}</p>
                                            <p><strong>Current Stock:</strong> <span class="text-danger">{{ $item->quantity }} pcs</span></p>
                                            <p><strong>Recommended Order:</strong> 100 pcs</p>
                                            <div class="mb-3">
                                                <label>Quantity to Add *</label>
                                                <input type="number" name="additional_quantity" class="form-control" min="1" value="100" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Place Order</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h5>No Low Stock Items</h5>
                <p class="text-muted">All medicines have adequate stock levels.</p>
                <a href="{{ route('medical-store.stock.index') }}" class="btn btn-primary">Back to Stock</a>
            </div>
        @endif
    </div>
</div>
@endsection