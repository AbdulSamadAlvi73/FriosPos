@extends('layouts.app')

@section('content')
<div class="content-body default-height p-5 mt-5">
    <div class="container-fluid rounded border p-5 bg-white">
           <div class="row">
                    <div class="col-md-12">
                        <div style="float: right;" >
                            <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
                                Back
                            </a>
                        </div>
                    </div>
                </div>
        <div class="d-flex justify-content-between">
            <div class="container">
                <h1 style="font-size: 28px; font-weight: bold">View Invoice</h1>


                    <div class="row mt-5">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="name">Customer Name</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name', $invoice->name) }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="customer">Customer</label>
                                    <select name="customer_id" id="customer_id" class="form-control" disabled>
                                        <option value="">-- Select Customer --</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->customer_id }}"
                                            {{ $customer->customer_id == $invoice->customer_id ? 'selected' : '' }}
                                            >{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" id="apply_tax" name="apply_sales_tax" value="1"
                            class="form-check-input" {{ $invoice->taxable ? 'checked' : '' }} disabled>
                        <label for="apply_tax" class="form-check-label">
                            Apply Sales Tax ({{ $franchisee }}%)
                        </label>
                    </div>

                    <h4>Invoice Items</h4>
                    <table class="table table-bordered" id="items-table">
                        <thead>
                            <tr>
                                <th>Flavor</th>
                                <th>Allocation Location</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice->items as $index => $item)
                                <tr>
                                    <td>{{ $item->flavor->name ?? 'N/A' }}</td>
                                    <td>{{ $item->inventory_allocation_id }}</td>
                                    <td>
                                        <input type="hidden" name="items[{{ $index }}][flavor_id]" value="{{ $item->flavor_id }}">
                                        <input type="hidden" name="items[{{ $index }}][location]" value="{{ $item->inventory_allocation_id }}">
                                        <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" class="form-control form-control-sm" disabled>
                                    </td>
                                    <td>
                                        <input type="hidden" name="items[{{ $index }}][price]" value="{{ $item->unit_price }}">
                                        {{ number_format($item->unit_price, 2) }}
                                    </td>
                                    <td class="item-total">{{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>

                            <tr>
                                <td colspan="4" class="text-end">Subtotal</td>
                                <td><span id="subtotal">{{ number_format($subtotal, 2) }}</span></td>
                            </tr>
                            <tr id="tax-row" style="display: {{ $tax > 0 ? '' : 'none' }};">
                                <td colspan="4" class="text-end">Sales Tax</td>
                                <td><span id="tax-amount">{{ number_format($tax, 2) }}</span></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total</strong></td>
                                <td><span id="total">{{ number_format($total, 2) }}</span></td>
                            </tr>
                        </tfoot>
                    </table>


            </div>
        </div>
    </div>
</div>
@endsection
