<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('app.dashboard') }}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">RECEIPT BILL FLYING LOGS</li>
        </ul>
    </x-slot>
    <x-slot name="css">
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" />
        {{--  <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}"
            rel="stylesheet" />
        <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
            rel="stylesheet" />
        <link href="{{ asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}"
            rel="stylesheet" />  --}}
    </x-slot>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Validation -->
    <x-errors class="mb-4" />
    <x-success class="mb-4" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Receipt Bill Flying Logs</h3>
            <a href="{{ route('app.receive.bill',$data->receives_id) }}" class="btn btn-primary btn-sm p-2">Back</a>
        </div>
        <div class="card-body">
            <form id="manageGetForm" action="{{ route('app.receive.getFlyingLogs') }}" method="POST">
                @csrf
                <input type="hidden" id="bill_id" name="bill_id" value="{{ $data->id }}">
                <input type="hidden" id="receives_id" name="receives_id" value="{{ $data->receives_id }}">
                <div class="row mb-5 justify-content-evenly">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="aircraft" class="form-label">Aircraft</label>
                            <select class="form-control filters" id="aircraft" name="aircraft">
                                <option value="">Select</option>
                                @foreach($aircrafts as $key=> $aircraft)
                                <option value="{{$aircraft->id}}">{{$aircraft->call_sign}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="text" class="form-control dates" id="from_date" name="from_date"
                                placeholder="DD-MM-YYYY" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="text" class="form-control dates" id="to_date" name="to_date"
                                placeholder="DD-MM-YYYY" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-info mt-4 py-2 px-4">Search</button>
                        </div>
                    </div>
                </div>
            </form>


            <div class="table-responsive border-top">
                <form id="manageForm" action="{{ route('app.receive.FlyingLogsStore') }}" method="POST">
                    @csrf
                    <input type="hidden" id="bill_id" name="bill_id" value="{{ $data->id }}">
                    <input type="hidden" id="receives_id" name="receives_id" value="{{ $data->receives_id }}">
                    <input type="hidden" name="edit_id" id="edit_id" value="{{ $data->id }}">
                    <table class="table text-nowrap w-100">
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
                                @php
                                    // Decode if string and ensure it is an array, otherwise set as an empty array.
                                    $expenseTypes = is_string($data->expenses_type) ? json_decode($data->expenses_type, true) : (is_array($data->expenses_type) ? $data->expenses_type : []);
                                @endphp
                                @foreach ($expenseTypes as $type)
                                    <th>{{ getMasterName($type) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-12 text-center" id="btn">

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <x-slot name="js">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js">
        </script>
        {{--  <script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>  --}}
        <script>
            $(".dates").datetimepicker({
                timepicker: false,
                format: 'd-m-Y',
                formatDate: 'Y/m/d',
                autoclose: true,
                clearBtn: true,
                todayButton: true,
                // maxDate: new Date(new Date().getTime() + 5 * 24 * 60 * 60 * 1000),
                onSelectDate: function(ct) {}
            });

            $('#manageGetForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('tbody').html(response.html);
                            $('#btn').html(response.btn);
                        } else {
                            info(response.message);
                        }
                    }
                });
            });


        </script>
    </x-slot>
</x-app-layout>
