@extends('theme-one.layouts.app', ['title' => 'User', 'sub_title' => 'Leave Add'])
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">User Leave Add</h3>
        <a href="{{ route('user.leave') }}" class="btn btn-primary text-white">Go Back</a>
    </div>
    <div class="card-body">
        <form action="{{route('user.leave.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row m-2">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="user_id" class="form-label">Crew<span class="text-danger">*</span></label>
                        <select class="form-control" id="user_id" name="user_id" required
                            onchange="checkValidLeave();">
                            <option value="">Select</option>
                            @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->salutation}} {{$user->name}}</option>
                            @endforeach
                        </select>
                        <span class="text-danger user_error"></span>
                    </div>

                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="master_id" class="form-label">Leave Type<span
                                class="text-danger">*</span></label>
                        <select class="form-control" id="master_id" name="master_id" required>
                            <option value="">Select</option>
                            @foreach($leave_types as $leave_type)
                            <option value="{{$leave_type->id}}">{{$leave_type->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="documnets" class="form-label">Doc 1</label>
                        <input type="file" class="form-control" id="documnets" name="documnets">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="other_doc" class="form-label">Doc 2</label>
                        <input type="file" class="form-control" id="other_doc" name="other_doc">
                    </div>
                </div>

            </div>
            <div class="row m-2">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="leave_dates" class="form-label">Date<span class="text-danger">*</span></label>
                        <input type="text" class="form-control daterange" id="leave_dates" name="leave_dates"
                            onchange="checkValidLeave();" required placeholder="Please Select Date"
                            value="{{old('leave_dates')}}">
                            <span class="text-danger date_error"></span>
                    </div>

                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="status" class="form-label">Status<span class="text-danger">*</span></label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="">Select</option>
                            <option value="inprocess">Inprocess</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="no_of_days" class="form-label">Leave Duration</label>
                        <input type="number" class="form-control" name="no_of_days" id="no_of_days">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mt-4">
                        <div class="row">
                            <div class="col-md-6">
                                Total Leave: <b><span id="total_leave">0</span></b>
                            </div>
                            <div class="col-md-6">
                                Apply Leave: <b><span id="apply_leave">0</span></b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                Consumed Leave:<b><span id="consumed_leave">0</span></b>
                            </div>
                            <div class="col-md-6">
                                Remaining Leave: <b><span id="remaining_leave">0</span></b>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row m-2">
                <div class="col-md-12">
                    <div class="form-group">
                        <lable>Remark</lable>
                        <textarea class="form-control" name="remark"></textarea>
                    </div>
                </div>
            </div>


            <div class="row m-2 text-center">
                <div class="col-md-12 ">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            <div class="row m-2">
                <div class="col-md-12" id="leave_status">
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('assets/plugins/moment/moment.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
 <script>
    $('.daterange').daterangepicker({
        // opens: 'right',
        format: 'MM/DD/YYYY',
        separator: ' to ',
        // startDate: moment().subtract('days', 29),
        endDate: moment(),
        // minDate: '01/01/2012',
        // maxDate: '12/31/2018',
    }, function(start, end) {
        // $('#daterange input').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        var days = end.diff(start, 'days') + 1;
        $('#no_of_days').val(days);
    });
     function checkValidLeave() {
            $('.text-danger').html('');
            var leave_dates = $('#leave_dates').val();
            var user_id = $('#user_id').val();
            var no_of_days = $('#no_of_days').val();
            // if( user_id == '') {
            //     $('.user_error').html('Please Select User');
            //     return false;
            // }
            // if(leave_dates == '') {
            //     $('.date_error').html('Please Select Date');
            //     return false;
            // }
            $.ajax({
                url: "{{route('user.leave.checkValidLeave')}}",
                type: "POST",
                dataType: 'json',
                data: {
                    leave_dates: leave_dates,
                    user_id: user_id,
                    no_of_days,
                    _token: '{{csrf_token()}}'
                },
                success: function(response) {
                    if (response.status) {
                        $('#leave_status').html(response.message);
                        $('#total_leave').html(response.total_leave);
                        $('#apply_leave').html(response.apply_leave);
                        $('#consumed_leave').html(response.consumed_leave);
                        $('#remaining_leave').html(response.remaining_leave);
                    } else {
                        $('#leave_status').html(response.message);
                    }
                }
            });
        }
    
    </script>
@endsection
