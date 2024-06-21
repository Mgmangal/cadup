<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">PROCESS FLYING LOG</li>
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
            <h3 class="card-title">Flying Log List</h3>
            <div>
                <a href="javascript:void(0);" onclick="unprocess();" class="btn btn-info btn-sm p-2">Unprocess</a>
                <a href="javascript:void(0);" onclick="process();" class="btn btn-primary btn-sm p-2">Process</a>
                <a href="javascript:void(0);" onclick="analyzeViolation();" class="btn btn-info btn-sm p-2">Analyze Violation</a>
            </div>
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
                <div class="col-md-2">
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
                <div class="col-md-3">
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
                <div class="col-md-3">
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
                            <th>Chocks Off / On</th>
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
    <div class="modal fade" id="manageModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('app.flying-details.process.save')}}" method="POST" id="roleForm" class="">
                    @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="text" class="form-control dates" name="dates" placeholder="Enter Date" autocomplete="off"/>
                        <input type="hidden" name="types" id="types"  />
                        <div class="invalid-feedback"></div>
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
                        url: "{{route('app.flying-details.list')}}",
                        type: "post",
                        data: {
                            "_token": "{{ csrf_token() }}",from_date:$('#from_date').val(),to_date:$('#to_date').val(),aircraft:$('#aircraft').val(),pilot:$('#pilot').val(),flying_type:$('#flying_type').val()
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
            
            function unprocess()
            {   
                $('#modalLabel').html('Unprocess');
                $('#types').val('unprocess');
                $('#roleForm').find('.is-invalid').removeClass('is-invalid');
                $('#roleForm').find('.invalid-feedback').hide();
                $('#roleForm')[0].reset();
                $('#manageModal').modal('show') 
            }
            
            function process()
            {
                $('#modalLabel').html('Process');
                $('#types').val('process');
                $('#roleForm').find('.is-invalid').removeClass('is-invalid');
                $('#roleForm').find('.invalid-feedback').hide();
                $('#roleForm')[0].reset();
                $('#manageModal').modal('show') 
            }
            
            $('#roleForm').submit(function(e) {
                e.preventDefault();
                $('#roleForm').find('.invalid-feedback').hide();
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    dataType: 'json',
                    data: $(this).serialize(),
                    beforeSend: function() {
                        showLoader(); 
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#roleForm')[0].reset();
                            $('#manageModal').modal('hide');
                            dataList();
                        } else {
                            $.each(response.message, function(fieldName, field) {
                                $('#roleForm').find('[name=' + fieldName + ']').addClass('is-invalid');
                                $('#roleForm').find('[name=' + fieldName + ']').after('<div class="invalid-feedback">' + field + '</div>');
                            })
                        }
                    },
                    complete: function() {
                        hideLoader();
                    }
                })
            })
            
            function analyzeViolation()
            {
                $.ajax({
                    url: "{{route('app.flying-details.analyze.violation')}}",
                    method: 'post',
                    dataType: 'json',
                    data: {"_token": "{{ csrf_token() }}"},
                    beforeSend: function() {
                       showLoader(); 
                    },
                    success: function(response) {
                        console.log(response);
                    },
                    complete: function() {
                        hideLoader();
                    }
                })
            }
             
        </script>
    </x-slot>
</x-app-layout>