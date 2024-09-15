@extends('theme-one.layouts.app', ['title' => 'Masters', 'sub_title' => $sub_title])
@section('css')
<link href="{{ asset('assets/theme_one/lib/datatables.net-dt/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/theme_one/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css') }}"
    rel="stylesheet">
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ $sub_title }} @if(!empty($role)) - {{$role->name}} @endif</h3>
        <div>
            @can('Role Add')
            <a href="javascript:void(0);" class="btn btn-primary" onclick="addRole();">Add Role</a>
            @endcan
            @if(!empty($parentId))
            <a href="{{url()->previous()}}" class="btn btn-info btn-sm p-2">Back</a>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="datatableDefault" class="table text-nowrap w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
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

<div class="modal fade" id="manageRoleModal" tabindex="-1" role="dialog" aria-labelledby="roleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roleModalLabel">Add Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('user.master.role_store')}}" method="POST" id="roleForm" class="">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Role Name</label>
                        <input type="hidden" name="edit_id" id="edit_id" />
                        <input type="hidden" name="parent_id" id="parent_id" value="" />
                        <input type="text" class="form-control" name="name" placeholder="Enter Role Name" />
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
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
            processing: true,
            serverSide: true,
            dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
            lengthMenu: [20, 50, 100, 200, 500, 1000, 2000, 5000, 10000],
            responsive: true,
            columnDefs: [{
                width: 200,
                targets: 3
            }],
            fixedColumns: true,
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
                url: "{{route('user.master.role_list')}}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "parent_id": "{{$parentId}}"
                },
            },
            "initComplete": function() {

            }
        });
    }
    dataList();

    function addRole() {
        $('#roleForm').find('.is-invalid').removeClass('is-invalid');
        $('#roleForm').find('.invalid-feedback').hide();
        $('#roleForm')[0].reset();
        $('#parent_id').val('{{$parentId}}');
        $('#manageRoleModal').modal('show')
    }

    $('#roleForm').submit(function(e) {
        e.preventDefault();
        $('#roleForm').find('.invalid-feedback').hide();
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            dataType: 'json',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#roleForm')[0].reset();
                    $('#manageRoleModal').modal('hide');
                    dataList();
                } else {
                    $('#roleForm').find('.invalid-feedback').show().html(response.message);
                    // $.each(response.errors, function(fieldName, field){
                    //     $('#roleForm').find('[name='+fieldName+']').addClass('is-invalid');
                    //     $('#roleForm').find('[name='+fieldName+']').after('<div class="invalid-feedback">'+field+'</div>');
                    // })
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
                    $('#roleForm').find('[name=parent_id]').val(response.data.parent_id);
                    $('#roleForm').find('[name=name]').val(response.data.name);
                    $('#roleForm').find('[name=edit_id]').val(response.data.id);
                    $('#manageRoleModal').modal('show');
                }
            }
        });
    }
</script>
@endsection
