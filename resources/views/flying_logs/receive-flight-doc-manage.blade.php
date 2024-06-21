<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item"><a href="{{route('app.air-crafts')}}">RECEIVE FLIGHT DOC</a></li>
            <li class="breadcrumb-item active">ADD</li>
        </ul>
    </x-slot>
    <x-slot name="css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <!-- <link href="{{asset('assets/plugins/select-picker/dist/picker.min.css')}}" rel="stylesheet" /> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" />
        <link href="{{asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}" rel="stylesheet" />
    </x-slot>
    <!-- Errors -->
    <x-errors class="mb-4" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Receive Flight Doc Add</h3>
            <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
        </div>
        <div class="card-body">
            <form action="{{route('app.flying-details.receiveFlightDoc.store')}}" method="POST" enctype="multipart/form-data" id="manageForm" autocomplete="off">
                @csrf
                <div class="row m-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="bunch_no" class="form-label">Bunch No.<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="bunch_no" name="bunch_no" placeholder="Please Enter Bunch No." value="{{date('d-m-Y')}}-{{!empty($data)?$data->bunch_no:(!empty($last_data)?$last_data->id+1:1)}}" required>
                            <input type="hidden" class="form-control" id="edit_id" name="edit_id" value="{{!empty($data)?$data->id:''}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="aircraft_type" class="form-label">Date<span class="text-danger">*</span></label>
                            <input type="text" class="form-control dates" id="dates" name="dates" placeholder="Please Enter Date" value="{{!empty($data)?$data->dates:''}}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="day_officers" class="form-label">Day Officers</label>
                            <select class="form-control" id="day_officers" name="day_officers">
                                <option value="">Select</option>
                                @foreach($passengers as $passenger)
                                    <option {{!empty($data)&&$data->day_officers==$passenger->id?'selected':''}} value="{{$passenger->id}}">{{$passenger->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="remark" class="form-label">Remark</label>
                            <textarea class="form-control" id="remark" name="remark" placeholder="Please Enter Remark">{{!empty($data)?$data->remark:''}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
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
                            <div class="col-md-4">
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
                        </div>
                        <table id="datatableDefault" class="table" style="font-size: 10px;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Aircraft</th>
                                    <th>From/To</th>
                                    <th>Chocks Off / On</th>
                                    <th>Crew</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table" style="font-size: 10px;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Flight Doc</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $doc=!empty($data->documents)?$data->documents:[];  @endphp
                                @foreach($post_flight_doc as $post_flight)
                                <tr>
                                    <th><input type="checkbox" name="doc_id[{{$post_flight->id}}]" {{array_key_exists($post_flight->id, $doc)?'checked':''}} value="{{$post_flight->id}}"></th>
                                    <th>{{$post_flight->name}}</th>
                                    <th>
                                        <input type="file" name="document[{{$post_flight->id}}]">
                                        <input type="hidden" name="document_dc[{{$post_flight->id}}]" value="">
                                        @if(!empty($doc[$post_flight->id]))
                                            <input type="hidden" name="document_dc[{{$post_flight->id}}]" value="{{$doc[$post_flight->id]}}">
                                            <a href="{{asset('uploads/flight_doc/'.$doc[$post_flight->id])}}" target="blank" class="btn btn-sm btn-success">View</a>
                                        @endif
                                    </th>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row m-3 text-center">
                            <div class="col-md-12 ">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                
               

                
            </form>
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
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <!-- <script src="{{asset('assets/plugins/select-picker/dist/picker.min.js')}}"></script> -->
        <script>
            $('.selct2').select2({  
                tags: true,
                tokenSeparators: [',', ' '],
                placeholder: "Select Pilots",
                allowClear: true,
            });
            $('#manageForm').submit(function(e) {
                e.preventDefault();
                $('#manageForm').find('.invalid-feedback').hide();
                $('#manageForm').find('.is-invalid').removeClass('is-invalid');
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    dataType: 'json',
                    processData: false,  // Important for file data
                    contentType: false,  // Important for file data
                    data: new FormData(this),
                    success: function(response) {
                        if (response.success) {
                            success(response.message);
                            window.location.href = "{{ route('app.flying-details.receiveFlightDoc') }}";
                        } else {
                            $.each(response.error, function(fieldName, field) {
                                $('#manageForm').find('[name=' + fieldName + ']').addClass('is-invalid');
                                $('#manageForm').find('[name=' + fieldName + ']').after('<div class="invalid-feedback">' + field + '</div>');
                            })
                        }

                    }
                })
            })

            function validateDate() {
                var start_date = $("#operation_start_date").val();
                var end_date = $("#operation_end_date").val();
                // console.log(end_date.length, start_date.length);
                // console.log(Date.parse(toJSDate(start_date)), Date.parse(toJSDate(end_date)));
                if (start_date.length != 0 && end_date.length != 0 && Date.parse(toJSDate(start_date)) >= Date.parse(toJSDate(end_date))) {
                    // console.log(Date.parse(toJSDate(start_date)), Date.parse(toJSDate(end_date)));
                    $("#operation_end_date").val("");
                    warning("Operation End Date should be Greater than Operation Start Date.");
                }
            }

            function toJSDate(dateTime) {
                var date = dateTime.split("/");
                //(year, month, day, hours, minutes, seconds, milliseconds)
                //subtract 1 from month because Jan is 0 and Dec is 11
                return new Date(date[2], (date[1] - 1), date[0]);
            }

            $(".dates").datepicker({
                //defaultDate: new Date(),
                format: 'yyyy-mm-dd',
                autoclose: true,
                orientation: 'bottom auto',
            });
            function dataList() {
                $('#datatableDefault').DataTable().destroy();
                $('#datatableDefault').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    ordering: true,
                    searching: false,
                    lengthMenu: [20, 50, 100, 200, 500, 1000, 2000, 5000, 10000],
                    responsive: true,
                     order: [[0, 'desc']],
                    fixedColumns: true,
                    ajax: {
                        url: "{{route('app.flying-details.receiveFlight.list')}}",
                        type: "post",
                        data: {
                            "_token": "{{ csrf_token() }}",edit_id:$('#edit_id').val(),from_date:$('#from_date').val(),to_date:$('#to_date').val(),aircraft:$('#aircraft').val(),pilot:$('#pilot').val()
                        },
                    },
                    fnRowCallback: function( nRow, aData, iDisplayIndex ) { 
                        var oSettings = this.fnSettings ();
                    },
                    "initComplete": function() {
                            
                    }
                });
            }
            dataList();
            
            $('.filters').on('change',function(){
                dataList();
            });
            
        </script>
    </x-slot>
</x-app-layout>