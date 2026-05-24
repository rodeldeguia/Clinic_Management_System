@extends('layouts.medical-store')

@section('title', 'Add Stock')
@section('header', 'Add New Medicine Stock')
@section('subheader', 'Register new medicine batch')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('medical-store.stock.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Medicine *</label>
                    <select name="medicine_id" class="form-control" required>
                        <option value="">Select Medicine</option>
                        @foreach($medicines as $medicine)
                            <option value="{{ $medicine->medicine_id }}" {{ old('medicine_id') == $medicine->medicine_id ? 'selected' : '' }}>
                                {{ $medicine->medicine_name }} - {{ $medicine->category }}
                            </option>
                        @endforeach
                    </select>
                    @error('medicine_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>Batch Number *</label>
                    <input type="text" name="batch_number" class="form-control" value="{{ old('batch_number') }}" required>
                    @error('batch_number') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Quantity *</label>
                    <input type="number" name="quantity" class="form-control" min="1" value="{{ old('quantity') }}" required>
                    @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>Unit Price *</label>
                    <input type="number" step="0.01" name="unit_price" class="form-control" min="0" value="{{ old('unit_price') }}" required>
                    @error('unit_price') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Manufacturing Date *</label>
                    <input type="date" name="manufacturing_date" class="form-control" value="{{ old('manufacturing_date') }}" required>
                    @error('manufacturing_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>Expiry Date *</label>
                    <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}" required>
                    @error('expiry_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Storage Location</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="e.g., Shelf A1">
                    @error('location') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Make sure the expiry date is at least 30 days from today.
            </div>
            <button type="submit" class="btn btn-primary">Save Stock</button>
            <a href="{{ route('medical-store.stock.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection