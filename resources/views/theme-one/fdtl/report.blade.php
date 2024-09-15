@extends('theme-one.layouts.app',['title' => 'FDTL','sub_title'=>'REPORT'])
@section('css')

@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{route('user.fdtl.getReport')}}" method="post" id="reportForm">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="mt-4"><b>Select Report Duration</b></label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Poilot</label>
                            <select name="user_id" id="user_id" class="form-control"></select>
                                <option value="">Select</option>
                                @foreach($users as $key=> $user)
                                    <option value="{{$user->id}}">{{$user->salutation.' '.$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>From Date</label>
                            <input type="date" name="from_date" id="from_date" class="form-control" value="{{request()->from_date}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>To Date</label>
                            <input type="date" name="to_date" id="to_date" class="form-control" value="{{request()->to_date}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group m-3">
                            <button type="submit" class="btn btn-primary btn-sm btn-block m-2">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">FDTL report : </h3>
            <div>
            <!-- <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a> -->
            <a href="javascript:void(0);" onclick="printSectedDiv();" class="btn btn-info btn-sm p-2">Print</a>
            </div>
        </div>
        <div class="card-body p-1 table-responsive" id="reportTable">

        </div>
    </div>
@endsection

@section('js')

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.min.js"></script>
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
        <script>
            $('#reportForm').submit(function(e){
                e.preventDefault();
                let form = $(this);
                let url = form.attr('action');
                let method = form.attr('method');
                let data = form.serialize();
                $.ajax({
                    url:url,
                    type:method,
                    data:data,
                    beforeSend:function(data){
                         $('#reportTable').html('<div style="text-align: center;width: 100%;height: 100%;">'
                            +'<img src="' + "{{ asset('assets/img/orange-loader.gif') }}" + '" style="width: 100px;">'
                            +'</div>');
                    },
                    success:function(data){
                        $('#reportTable').html(data);
                    },
                    error:function(err){
                        console.log(err);
                    },
                    complete:function(err){
                        console.log(err);
                    }
                })

            });

            function printSectedDiv()
            {
                let user_id = $('#user_id').val();
                if(user_id == '')
                {
                    alert('Please select user');
                    return false;
                }
                let from_date = $('#from_date').val();
                if(from_date == '')
                {
                    alert('Please select from date');
                    return false;
                }
                let to_date = $('#to_date').val();
                if(to_date == '')
                {
                    alert('Please select to date');
                    return false;
                }
                window.open("{{url('user/fdtl/report/print')}}"+'/'+user_id+'/'+from_date+'/'+to_date,'printDiv','height=900,width=900');
            }
        </script>
@endsection
