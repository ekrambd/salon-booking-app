@extends('admin_master')
@section('content')

<div class="content-wrapper">

    <!-- Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Withdraw Requests</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Withdraws</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <section class="content">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title">All Withdraw Requests</h3>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table id="table-withdraw-data" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Staff Name</th>
                                <th>Staff Phone</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>TRX ID</th>
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

<!-- ✅ Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="approveForm">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Approve Withdraw</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="withdraw_id" name="id">

                    <div class="form-group">
                        <label>Transaction ID (TRX ID) <span class="text-danger">*</span></label>
                        <input type="text" name="trx_id" id="trx_id" class="form-control" placeholder="Enter TRX ID" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Approve</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection

@push('scripts')

<script>

// CSRF setup
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function(){

    // DataTable
    var table = $('#table-withdraw-data').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ordering: false,
        responsive: true,
        stateSave: true,

        ajax: {
            url: "{{ url('/withdraw-lists') }}"
        },

        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'staff_name', name: 'staff_name'},
            {data: 'staff_phone', name: 'staff_phone'},
            {data: 'amount', name: 'amount'},
            {data: 'date', name: 'date'},
            {data: 'time', name: 'time'},
            {data: 'trx_id', name: 'trx_id'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    // OPEN MODAL
    $(document).on('click', '.approve-btn', function(){

        let id = $(this).data('id');

        $('#withdraw_id').val(id);
        $('#trx_id').val('');

        $('#approveModal').modal('show');
    });

    // SUBMIT APPROVE
    $('#approveForm').on('submit', function(e){
        e.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            url: "{{ url('/withdraw-approve') }}",
            type: "POST",
            data: formData,
            dataType: "json",

            success:function(data){
                if (data.status) {
                    toastr.success(data.message);

                    $('#approveModal').modal('hide');

                    $('#table-withdraw-data').DataTable().ajax.reload(null, false);
                } else {
                    toastr.error(data.message);
                }
            }
        });
    });

});
</script>

@endpush