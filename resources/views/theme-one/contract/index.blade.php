@extends('theme-one.layouts.app',['title' => 'Contract','sub_title'=>'List'])
@section('css')
<link href="{{asset('assets/theme_one/lib/datatables.net-dt/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/theme_one/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css')}}"
    rel="stylesheet">
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Contract List </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive=">
            <table id="datatableDefault" class="table text-nowrap w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Contract User</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Contract Type</th>
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
            url: "{{route('app.contract.list')}}",
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

</script>
@endsection