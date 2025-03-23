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
                    <p>Edit Category</p>
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
                                        <h4 class="card-title">Edit Category</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="basic-form">

                                            <!-- Display Success Message -->
                                            @if(session('success'))
                                                <div class="alert alert-success">
                                                    {{ session('success') }}
                                                </div>
                                            @endif

                                            <form action="{{ route('corporate_admin.fpgcategory.update', $fpgcategory->category_ID) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                        
                                                <div class="row">
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $fpgcategory->name) }}" required>
                                                        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                                                    </div>
                                        
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Category type <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('type') is-invalid @enderror" name="type" value="{{ old('type', $fpgcategory->type) }}" required>
                                                        @error('type') <div class="text-danger">{{ $message }}</div> @enderror
                                                    </div>
                                                </div>
                                        
                                                <button type="submit" class="btn btn-primary bg-primary">Update Category</button>
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