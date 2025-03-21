@extends('layouts.app')
@section('content')

    {{-- <div class="container">
        <h1>Add Franchise</h1>
        <form action="{{ route('corporate_admin.franchise.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Business Name</label>
                <input type="text" name="business_name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div> --}}


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
                    <p>Add Franchise</p>
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
                                        <h4 class="card-title">Add Franchise</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="basic-form">

                                            <!-- Display Success Message -->
                                            @if(session('success'))
                                                <div class="alert alert-success">
                                                    {{ session('success') }}
                                                </div>
                                            @endif

                                            <form action="{{ route('corporate_admin.franchise.store') }}" method="POST">
                                                @csrf

                                                <div class="row">
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Business Name <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text"
                                                            class="form-control @error('business_name') is-invalid @enderror"
                                                            name="business_name" value="{{ old('business_name') }}"
                                                            placeholder="Business Name">
                                                        @error('business_name')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    {{-- <div class="mb-3 col-md-6">
                                                        <label class="form-label">Join Date <span
                                                                class="text-danger">*</span></label>
                                                        <input type="date"
                                                            class="form-control @error('join_date') is-invalid @enderror"
                                                            name="join_date" value="{{ old('join_date') }}">
                                                        @error('join_date')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div> --}}

                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Address 1 <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text"
                                                            class="form-control @error('address1') is-invalid @enderror"
                                                            name="address1" value="{{ old('address1') }}"
                                                            placeholder="Address Line 1">
                                                            <small class="form-text text-muted">Street address, P.O box, company name, c/o</small>
                                                        @error('address1')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Address 2</label>
                                                        <input type="text"
                                                            class="form-control @error('address2') is-invalid @enderror"
                                                            name="address2" value="{{ old('address2') }}"
                                                            placeholder="Address Line 2">
                                                            <small class="form-text text-muted">Aprtment, suite, unit, building, floor, etc</small>
                                                        @error('address2')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">State/Provoince/Region<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text"
                                                            class="form-control @error('state') is-invalid @enderror"
                                                            name="state" value="{{ old('state') }}" placeholder="State">
                                                        @error('state')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Zip / Postal Code <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text"
                                                            class="form-control @error('zip_code') is-invalid @enderror"
                                                            name="zip_code" value="{{ old('zip_code') }}"
                                                            placeholder="Zip Code" pattern="\d{5}">
                                                        @error('zip_code')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <!-- Include jQuery & Select2 -->
                                                    <script
                                                        src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                                                    <link rel="stylesheet"
                                                        href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
                                                    <script
                                                        src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

                                                    <!-- Multi-select Dropdown for ZIP Codes -->
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Location ZIP Codes</label>
                                                        <select class="form-control" name="location_zip[]" id="location_zip"
                                                            multiple>
                                                            <option value="35004">35004</option>
                                                            <option value="99501">99501</option>
                                                            <option value="85001">85001</option>
                                                            <option value="71601">71601</option>
                                                            <option value="90001">90001</option>
                                                            <option value="80001">80001</option>
                                                            <option value="06001">06001</option>
                                                            <option value="19701">19701</option>
                                                            <option value="32003">32003</option>
                                                            <option value="30002">30002</option>
                                                            <option value="96701">96701</option>
                                                            <option value="83201">83201</option>
                                                            <option value="60001">60001</option>
                                                            <option value="46001">46001</option>
                                                            <option value="50001">50001</option>
                                                        </select>
                                                        <small class="form-text text-muted">Select ZIP
                                                            codes.</small>
                                                        @error('location_zip')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- JavaScript -->
                                                    <script>
                                                        $(document).ready(function () {
                                                            $('#location_zip').select2({
                                                                tags: true,  // Allow user to enter new values
                                                                tokenSeparators: [',', ' '], // Allow separating with commas or spaces
                                                                placeholder: "Type or select ZIP codes...",
                                                                allowClear: true,
                                                                createTag: function (params) {
                                                                    let term = $.trim(params.term);
                                                                    if (term.match(/^\d{5}$/)) {  // Ensure only valid 5-digit ZIP codes
                                                                        return { id: term, text: term, newTag: true };
                                                                    }
                                                                    return null; // Prevent invalid input
                                                                }
                                                            });
                                                        });
                                                    </script>




                                                </div>

                                                <button type="submit" class="btn btn-primary bg-primary">Add Franchise</button>
                                            </form>
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