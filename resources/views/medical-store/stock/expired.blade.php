@extends('layouts.medical-store')

@section('title', 'Expired Stock')
@section('header', 'Expired Stock Management')
@section('subheader', 'View and manage expired medicines')

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('medical-store.stock.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Stock
        </a>
        <button type="button" class="btn btn-danger" id="removeAllExpired" onclick="confirmRemoveAll()">
            <i class="fas fa-trash-alt"></i> Remove All Expired
        </button>
    </div>
    <div class="card-body">
        @if(isset($expiredStock) && $expiredStock->count() > 0)
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> 
                <strong>{{ $expiredStock->count() }} items</strong> have expired and need to be removed.
            </div>
            <!-- rest of the table using $expiredStock -->
        @else
            <!-- empty state -->
       
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>ID</th>
                            <th>Medicine Name</th>
                            <th>Category</th>
                            <th>Batch #</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Manufacturing Date</th>
                            <th>Expiry Date</th>
                            <th>Days Expired</th>
                            <th>Location</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expiredStock as $item)
                        @php
                            $expiryDate = \Carbon\Carbon::parse($item->expiry_date);
                            $daysExpired = $expiryDate->diffInDays(now());
                        @endphp
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="stock-checkbox" value="{{ $item->stock_id }}">
                            </td>
                            <td>{{ $item->stock_id }}</td>
                            <td>
                                {{ $item->medicine->medicine_name ?? 'N/A' }}
                                <br><small class="text-muted">{{ $item->medicine->manufacturer ?? '' }}</small>
                             </div>
                            <td>{{ $item->medicine->category ?? 'N/A' }}</div>
                            <td>{{ $item->batch_number }}</div>
                            <td class="text-danger fw-bold">{{ $item->quantity }} pcs</div>
                            <td>₱{{ number_format($item->unit_price, 2) }}</div>
                            <td>{{ \Carbon\Carbon::parse($item->manufacturing_date)->format('M d, Y') }}</div>
                            <td>
                                <span class="badge bg-danger">
                                    {{ \Carbon\Carbon::parse($item->expiry_date)->format('M d, Y') }}
                                </span>
                                <br>
                                <small class="text-danger">Expired {{ $daysExpired }} days ago</small>
                             </div>
                            <td>{{ $item->location ?? 'N/A' }}</div>
                            <td>
                                <form action="{{ route('medical-store.stock.remove-expired', $item->stock_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove this expired stock?')">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </form>
                             </div>
                        </tr>
                        @endforeach
                    </tbody>
                </div>
            </div>
            
            <div class="mt-3">
                <form id="bulkRemoveForm" action="{{ route('medical-store.stock.bulk-remove-expired') }}" method="POST">
                    @csrf
                    <input type="hidden" name="stock_ids" id="selectedStockIds">
                    <button type="submit" class="btn btn-danger" id="bulkRemoveBtn" style="display: none;">
                        <i class="fas fa-trash-alt"></i> Remove Selected
                    </button>
                </form>
            </div>
        
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h5>No Expired Stock</h5>
                <p class="text-muted">All medicines are within their expiry date.</p>
                <a href="{{ route('medical-store.stock.index') }}" class="btn btn-primary">Back to Stock</a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Select all functionality
    $('#selectAll').change(function() {
        $('.stock-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkButton();
    });
    
    $('.stock-checkbox').change(function() {
        updateBulkButton();
    });
    
    function updateBulkButton() {
        var selected = $('.stock-checkbox:checked').length;
        if(selected > 0) {
            $('#bulkRemoveBtn').show();
        } else {
            $('#bulkRemoveBtn').hide();
        }
    }
    
    function confirmRemoveAll() {
        if(confirm('WARNING: This will remove ALL expired stock from inventory. This action cannot be undone. Are you sure?')) {
            var stockIds = [];
            $('.stock-checkbox').each(function() {
                stockIds.push($(this).val());
            });
            $('#selectedStockIds').val(JSON.stringify(stockIds));
            $('#bulkRemoveForm').submit();
        }
    }
    
    // Bulk remove form submission
    $('#bulkRemoveForm').submit(function(e) {
        var selected = [];
        $('.stock-checkbox:checked').each(function() {
            selected.push($(this).val());
        });
        
        if(selected.length === 0) {
            e.preventDefault();
            alert('Please select at least one item to remove.');
            return false;
        }
        
        if(confirm('Remove ' + selected.length + ' expired stock items? This action cannot be undone.')) {
            $('#selectedStockIds').val(JSON.stringify(selected));
            return true;
        }
        e.preventDefault();
        return false;
    });
</script>
@endpush
@endsection