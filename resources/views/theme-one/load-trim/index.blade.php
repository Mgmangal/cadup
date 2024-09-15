@extends('theme-one.layouts.app',['title' => 'LOAD & TRIM','sub_title'=>''])
@section('css')
<link href="{{asset('assets/theme_one/lib/datatables.net-dt/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/theme_one/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css')}}" rel="stylesheet">
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Load Trim List </h3>
        <div>
            <a href="{{route('user.loadTrim.add')}}" class="btn btn-primary btn-sm p-2">Add</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <lable for="from_date">From Date</lable>
                    <input type="text" class="form-control datepicker filter" id="from_date" name="from_date" value=""
                        autocomplete="off">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <lable for="to_date">To Date</lable>
                    <input type="text" class="form-control datepicker filter" id="to_date" name="to_date" value=""
                        autocomplete="off">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="aircraft">Aircraft<span class="text-danger">*</span></label>
                    <select class="form-control filter" id="aircraft" name="aircraft" required>
                        <option value="">Select</option>
                        @foreach($aircrafts as $aircraft)
                        <option value="{{$aircraft->id}}">{{$aircraft->call_sign}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="datatableDefault" class="table text-nowrap w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Aircraft</th>
                        <th>Sheets </th>
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
        var from_date=$('#from_date').val();
        var to_date=$('#to_date').val();
        var aircraft=$('#aircraft').val();
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
                url: "{{route('user.loadTrim.list')}}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",aircraft,from_date,to_date
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
</script>
<script>
    $('.datepicker').datepicker({});
</script>
@endsection
