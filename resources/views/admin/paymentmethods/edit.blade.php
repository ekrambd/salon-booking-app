@extends('admin_master')
@section('content')

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Payment Method</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{route('paymentmethods.index')}}">All Payment Methods</a></li>
                        <li class="breadcrumb-item active">Edit Payment Method</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit Payment Method</h3>
            </div>

            <form action="{{route('paymentmethods.update',$method->id)}}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <div class="row">

                        <!-- Name -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name <span class="required">*</span></label>
                                <input type="text" name="name" class="form-control"
                                    value="{{old('name',$method->name)}}" required>

                                @error('name')
                                    <span class="alert alert-danger d-block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status <span class="required">*</span></label>
                                <select class="form-control select2bs4" name="status" required>
                                    <option value="Active" {{ (old('status',$method->status)=='Active')?'selected':'' }}>Active</option>
                                    <option value="Inactive" {{ (old('status',$method->status)=='Inactive')?'selected':'' }}>Inactive</option>
                                </select>

                                @error('status')
                                    <span class="alert alert-danger d-block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group w-100 px-2">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </section>

</div>

@endsection