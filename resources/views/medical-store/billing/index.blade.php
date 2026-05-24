@extends('layouts.medical-store')

@section('title', 'Pharmacy Billing')
@section('header', 'Pharmacy Billing')
@section('subheader', 'Generate bills for dispensed medicines')

@section('content')
<div class="row">
    <!-- Pending Prescriptions for Billing -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-prescription-bottle"></i> Pending Prescriptions for Billing
            </div>
            <div class="card-body">
                @if($pendingPrescriptions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>Prescription #</th>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Medicine</th>
                                    <th>Dosage</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingPrescriptions as $prescription)
                                @php
                                    $unitPrice = $prescription->medicine->stockEntries->first()->unit_price ?? 50;
                                    $total = $unitPrice * $prescription->quantity_dispensed;
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="prescription-checkbox" 
                                               value="{{ $prescription->prescription_id }}"
                                               data-price="{{ $unitPrice }}"
                                               data-qty="{{ $prescription->quantity_dispensed }}"
                                               data-name="{{ $prescription->medicine->medicine_name ?? 'N/A' }}">
                                    </div>
                                    <td>#{{ $prescription->prescription_id }}</div>
                                    <td>{{ \Carbon\Carbon::parse($prescription->created_at)->format('M d, Y') }}</div>
                                    <td>
                                        {{ $prescription->medicalRecord->appointment->patient->firstname ?? 'N/A' }}
                                        {{ $prescription->medicalRecord->appointment->patient->lastname ?? '' }}
                                        <br><small class="text-muted">ID: {{ $prescription->medicalRecord->appointment->patient->user_id ?? 'N/A' }}</small>
                                     </div>
                                    <td>Dr. {{ $prescription->medicalRecord->doctor->firstname ?? 'N/A' }} {{ $prescription->medicalRecord->doctor->lastname ?? '' }}</div>
                                    <td>{{ $prescription->medicine->medicine_name ?? 'N/A' }}</div>
                                    <td>{{ $prescription->dosage }}</div>
                                    <td class="text-center">{{ $prescription->quantity_dispensed }} pcs</div>
                                    <td class="text-end">₱{{ number_format($unitPrice, 2) }}</div>
                                    <td class="text-end">₱{{ number_format($total, 2) }}</div>
                                    <td>
                                        @if($prescription->status == 'dispensed')
                                            <span class="badge bg-success">Dispensed</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                     </div>
                                </tr>
                                @endforeach
                            </tbody>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="button" class="btn btn-success" id="generateBillBtn" disabled>
                            <i class="fas fa-receipt"></i> Generate Bill for Selected
                        </button>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p>No pending prescriptions for billing.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Bills -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <i class="fas fa-history"></i> Recent Bills
            </div>
            <div class="card-body">
                @if($bills->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Bill ID</th>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Items</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bills as $bill)
                                <tr>
                                    <td>#{{ $bill->bill_id }}</div>
                                    <td>{{ $bill->bill_date }}</div>
                                    <td>{{ $bill->patient->firstname ?? 'N/A' }} {{ $bill->patient->lastname ?? '' }}</div>
                                    <td>{{ $bill->items->count() }} item(s)</div>
                                    <td>₱{{ number_format($bill->net_amount, 2) }}</div>
                                    <td>
                                        @if($bill->payment_status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                     </div>
                                    <td>
                                        <a href="{{ route('medical-store.billing.show', $bill->bill_id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @if($bill->payment_status != 'paid')
                                            <a href="{{ route('medical-store.billing.mark-paid', $bill->bill_id) }}" class="btn btn-sm btn-success" onclick="return confirm('Mark as paid?')">
                                                <i class="fas fa-check"></i> Mark Paid
                                            </a>
                                        @endif
                                     </div>
                                </tr>
                                @endforeach
                            </tbody>
                        </div>
                    </div>
                    {{ $bills->links() }}
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <p>No bills generated yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Generate Bill Modal -->
<div class="modal fade" id="generateBillModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Generate Bill</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="billForm" action="{{ route('medical-store.billing.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Patient Name</label>
                                <input type="text" id="patientName" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Bill Date</label>
                                <input type="text" value="{{ date('Y-m-d H:i:s') }}" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="billItemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Medicine</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="billItemsBody">
                                <!-- Items will be added here dynamically -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td><strong id="subtotal">₱0.00</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Discount (10%):</strong></td>
                                    <td><input type="number" name="discount" id="discount" class="form-control form-control-sm" style="width: 120px;" value="0" step="0.01"></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tax (12%):</strong></td>
                                    <td><strong id="tax">₱0.00</strong></td>
                                </tr>
                                <tr class="table-active">
                                    <td colspan="3" class="text-end"><strong>Net Amount:</strong></td>
                                    <td><strong id="netAmount">₱0.00</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <input type="hidden" name="prescription_ids" id="prescriptionIds">
                    <input type="hidden" name="net_amount" id="netAmountInput">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Generate Bill</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let selectedPrescriptions = [];
    
    // Select All functionality
    $('#selectAll').change(function() {
        $('.prescription-checkbox').prop('checked', $(this).prop('checked'));
        updateSelectedPrescriptions();
    });
    
    $('.prescription-checkbox').change(function() {
        updateSelectedPrescriptions();
    });
    
    function updateSelectedPrescriptions() {
        selectedPrescriptions = [];
        $('.prescription-checkbox:checked').each(function() {
            selectedPrescriptions.push({
                id: $(this).val(),
                name: $(this).data('name'),
                price: $(this).data('price'),
                qty: $(this).data('qty')
            });
        });
        
        $('#generateBillBtn').prop('disabled', selectedPrescriptions.length === 0);
    }
    
    $('#generateBillBtn').click(function() {
        if(selectedPrescriptions.length === 0) {
            alert('Please select at least one prescription.');
            return;
        }
        
        // Get patient name (assuming all selected are for same patient)
        var patientName = $('.prescription-checkbox:checked').first().closest('tr').find('td:eq(3)').text();
        $('#patientName').val(patientName.trim());
        
        // Build bill items table
        var itemsHtml = '';
        var subtotal = 0;
        
        selectedPrescriptions.forEach(function(item) {
            var total = item.price * item.qty;
            subtotal += total;
            itemsHtml += `
                <tr>
                    <td>${item.name}</td>
                    <td class="text-center">${item.qty} pcs</td>
                    <td class="text-end">₱${item.price.toFixed(2)}</td>
                    <td class="text-end">₱${total.toFixed(2)}</td>
                </tr>
            `;
        });
        
        $('#billItemsBody').html(itemsHtml);
        $('#subtotal').text('₱' + subtotal.toFixed(2));
        
        // Calculate tax and net
        calculateTotals();
        
        // Set prescription IDs
        var ids = selectedPrescriptions.map(p => p.id).join(',');
        $('#prescriptionIds').val(ids);
        
        $('#generateBillModal').modal('show');
    });
    
    $('#discount').on('input', function() {
        calculateTotals();
    });
    
    function calculateTotals() {
        var subtotalText = $('#subtotal').text();
        var subtotal = parseFloat(subtotalText.replace('₱', ''));
        var discount = parseFloat($('#discount').val()) || 0;
        var tax = (subtotal - discount) * 0.12;
        var netAmount = (subtotal - discount) + tax;
        
        $('#tax').text('₱' + tax.toFixed(2));
        $('#netAmount').text('₱' + netAmount.toFixed(2));
        $('#netAmountInput').val(netAmount.toFixed(2));
    }
</script>
@endpush
@endsection