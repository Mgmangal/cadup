<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">Receive Flight Doc List</li>
        </ul>
    </x-slot>
    <x-slot name="css">
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" />
        <link href="{{asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}" rel="stylesheet" />
    </x-slot>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Validation -->
    <x-errors class="mb-4" />
    <x-success class="mb-4" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Receive Flight Doc List</h3>
            <a href="{{route('app.flying-details.receiveFlightDoc.add')}}" class="btn btn-primary btn-sm p-2">Add New</a>
        </div>
        <div class="card-body">
            <div class="row">
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
                        <label for="$passenger" class="form-label">Day Officer </label>
                        <select class="form-control filters" id="passenger">
                            <option value="">Select</option>
                            @foreach($passengers as $passenger)
                                <option value="{{$passenger->id}}">{{$passenger->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="bunch_no" class="form-label">Bunch No</label>
                        <input type="text" class="form-control filters" id="bunch_no" placeholder="bunch no" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="bunch_no" class="form-label">&nbsp;</label>
                        <button type="button" class="form-control btn btn-sm btn-info" onclick="printDiv();">Print</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="datatableDefault" class="table text-nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bunch No</th>
                            <th>Date</th>
                            <th>Day Officer</th>
                            <th>Remark</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="postFlightDocModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Modal Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3" id="data-result">

                    </div>
                </div>
            </div>
        </div>
    </div>




    <x-slot name="js">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
        <script src="{{asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js')}}"></script>
        <script>
            $(".dates").datetimepicker({
                timepicker: false,
                format: 'd-m-Y',
                formatDate:'Y/m/d',
                autoclose: true,
                clearBtn: true,
                todayButton: true,
                // maxDate: new Date(new Date().getTime() + 5 * 24 * 60 * 60 * 1000),
                onSelectDate: function(ct) {
                }
            });

            function dataList() {
                $('#datatableDefault').DataTable().destroy();
                $('#datatableDefault').DataTable({
                    processing: true,
                    serverSide: true,
                    dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-flex justify-content-end'fB>>>t<'d-flex align-items-center'<'me-auto'i><'mb-0'p>>",
                    lengthMenu: [20, 50, 100, 200, 500, 1000, 2000, 5000, 10000],
                    responsive: true,
                     order: [[0, 'desc']],
                    // columnDefs: [{
                    //     width: 200,
                    //     targets: 3
                    // }],
                    fixedColumns: true,
                    buttons: [
                        {
                            extend: 'csv',
                            className: 'btn btn-default btn-sm'
                        }
                    ],
                    ajax: {
                        url: "{{route('app.flying-details.receiveFlightDoc.list')}}",
                        type: "post",
                        data: {
                            "_token": "{{ csrf_token() }}",from_date:$('#from_date').val(),to_date:$('#to_date').val(),passenger:$('#passenger').val(),bunch_no:$('#bunch_no').val()
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

            function printDiv()
            {
                var from_date = $('#from_date').val() ? $('#from_date').val() : 'NA';
                var to_date = $('#to_date').val() ? $('#to_date').val() : 'NA';
                var passenger = $('#passenger').val() ? $('#passenger').val() : 'NA';
                var bunch_no = $('#bunch_no').val() ? $('#bunch_no').val() : 'NA';
                window.open("{{ route('app.flying-details.assignPostFlightDocPrint') }}" + '/' + from_date+'/'+to_date+'/' + passenger+'/'+bunch_no, 'printDiv', 'height=900,width=900');
            }

            function openFlightDetailModel(id, type) {
                $.ajax({
                    url: "{{route('app.openFlightDetailModel')}}",
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id,
                        "type": type,
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#modalLabel').html(response.title);
                            $('#data-result').html(response.html);
                            $('#postFlightDocModal').modal('show');
                        }
                    }
                });
            }
        </script>
    </x-slot>
</x-app-layout>
