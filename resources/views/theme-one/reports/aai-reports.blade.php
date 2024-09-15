@extends('theme-one.layouts.app', ['title' => 'Reports', 'sub_title' => $sub_title])
@section('css')
<link href="{{asset('assets/theme_one/lib/datatables.net-dt/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/theme_one/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css')}}"
    rel="stylesheet">

@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">AAI Reports</h3>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="from_date" class="form-label">From Date</label>
                    <input type="text" class="form-control filters dates" id="from_date" placeholder="DD-MM-YYYY" autocomplete="off">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="to_date" class="form-label">To Date</label>
                    <input type="text" class="form-control filters dates" id="to_date" placeholder="DD-MM-YYYY" autocomplete="off">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="from_sector" class="form-label">Sector</label>
                    <input type="text" class="form-control filters auto_complete_input" id="from_sector" placeholder="Sector" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table id="datatableDefault" class="table text-nowrap w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        {{-- <th>Flying Log ID</th> --}}
                        <th>D I IND</th>
                        <th>RCS IND</th>
                        <th>Booking Date</th>
                        <th>Modification Date</th>
                        <th>Original PNR</th>
                        <th>Parent PNR</th>
                        <th>Tail Number</th>
                        <th>Departure Date</th>
                        <th>Departure Time UTC</th>
                        <th>Departure Time Local</th>
                        <th>Flight Number</th>
                        <th>PNR Actual Departure Station</th>
                        <th>Departure Station</th>
                        <th>Arrival Station</th>
                        <th>Final Station</th>
                        <th>Nationality</th>
                        <th>Carrier Code</th>
                        <th>Total Pax</th>
                        <th>Adult Count</th>
                        <th>Child Count</th>
                        <th>Infant Count</th>
                        <th>Sky Marshall Count</th>
                        <th>Connection Status Embarkation</th>
                        <th>Connection Status Disembarkation</th>
                        <th>Flight Status</th>
                        <th>PNR Status</th>
                        <!--<th>Action</th>-->
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>


@endSection

@section('js')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="{{asset('assets/theme_one/lib/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/theme_one/lib/datatables.net-dt/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{asset('assets/theme_one/lib/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/theme_one/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js')}}"></script>

<script>
      $('.dates').datepicker({});

    function dataList() {
        $('#datatableDefault').DataTable().destroy();
        $('#datatableDefault').DataTable({
            processing: true,
            serverSide: true,
            dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
            lengthMenu: [20, 50, 100, 200, 500, 1000, 2000, 5000, 10000],
            responsive: false,
            order: [
                [1, 'desc']
            ],
            // columnDefs: [{
            //     width: 200,
            //     targets: 3
            // }],
            fixedColumns: true,
            buttons: [{
                    extend: 'print',
                    className: 'btn btn-default btn-sm',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26]
                    }
                },
                {
                    extend: 'csv',
                    className: 'btn btn-default btn-sm',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26]
                    }
                }
            ],
            ajax: {
                url: "{{route('user.aai_report.list')}}",
                type: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                    from_date: $('#from_date').val(),
                    to_date: $('#to_date').val(),
                    from_sector: $('#from_sector').val()
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

@endSection