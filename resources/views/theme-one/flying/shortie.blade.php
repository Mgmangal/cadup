@extends('theme-one.layouts.app',['title' => 'Flying','sub_title'=>'Shortie List'])
@section('css')
<link href="{{asset('assets/theme_one/lib/datatables.net-dt/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/theme_one/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css')}}"
    rel="stylesheet">
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Shortie List</h3>
        <!-- <a href="{{route('app.flying-details.create')}}" class="btn btn-primary btn-sm p-2">Add New</a> -->
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="from_date" class="form-label">From Date</label>
                    <input type="text" class="form-control filters dates" id="from_date" placeholder="DD-MM-YYYY" autocomplete="off">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="to_date" class="form-label">To Date</label>
                    <input type="text" class="form-control filters dates" id="to_date" placeholder="DD-MM-YYYY" autocomplete="off">
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label for="aircraft" class="form-label">Aircraft</label>
                    <select class="form-control filters" id="aircraft">
                        <option value="">Select</option>
                        @foreach($aircrafts as $aircraft)
                        <option value="{{$aircraft->id}}">{{$aircraft->call_sign}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label for="$passenger" class="form-label">Passenger </label>
                    <select class="form-control filters" id="passenger">
                        <option value="">Select</option>
                        @foreach($passengers as $passenger)
                            <option value="{{$passenger->more_data}}">{{$passenger->more_data}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label for="from_sector" class="form-label">From Sector</label>
                    <input type="text" class="form-control filters auto_complete_input" id="from_sector" placeholder="" autocomplete="off">
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label for="to_sector" class="form-label">To Sector</label>
                    <input type="text" class="form-control filters auto_complete_input" id="to_sector" placeholder="" autocomplete="off">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="pilot" class="form-label">Pilots</label>
                    <select class="form-control filters" id="pilot">
                        <option value="">Select</option>
                        @foreach($pilots as $pilot)
                        <option value="{{$pilot->id}}">{{$pilot->salutation}} {{$pilot->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="flying_type" class="form-label">Flying Type</label>
                    <select class="form-control filters" id="flying_type">
                        <option value="">Select</option>
                        @foreach($flying_types as $flying_type)
                            <option value="{{$flying_type['id']}}">{{$flying_type['name']}}</option>
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
                        <th>From/To</th>
                        <th>Chocks-Off /<br>Chocks-On</th>
                        <th>Block Time<br> (Hrs:Mins)</th>
                        <th>Crew</th>
                        <th>Flying Type</th>
                        <!-- <th>Passenger</th> -->
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
            order: [[1, 'desc']],
            // columnDefs: [{
            //     width: 200,
            //     targets: 3
            // }],
            fixedColumns: true,
            buttons: [{
                    extend: 'print',
                    className: 'btn btn-default btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2,3,4, 5,6,7]
                    }
                },
                {
                    extend: 'csv',
                    className: 'btn btn-default btn-sm'
                }
            ],
            ajax: {
                url: "{{route('user.flying.shortieList')}}",
                type: "post",
                data: {
                    "_token": "{{ csrf_token() }}",from_date:$('#from_date').val(),to_date:$('#to_date').val(),aircraft:$('#aircraft').val(),pilot:$('#pilot').val(),flying_type:$('#flying_type').val(),from_sector:$('#from_sector').val(),to_sector:$('#to_sector').val(),passenger:$('#passenger').val()
                },
            },
            fnRowCallback: function( nRow, aData, iDisplayIndex ) {
                var oSettings = this.fnSettings ();
                $("td:eq(0)", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
                $('td:eq(5)', nRow).css('text-align','center');
            },
            "initComplete": function() {

            }
        });
    }
    dataList();

    $('.filters').on('change',function(){
        dataList();
    });

    function getSectors()
    {
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

    function autoCompleteInput()
    {
        $( ".auto_complete_input" ).autocomplete({
          source: jQuery.parseJSON( localStorage.getItem('htmltest'))
        });
    }

    autoCompleteInput();
</script>
@endsection
