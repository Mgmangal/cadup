@extends('theme-one.layouts.app', ['title' => 'User', 'sub_title' => 'Leave Edit'])
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">User Leave Edit</h3>
        <a href="{{ route('user.leave') }}" class="btn btn-primary text-white">Go Back</a>
    </div>
    <div class="card-body">
        <form action="{{route('user.leave.update',$data->id)}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row m-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="user_id" class="form-label">Crew<span class="text-danger">*</span></label>
                        <select class="form-control" id="user_id" name="user_id">
                            <option value="">Select</option>
                            @foreach($users as $user)
                            <option {{$data->user_id==$user->id ? 'selected' : ''}} value="{{$user->id}}">{{$user->salutation}} {{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="master_id" class="form-label">Leave Type<span class="text-danger">*</span></label>
                        <select class="form-control" id="master_id" name="master_id">
                            <option value="">Select</option>
                            @foreach($leave_types as $leave_type)
                            <option {{$data->master_id==$leave_type->id ? 'selected' : ''}} value="{{$leave_type->id}}">{{$leave_type->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="documnets" class="form-label">Doc 1</label>
                        <input type="file" class="form-control" id="documnets" name="documnets" >
                        @if(!empty($data->documnets))
                        <a href="{{asset('uploads/leave/'.$data->documnets)}}" target="_blank">View</a>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="other_doc" class="form-label">Doc 2</label>
                        <input type="file" class="form-control" id="other_doc" name="other_doc" >
                        @if(!empty($data->other_doc))
                        <a href="{{asset('uploads/leave/'.$data->other_doc)}}" target="_blank">View</a>
                        @endif
                    </div>
                </div>

            </div>
            <div class="row m-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="leave_dates" class="form-label">Date<span class="text-danger">*</span></label>
                        <input type="text" class="form-control daterange" id="leave_dates" name="leave_dates" placeholder="Please Select Date" value="{{$data->leave_dates}}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="status" class="form-label">Status<span class="text-danger">*</span></label>
                        <select class="form-control" id="status" name="status">
                            <option value="">Select</option>
                            <option {{$data->status=='inprocess' ? 'selected' : ''}} value="inprocess">Inprocess</option>
                            <option {{$data->status=='approved' ? 'selected' : ''}} value="approved">Approved</option>
                            <option {{$data->status=='rejected' ? 'selected' : ''}} value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="no_of_days" class="form-label">Leave Duration</label>
                        <input type="number" class="form-control" name="no_of_days" id="no_of_days" value="{{$data->no_of_days}}">
                    </div>
                </div>

            </div>
             <div class="row m-2">
                <div class="col-md-12">
                    <div class="form-group">
                        <lable>Remark</lable>
                        <textarea class="form-control" name="remark">{{$data->remark}}</textarea>
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
</script>
@endsection

