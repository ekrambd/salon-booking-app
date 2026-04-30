@extends('admin_master')
@section('content')

<div class="content-wrapper">

    <!-- Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">All Payment Methods</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{URL::to('/dashboard')}}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">All Payment Methods</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <section class="content">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title">All Payment Methods</h3>
            </div>

            <div class="card-body">

                <a href="{{route('paymentmethods.create')}}" class="btn btn-primary mb-2">
                    Add New Payment Method
                </a>

                <div class="table-responsive">
                    <table id="pau-data" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>

        </div>
    </section>

</div>

@endsection

@push('scripts')

<script>
$(document).ready(function(){

    let data_id;

    var table = $('#pau-data').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ordering: false,
        responsive: true,
        stateSave: true,
        ajax: {
            url: "{{ url('/paymentmethods') }}"
        },

        columns: [
            {data: 'name', name: 'name'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    // DELETE
    $(document).on('click', '.delete-data', function(e){

        e.preventDefault();
        data_id = $(this).data('id');

        if(confirm('Do you want to delete this?')){

            $.ajax({
                url: "{{ url('/paymentmethods') }}/" + data_id,
                type: "DELETE",
                dataType: "json",

                success:function(data){
                    if (data.status) {
                        toastr.success(data.message);
                        $('.data-table').DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });

    // STATUS TOGGLE
    $(document).on('click', '.status-toggle', function(){

	    const id = $(this).data('id');
	    //alert(id);

	    let isChecked = $(this).prop('checked');
	    let status_val = isChecked ? 'Active' : 'Inactive';

	    $.ajax({
	        url: "{{ url('/paymentmethod-status-update') }}",
	        type: "POST",
	        data: {
	            id: id,
	            status: status_val
	        },
	        dataType: "json",

	        success:function(data){
	            if (data.status) {
	                toastr.success(data.message);
	                $('.data-table').DataTable().ajax.reload(null, false);
	            } else {
	                toastr.error(data.message);
	            }
	        }
	    });
	});

});
</script>

@endpush