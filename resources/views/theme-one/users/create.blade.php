@extends('theme-one.layouts.app', ['title' => 'Employees', 'sub_title' => $sub_title])
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Employees {{ $sub_title }}</h3>
            @if (auth()->user()->can('Employee Add'))
                <a href="{{ route('user.users') }}" class="btn btn-primary text-white">Go Back</a>
            @endif
        </div>
        {{-- @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif --}}
        <div class="card-body">
            <form action="{{route('user.users.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row m-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="emp_id" class="form-label">Emp ID<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="emp_id" name="emp_id" placeholder="Please Enter Emp ID" value="{{old('emp_id')}}">
                            @error('emp_id')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="salutation" class="form-label">Mr./Ms.<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="salutation" name="salutation" placeholder="Please Enter Salutation" value="{{old('salutation')}}">
                            @error('salutation')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Please Enter Name" value="{{old('name')}}">
                            @error('name')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row m-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="Please Enter Email" value="{{old('email')}}">
                            @error('email')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Please Enter Phone" value="{{old('phone')}}">
                            @error('phone')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="designation" class="form-label">Designation</label>
                            <select class="form-control" id="designation" name="designation" >
                                <option value="">Select Designation</option>
                                @foreach ($designations as $designation)
                                <option value="{{$designation->id}}">{{$designation->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="row m-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="department" class="form-label">Department</label>
                            <select class="form-control" id="department" name="department[]" multiple onchange="getSection();">
                                @foreach ($departments as $department)
                                <option value="{{$department->id}}">{{$department->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="section" class="form-label">Section</label>
                            <select class="form-control" id="section" name="section[]" multiple onchange="getJobFunction();">

                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="jobfunction" class="form-label">Job Function</label>
                            <select class="form-control" id="jobfunction" name="jobfunction[]" multiple>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row m-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="address" class="form-label">Address<span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                            @error('address')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 m-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_adt" name="is_adt" checked>
                            <label class="form-check-label" for="is_adt">Aplicable For Alcohol Detection Test</label>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('#department').select2({
            width: 'resolve',
            placeholder: 'Select Department'
        });
        $('#designation').select2({
            width: 'resolve',
            placeholder: 'Select Designation'
        });
        $('#section').select2({
            width: 'resolve',
            placeholder: 'Select Section'
        });
        $('#jobfunction').select2({
            width: 'resolve',
            placeholder: 'Select Job Function'
        });
        $('#logo').change(function() {
            // FileReader function for read the file.
            let reader = new FileReader();
            var base64;
            reader.readAsDataURL(this.files[0]);

            //Read File
            let filereader = new FileReader();
            var selectedFile = this.files[0];

            // Onload of file read the file content
            filereader.onload = function(fileLoadedEvent) {
                base64 = fileLoadedEvent.target.result;
                console.log(base64);
                $("#pimage").attr('src', base64);
            };

            filereader.readAsDataURL(selectedFile);
        });

        function getSection() {
            $.ajax({
                url: "{{route('app.users.getSection')}}",
                type: "POST",
                data: {
                    _token: "{{csrf_token()}}",
                    id: $('#department').val(),
                    user_id: ''
                },
                success: function(data) {
                    $('#section').html(data.data);
                    $('#section').select2({
                        width: 'resolve',
                        placeholder: 'Select section'
                    });
                }
            })
        }

        function getJobFunction() {
            $.ajax({
                url: "{{route('app.users.getJobFunction')}}",
                type: "POST",
                data: {
                    _token: "{{csrf_token()}}",
                    id: $('#section').val(),
                    user_id: ''
                },
                success: function(data) {
                    $('#jobfunction').html(data.data);
                    $('#jobfunction').select2({
                        width: 'resolve',
                        placeholder: 'Select Job Function'
                    });
                }
            })
        }
    </script>
@endsection
