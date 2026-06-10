@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">All Notification</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">All Notification</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Notification</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <a href="{{url('/add-notification')}}" class="btn btn-primary add-new mb-2">Add New Notification</a>
                <div class="fetch-data table-responsive">
                    <table id="notification-data" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="conts">
                        </tbody>
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
  		let notification_id;
  		var table = $('#notification-data').DataTable({
		        searching: true,
		        processing: true,
		        serverSide: true,
		        ordering: false,
		        responsive: true,
		        stateSave: true,
		        ajax: {
		          url: "{{ url('/all-notifications') }}",
		        },

		        columns: [
		            {data: 'title', name: 'title'},
		            {data: 'date', name: 'date'},
		            {data: 'time', name: 'time'},
		            {data: 'action', name: 'action', orderable: false, searchable: false},
		        ]
        });

        $(document).on('click', '.delete-notification', function(e){

            e.preventDefault();

            notification_id = $(this).data('id');

            if(confirm('Do you want to delete this?'))
            {
                $.ajax({

                    url: "{{url('/delete-notification')}}/"+notification_id,
                    type:"GET",
                    dataType:"json",
                    success:function(data) {
                        if (data.status) {
                            toastr.success(data.message);

                            $('.data-table').DataTable().ajax.reload(null, false);
                        } else {
                            toastr.error(data.message);
                        }
                    },
                });
            }
        });

  	});
  </script>

@endpush
