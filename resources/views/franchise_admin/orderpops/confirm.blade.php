@extends('layouts.app')
@section('content')

    <!--**********************************
                Content body start
            ***********************************-->
    <div class=" content-body default-height">
        <!-- row -->
        <div class="container-fluid">
            <!-- <div class="page-titles">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Analytics</a></li>
                        </ol>
                    </div> -->
            <div class="form-head mb-4 d-flex flex-wrap align-items-center">
                <div class="me-auto">
                    <h2 class="font-w600 mb-0">Dashboard \</h2>
                    <p>Confirm Order</p>
                </div>

                <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left me-2"></i> Back
                </a>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="col-xl-12 col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Confirm Order</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="basic-form">

                                            @if(session('success'))
                                            <div class="alert alert-success">
                                                {{ session('success') }}
                                            </div>
                                        @endif
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="table-responsive rounded">
                                                    @if (!empty($items))
                                                        <form action="{{ route('franchise_admin.orderpops.store') }}" method="POST">
                                                            @csrf
                                                            <table id="example5" class="table customer-table display mb-4 fs-14 card-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Item Name</th>
                                                                        <th>User</th>
                                                                        <th>Qty</th>
                                                                        <th>Cost</th>
                                                                        <th>Total</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($items as $index => $item)
                                                                        <tr>
                                                                            <td>
                                                                                <input type="hidden" name="items[{{ $index }}][fgp_item_id]" value="{{ $item['itemId'] }}">
                                                                                {{ $item['name'] }}
                                                                            </td>
                                                                            <td>
                                                                                <input type="hidden" name="items[{{ $index }}][user_ID]" value="{{ auth()->user()->user_id }}">
                                                                                {{ auth()->user()->name   }}
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" name="items[{{ $index }}][unit_number]" class="form-control qty" min="1" value="{{ $item['quantity'] }}" data-index="{{ $index }}">
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" name="items[{{ $index }}][unit_cost]" class="form-control cost" step="0.01" value="{{ $item['price'] }}" data-index="{{ $index }}">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" class="form-control total" readonly value="{{ number_format($item['price'] * $item['quantity'], 2) }}" data-index="{{ $index }}">
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                    <tr>
                                                                        <td colspan="4" class="text-end"><strong>Subtotal</strong></td>
                                                                        <td><input type="text" id="subtotal" class="form-control" readonly></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="4" class="text-end"><strong>Tax (%)</strong></td>
                                                                        <td><input type="number" id="taxRate" class="form-control" step="0.01" value="10"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="4" class="text-end"><strong>Total</strong></td>
                                                                        <td><input type="text" id="grandTotal" class="form-control" readonly></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <button type="submit" class="btn btn-primary bg-primary">Confirm Order</button>
                                                        </form>
                                                    @else
                                                        <p>No items in the order.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                            
                                            <script>
                                                function calculateTotals() {
                                                    let subtotal = 0;
                                            
                                                    document.querySelectorAll('.qty').forEach((el, index) => {
                                                        let qty = parseFloat(el.value) || 0;
                                                        let cost = parseFloat(document.querySelector(`.cost[data-index='${index}']`).value) || 0;
                                                        let total = qty * cost;
                                                        document.querySelector(`.total[data-index='${index}']`).value = total.toFixed(2);
                                                        subtotal += total;
                                                    });
                                            
                                                    document.getElementById('subtotal').value = subtotal.toFixed(2);
                                                    let taxRate = parseFloat(document.getElementById('taxRate').value) || 0;
                                                    let taxAmount = (subtotal * taxRate) / 100;
                                                    document.getElementById('grandTotal').value = (subtotal + taxAmount).toFixed(2);
                                                }
                                            
                                                document.querySelectorAll('.qty, .cost, #taxRate').forEach(el => {
                                                    el.addEventListener('input', calculateTotals);
                                                });
                                            
                                                window.onload = calculateTotals;
                                            </script>
                                            
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--**********************************
                Content body end
            ***********************************-->


@endsection