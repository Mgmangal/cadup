@extends('theme-one.layouts.app',['title' => 'TBO List'])
@section('css')
<link href="{{asset('assets/theme_one/lib/datatables.net-dt/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/theme_one/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css')}}" rel="stylesheet">
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">TBO List</h3>
        <div>
            <a href="{{route('user.tbo.add')}}" class="btn btn-primary btn-sm p-2">Add New</a>
        </div>
    </div>
    <div class="card-body">
        {{-- <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="aircraft">Aircraft<span class="text-danger">*</span></label>
                    <select class="form-control filter" id="aircraft" name="aircraft" required>
                        <option value="">Select</option>
                        @foreach($aircrafts as $aircraft)
                        <option value="{{$aircraft->call_sign}}">{{$aircraft->call_sign}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <lable for="fitting_date">Fitting Date</lable>
                    <input type="text" class="form-control datepicker filter" id="fitting_date" name="fitting_date" value=""
                        autocomplete="off">
                </div>
            </div>
        </div> --}}

        <div class="table-responsive">
            <table id="datatableDefault" class="table text-nowrap w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>TBO Name</th>
                        <th>Aircraft</th>
                        <th>TBO Type </th>
                        <th>ATA Code</th>
                        <th>Part Number</th>
                        <th>Serial Number</th>
                        <th>TBO Requirement</th>
                        <th>Date of Fitting</th>
                        <th>Created At</th>
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
@endsection

@section('js')
<script src="{{asset('assets/theme_one/lib/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/theme_one/lib/datatables.net-dt/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{asset('assets/theme_one/lib/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/theme_one/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js')}}"></script>
<script>
    function dataList() {
        $('#datatableDefault').DataTable().destroy();
        var aircraft=$('#aircraft').val();
        var fitting_date=$('#fitting_date').val();
        $('#datatableDefault').DataTable({
            processing: true,
            serverSide: true,
            dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
            lengthMenu: [20, 50, 100, 200, 500, 1000, 2000, 5000, 10000],
            responsive: false,
            order: [[0, 'desc']],
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
                url: "{{route('user.tbo.list')}}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",aircraft,fitting_date
                },
            },
            "initComplete": function() {

            }
        });
    }
    dataList();
    $('.filter').on('change',function(){
        dataList();
    });
    function changeStatus(id,status)
    {
        $.ajax({
            url: "{{route('user.tbo.status')}}",
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
<script>
    $('.datepicker').datepicker({});
</script>
@endsection
