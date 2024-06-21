<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">LOAD & TRIM</li>
            <li class="breadcrumb-item active">ADD</li>
        </ul>
    </x-slot>
    <x-slot name="css">
        <link href="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <!-- <link href="{{asset('assets/plugins/select-picker/dist/picker.min.css')}}" rel="stylesheet" /> -->
    </x-slot>
    <!-- Errors -->
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Add Load & Trim</h3>
            <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
        </div>
        <div class="card-body">
            <form action="{{route('app.load.trim.store')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="row m-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="aircraft" class="form-label">Aircraft<span class="text-danger">*</span></label>
                            <select class="form-control" id="aircraft" name="aircraft" required>
                                <option value="">Select</option>
                                @foreach($aircrafts as $aircraft)
                                <option value="{{$aircraft->id}}">{{$aircraft->call_sign}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dates" class="form-label">Date<span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker" id="dates" name="dates" required placeholder="Please Select Date" value="{{old('dates')}}">
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
                            <label for="remark" class="form-label">Remark<span class="text-danger">*</span></label>
                            <textarea class="form-control" id="remark" name="remark" placeholder="Please enter remark" >{{old('remark')}}</textarea>
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




    <x-slot name="js">
        <script src="{{asset('assets/plugins/moment/moment.js')}}"></script>
        <script src="{{asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <!-- <script src="{{asset('assets/plugins/select-picker/dist/picker.min.js')}}"></script> -->
        <script>
            $('.datepicker').datepicker({});
        </script>
    </x-slot>
</x-app-layout>