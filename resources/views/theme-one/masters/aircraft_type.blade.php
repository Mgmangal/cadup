@extends('theme-one.layouts.app', ['title' => 'Masters', 'sub_title' => $sub_title])
@section('css')
<link href="{{ asset('assets/theme_one/lib/datatables.net-dt/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/theme_one/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css') }}"
    rel="stylesheet">
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ $sub_title }} </h3>
        <div>
            @can('Aircraft Type Add')
                <a href="javascript:void(0);" class="btn btn-primary" onclick="addNew();">Add New</a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="datatableDefault" class="table text-nowrap w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Aircraft Category</th>
                        <th>Created On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="manageModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Manage Aircraft Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('user.master.aircraft_type_store')}}" method="POST" id="manageForm" class="">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden" name="edit_id" id="edit_id" />
                        <label class="form-label">Aircraft Type</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Aircraft Type" />
                    </div>
                    <div class="mb-3">
                        <label for="aircraft_cateogry" class="form-label">Aircraft Category<span
                                class="text-danger">*</span></label>
                        <select name="aircraft_cateogry" id="aircraft_cateogry" class="form-control">
                            <option value="">Select</option>
                            <option value="Fixed Wing">Fixed Wing</option>
                            <option value="Rotor Wing">Rotor Wing</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('assets/theme_one/lib/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/theme_one/lib/datatables.net-dt/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/theme_one/lib/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/theme_one/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js') }}"></script>
<script>
    function dataList() {
        $('#datatableDefault').DataTable().destroy();
        $('#datatableDefault').DataTable({
            processing: false,
            serverSide: true,
            dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
            lengthMenu: [20, 50, 100, 200, 500, 1000, 2000, 5000, 10000],
            responsive: true,
            order: [[2, 'asc']],
            // columnDefs: [{
            //     width: 200,
            //     targets: 3
            // }],
            // fixedColumns: true,
            buttons: [{
                    extend: 'print',
                    className: 'btn btn-default btn-sm'
                },
                {
                    extend: 'csv',
                    className: 'btn btn-default btn-sm'
                }
            ],
            ajax: {
                url: "{{route('user.master.aircraft_type_list')}}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
            },
            "initComplete": function() {

            }
        });
    }
    dataList();

    function addNew() {
        $('#manageForm').find('.is-invalid').removeClass('is-invalid');
        $('#manageForm').find('.invalid-feedback').hide();
        $('#manageForm')[0].reset();
        $('#manageModal').modal('show');
        $('#edit_id').val('');
    }
    $('#manageForm').submit(function(e) {
        e.preventDefault();
        $('#manageForm').find('.invalid-feedback').hide();
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            dataType: 'json',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#manageForm')[0].reset();
                    $('#manageModal').modal('hide');
                    dataList();
                } else {
                    $.each(response.message, function(fieldName, field) {
                        $('#manageForm').find('[name=' + fieldName + ']').addClass('is-invalid');
                        $('#manageForm').find('[name=' + fieldName + ']').after('<div class="invalid-feedback">' + field + '</div>');
                    })
                }

            }
        })
    })

    function editRole(url) {
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#manageForm').find('[name=aircraft_cateogry]').val(response.data.more_data);
                    $('#manageForm').find('[name=name]').val(response.data.name);
                    $('#manageForm').find('[name=edit_id]').val(response.data.id);
                    $('#manageModal').modal('show');
                }
            }
        });
    }

</script>
@endsection
