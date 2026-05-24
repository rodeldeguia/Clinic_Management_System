@extends('layouts.medical-store')

@section('title', 'Edit Stock')
@section('header', 'Edit Medicine Stock')
@section('subheader', 'Update stock information')

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('medical-store.stock.update', $stock->stock_id) }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Medicine</label>
                    <input type="text" class="form-control" value="{{ $stock->medicine->medicine_name ?? 'N/A' }}" disabled>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Batch Number</label>
                    <input type="text" class="form-control" value="{{ $stock->batch_number }}" disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Quantity *</label>
                    <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $stock->quantity) }}" min="0" required>
                    @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>Unit Price *</label>
                    <input type="number" step="0.01" name="unit_price" class="form-control" value="{{ old('unit_price', $stock->unit_price) }}" min="0" required>
                    @error('unit_price') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Expiry Date *</label>
                    <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date', $stock->expiry_date) }}" required>
                    @error('expiry_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label>Storage Location</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $stock->location) }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Stock</button>
            <a href="{{ route('medical-store.stock.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection