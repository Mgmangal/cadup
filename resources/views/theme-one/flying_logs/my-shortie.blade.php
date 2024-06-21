@extends('theme-one.layouts.app',['title' => 'Flying','sub_title'=>'My Sortie'])
@section('css')
<link href="{{asset('assets/theme_one/lib/datatables.net-dt/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/theme_one/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css')}}"
    rel="stylesheet">
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">My Sortie List</h3>
        <!-- <a href="{{route('app.flying-details.create')}}" class="btn btn-primary btn-sm p-2">Add New</a> -->
    </div>
    <div class="card-body">
        <div class="row row-sm mb-3">
            <div class="col-md-2">
                <label for="from_date">From Date</label>
                <input type="text" class="form-control filters dates p-1" id="from_date" placeholder="DD-MM-YYYY" autocomplete="off">
            </div>
            <div class="col-md-2">
                <label for="to_date">To Date</label>
                <input type="text" class="form-control filters dates p-1" id="to_date" placeholder="DD-MM-YYYY" autocomplete="off">
            </div>
            <div class="col-md-1">
                <label for="aircraft">Aircraft</label>
                <select class="form-control filters p-1" id="aircraft">
                    <option value="">Select</option>
                    @foreach($aircrafts as $aircraft)
                    <option value="{{$aircraft->id}}">{{$aircraft->call_sign}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <label for="from_sector">From Sector</label>
                <input type="text" class="form-control filters auto_complete_input p-1" id="from_sector" placeholder=""
                        autocomplete="off">
            </div>
            <div class="col-md-1">
                <label for="to_sector">To Sector</label>
                <input type="text" class="form-control filters auto_complete_input p-1" id="to_sector" placeholder=""
                        autocomplete="off">
            </div>
            <div class="col-md-2">
                <label for="flying_type">Flying Type</label>
                <select class="form-control filters p-1" id="flying_type">
                    <option value="">Select</option>
                    @foreach($flying_types as $flying_type)
                    <option value="{{$flying_type['id']}}">{{$flying_type['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="flying_type">My Role</label>
                <select class="form-control filters p-1" id="pilot">
                    <option value="">Select</option>
                    @foreach($pilot_role as $pilotrole)
                    <option value="{{$pilotrole->id}}">{{$pilotrole->name}}</option>
                    @endforeach
                </select>
            </div>


        </div>
        <div class="table-responsive=">
            <table id="datatableDefault" class="table text-nowrap w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Aircraft</th>
                        <th>From/To</th>
                        <th>Chocks-Off / On</th>
                        <th>Block Time<br> (Hrs:Mins)</th>
                        <th>Role</th>
                        <th>Flying Type</th>
                        <th>Verify Status</th>
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
$(".dates").datepicker({
    timepicker: false,
    format: 'd-m-Y',
    formatDate: 'Y/m/d',
    autoclose: true,
    clearBtn: true,
    todayButton: true,
    // maxDate: new Date(new Date().getTime() + 5 * 24 * 60 * 60 * 1000),
});

function dataList() {
    $('#datatableDefault').DataTable().destroy();
    $('#datatableDefault').DataTable({
        processing: true,
        serverSide: true,
        dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
        lengthMenu: [20, 50, 100, 200, 500, 1000, 2000, 5000, 10000],
        responsive: true,
        order: [
            [1, 'desc']
        ],
        fixedColumns: true,
        buttons: [{
                extend: 'print',
                className: 'btn btn-default btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            },
            {
                extend: 'csv',
                className: 'btn btn-default btn-sm'
            }
        ],
        ajax: {
            url: "{{route('user.flying.myshortie')}}",
            type: "post",
            data: {
                "_token": "{{ csrf_token() }}",
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val(),
                aircraft: $('#aircraft').val(),
                pilot:"{{Auth::user()->id}}",
                flying_type: $('#flying_type').val(),
                from_sector: $('#from_sector').val(),
                to_sector: $('#to_sector').val(),
                passenger: $('#passenger').val()
            },
        },
        fnRowCallback: function(nRow, aData, iDisplayIndex) {
            var oSettings = this.fnSettings();
            $("td:eq(0)", nRow).html(oSettings._iDisplayStart + iDisplayIndex + 1);
            $('td:eq(5)', nRow).css('text-align', 'center');
        },
        "initComplete": function() {

        }
    });
}
dataList();

$('.filters').on('change', function() {
    dataList();
});

function getSectors() {
    $.ajax({
        url: "{{route('app.sectors.autocomplete')}}",
        type: 'get',
        data: {},
        dataType: 'json',
        success: function(data) {
            console.log(data);
            localStorage.setItem('htmltest', JSON.stringify(data));
        }
    });
}
getSectors();

function autoCompleteInput() {
    $(".auto_complete_input").autocomplete({
        source: jQuery.parseJSON(localStorage.getItem('htmltest'))
    });
}

autoCompleteInput();
</script>
@endsection