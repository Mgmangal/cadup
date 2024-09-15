@extends('theme-one.layouts.app',['title' => 'Certificate','sub_title'=>$sub_title])
@section('css')
<link href="{{asset('assets/theme_one/lib/datatables.net-dt/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/theme_one/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css')}}"
    rel="stylesheet">
@endsection

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ $sub_title }}</h3>
        <!-- <a href="#" class="btn btn-primary btn-sm p-2">Add New</a> -->
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-sm-2">
                <div class="form-group">
                    <lable for="pilots">Crew</lable>
                    <select name="pilots" id="pilots" class="form-control filters">
                        @if($pilots->count() == 1 && $pilots->first()->id == Auth::user()->id)
                            <option value="{{ $pilots->first()->id }}">{{ $pilots->first()->salutation . ' ' . $pilots->first()->name }}</option>
                        @else
                            <option value="">Select Poilot</option>
                            @foreach($pilots as $pilot)
                                <option value="{{ $pilot->id }}">{{ $pilot->salutation . ' ' . $pilot->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <div class="table-responsive=">
            <table id="datatableGroundTraining" class="table text-nowrap w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Crew Name</th>
                        <th>Info </th>
                        <th>Renewed On</th>
                        <th>Extended Date</th>
                        <th>Next Due</th>
                        <th>Remaining Days</th>
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

<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewModalBody">

            </div>
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
$(".dates").datepicker({
    timepicker: false,
    format: 'd-m-Y',
    formatDate: 'Y/m/d',
    autoclose: true,
    clearBtn: true,
    todayButton: true,
    // maxDate: new Date(new Date().getTime() + 5 * 24 * 60 * 60 * 1000),
});

function groundtrainingdataList() {
    $('#datatableGroundTraining').DataTable().destroy();
    $('#datatableGroundTraining').DataTable({
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
        }, {
            extend: 'csv',
            className: 'btn btn-default btn-sm'
        }],
        ajax: {
            url: "{{ route('user.certificate.groundTrainingList') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                dates: $('.datepicker').val(),
                user_id: $('#pilots').val(),
            },
        },
        "initComplete": function() {

        }
    });
}
groundtrainingdataList();

$('.filters').on('change', function() {
    groundtrainingdataList();
});

function showData(id, type) {
    $.ajax({
        type: "POST",
        url: "{{ route('user.ltm.view') }}",
        data: {
            "_token": "{{ csrf_token() }}",
            id: id,
            type: type
        },
        success: function(data) {
            $('#viewModal').modal('show');
            $('#viewModalBody').html(data);
        }
    });
}
</script>

@endsection
