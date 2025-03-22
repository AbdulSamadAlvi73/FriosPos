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
                    <p>Add User</p>
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
                                        <h4 class="card-title">Add User</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="basic-form">

                                            <!-- Display Success Message -->
                                            @if(session('success'))
                                                <div class="alert alert-success">
                                                    {{ session('success') }}
                                                </div>
                                            @endif
                                            @role('franchise_admin')
                                            <form action="{{ route('franchise_admin.staff.store') }}" method="POST">
                                            @endrole
                                            @role('franchise_manager')
                                            <form action="{{ route('franchise_manager.staff.store') }}" method="POST">
                                            @endrole
                                                @csrf
                                            
                                                <div class="row">
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Staff Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                            name="name" value="{{ old('name') }}" placeholder="Owner Name">
                                                        @error('name')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                            
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                            name="email" value="{{ old('email') }}" placeholder="Email">
                                                        @error('email')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                            
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                            name="password" placeholder="Password">
                                                        @error('password')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                            
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Assign Role <span class="text-danger">*</span></label>
                                                        <select class="form-control @error('role') is-invalid @enderror" name="role">
                                                            <option value="">Select Role</option>
                                                            <option value="franchise_manager">Manager</option>
                                                            <option value="franchise_staff">Staff</option>
                                                        </select>
                                                        @error('role')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                            
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Clearance</label>
                                                        <input type="text" class="form-control @error('clearance') is-invalid @enderror"
                                                            name="clearance" value="{{ old('clearance') }}" placeholder="Clearance">
                                                        @error('clearance')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                            
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Security</label>
                                                        <input type="text" class="form-control @error('security') is-invalid @enderror"
                                                            name="security" value="{{ old('security') }}" placeholder="Security">
                                                        @error('security')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            
                                                <button type="submit" class="btn btn-primary bg-primary">Add Staff</button>
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