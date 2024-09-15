@extends('theme-one.layouts.app', ['title' => 'ATA', 'sub_title' => $sub_title])
@section('css')
<link href="{{ asset('assets/theme_one/lib/datatables.net-dt/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/theme_one/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css') }}"
    rel="stylesheet">
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">ATA {{ $sub_title }} </h3>
        <div>
            @can('ATA Add')
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
                        <th>ATA Code</th>
                        <th>Chapter name</th>
                        <th>ATA Category</th>
                        <th>Created On</th>
                        <th>Status</th>
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
                <h5 class="modal-title" id="modalLabel">Manage AMP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('user.ata.ata_store')}}" method="POST" id="manageForm" class="">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required" for="category_id">ATA Category </label>
                        <input type="hidden" name="edit_id" id="edit_id" />
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">Select Category </option>
                            @foreach($ata_categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required" for="code">ATA Code</label>
                        <input type="text" class="form-control" name="code" placeholder="Enter ATA Code" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label required" for="name">Chapter name</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Chapter name" />
                    </div>
                    {{-- <div class="mb-3">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control" name="description" placeholder="Enter Description"> </textarea>
                    </div> --}}
                    <div class="mb-3">
                        <label class="form-label required" for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            {{-- <option value="">Select Status </option> --}}
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
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
                url: "{{route('user.ata.ata_list')}}",
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
        $('#manageForm').find('[name=edit_id]').val('');
        $('#manageModal').modal('show')
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
                    $('#manageForm').find('[name=category_id]').val(response.data.parent_id);
                    $('#manageForm').find('[name=name]').val(response.data.name);
                    $('#manageForm').find('[name=code]').val(response.data.code);
                    $('#manageForm').find('[name=description]').val(response.data.description);
                    $('#manageForm').find('[name=status]').val(response.data.status);
                    $('#manageForm').find('[name=edit_id]').val(response.data.id);
                    $('#manageModal').modal('show');
                }
            }
        });
    }
    function changeStatus(id,status)
    {
        $.ajax({
            url: "{{route('user.ata.ata_status')}}",
            type: 'post',
            data: {id,status,'_token':'{{csrf_token()}}'},
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    swal("Success!", data.message, "success");
                } else {
                    swal("Error!", data.message, "error");
                }
                dataList();
            }
        });
    }
</script>
@endsection
