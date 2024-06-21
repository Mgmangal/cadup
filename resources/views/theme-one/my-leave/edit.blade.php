@extends('theme-one.layouts.app',['title' => 'LEAVE','sub_title'=>'Apply LEAVE'])
@section('css')
<link href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">

@endsection
@section('content')
<div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Update Leave</h3>
            <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
        </div>
        <div class="card-body">
            <form action="{{route('user.my.leave.update',$data->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row m-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="master_id" class="form-label">Leave Type<span class="text-danger">*</span></label>
                            <select class="form-control" id="master_id" name="master_id" required>
                                <option value="">Select</option>
                                @foreach($leave_types as $leave_type)
                                <option {{$data->master_id==$leave_type->master_id?'selected':''}} value="{{$leave_type->master_id}}">{{$leave_type->master->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="leave_dates" class="form-label">Date<span class="text-danger">*</span></label>
                            <input type="text" class="form-control daterange" id="leave_dates" name="leave_dates" required placeholder="Please Select Date" value="{{old('leave_dates',$data->leave_dates)}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="documnets" class="form-label">Doc</label>
                            <input type="file" class="form-control" id="documnets" name="documnets" >
                        </div>
                    </div>
                </div>
                <div class="row m-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="reason" class="form-label">Reason<span class="text-danger">*</span></label>
                            <textarea class="form-control" id="reason" name="reason" placeholder="Please enter reason" >{{old('reason',$data->reason)}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row m-3 text-center">
                    <div class="col-md-12 ">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection

@section('js')
<script src="{{asset('assets/plugins/moment/moment.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- <script src="{{asset('assets/plugins/select-picker/dist/picker.min.js')}}"></script> -->
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
});
</script>

@endsection
