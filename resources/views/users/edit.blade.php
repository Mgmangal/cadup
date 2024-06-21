<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item"><a href="{{route('app.users')}}">EMPLOYEES</a></li>
            <li class="breadcrumb-item active">EDIT</li>
    </x-slot>
    <x-slot name="css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" />
    </x-slot>
    <!-- Errors -->
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Employee Edit</h3>
            <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
        </div>
        <div class="card-body">
            <form action="{{route('app.users.update', $user->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row m-3">
                    <div class="col-md-12 border mb-2 bg-light">
                        <p class="mb-1">Basic Details</p>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="emp_id" class="form-label">Emp ID<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="emp_id" name="emp_id" placeholder="Please Enter Emp ID" value="{{$user->emp_id}}">
                            @error('emp_id')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="salutation" class="form-label">Mr./Ms.<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="salutation" name="salutation" placeholder="Please Enter Salutation" value="{{$user->salutation}}">
                            @error('name')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{$user->name}}">
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
                            <input type="text" class="form-control" id="email" name="email" placeholder="Please Enter Email" value="{{$user->email}}">
                            @error('email')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mobile" class="form-label">Phone<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="mobile" name="mobile" value="{{$user->mobile}}">
                            @error('mobile')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phone" class="form-label">Land Line Number<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{$user->phone}}">
                            @error('phone')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row m-3">
                    <div class="col-md-12 border mb-2 bg-light">
                        <p class="mb-1">Next of Kin (NOK)</p>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="kin_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="kin_name" name="kin_name" value="{{$user->kin_name}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="kin_phone" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="kin_phone" name="kin_phone" value="{{$user->kin_phone}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="kin_relation" class="form-label">Relation</label>
                            <input type="text" class="form-control" id="kin_relation" name="kin_relation" value="{{$user->kin_relation}}">
                        </div>
                    </div>
                </div>
                <div class="row m-3">
                    <div class="col-md-12 border mb-2 bg-light">
                        <p class="mb-1">Unique identity (Aadhaar / PAN)</p>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="aadhaar_number" class="form-label">Aadhaar Number</label>
                            <input type="text" class="form-control" id="aadhaar_number" name="aadhaar_number" value="{{$user->aadhaar_number}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pan_number" class="form-label">PAN Number</label>
                            <input type="text" class="form-control" id="pan_number" name="pan_number" value="{{$user->pan_number }}">
                        </div>
                    </div>
                </div>
                <div class="row m-3">
                    <div class="col-md-12 border mb-2 bg-light">
                        <p class="mb-1">Professional Details</p>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-group">
                            <label for="department" class="form-label">Department</label>
                            <select class="form-control" id="department" name="department[]" multiple onchange="getSection();">
                                @foreach ($departments as $department)
                                <option {{!empty($user->department)&&in_array($department->id,$user->department)?'selected':''}} value="{{$department->id}}">{{$department->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-group">
                            <label for="section" class="form-label">Section</label>
                            <select class="form-control" id="section" name="section[]" multiple onclick="getJobFunction();">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-group">
                            <label for="jobfunction" class="form-label">Job Function</label>
                            <select class="form-control" id="jobfunction" name="jobfunction[]" multiple>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-group">
                            <label for="designation" class="form-label">Designation</label>
                            <select class="form-control" id="designation" name="designation" >
                                @foreach ($designations as $designation)
                                <option {{!empty($user->designation)&&$user->designation==$designation->id?'selected':''}} value="{{$designation->id}}">{{$designation->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-group">
                            <label for="doj" class="form-label">Date Of Joining</label>
                            <input type="text" class="form-control dates" id="doj" name="doj" value="{{$user->doj}}">
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-group">
                            <label for="joining_type" class="form-label">Joining Type </label>
                            <select class="form-control" id="joining_type" name="joining_type">
                                <!--<option value="">Select</option>-->
                                <option {{$user->joining_type=='Contractual'?'selected':''}} value="Contractual">Contractual</option>
                                <option {{$user->joining_type=='Permnent'?'selected':''}} value="Permnent">Permanent</option>
                                <option {{$user->joining_type=='External'?'selected':''}} value="External">External</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-group">
                            <label for="aep_type" class="form-label">AEP Type</label>
                            <select class="form-control" id="aep_type" name="aep_type">
                                <!--<option value="">Select</option>-->
                                <option {{$user->aep_type=='Contractual'?'selected':''}} value="Contractual">Permanent </option>
                                <option {{$user->aep_type=='Permnent'?'selected':''}} value="Permnent">Temporary</option>
                                <option {{$user->aep_type=='External'?'selected':''}} value="External">Not Applicable</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-group">
                            <label for="aep_number" class="form-label">AEP Number</label>
                            <input type="text" class="form-control" id="aep_number" name="aep_number" value="{{$user->aep_number}}">
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-group">
                            <label for="aep_expiring_on" class="form-label">AEP Expiring on</label>
                            <input type="text" class="form-control dates" id="aep_expiring_on" name="aep_expiring_on" value="{{$user->aep_expiring_on}}">
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-group">
                            <label for="police_verification" class="form-label">Police Verification Status</label>
                            <select class="form-control" id="police_verification" name="police_verification">
                                <!--<option value="">Select</option>-->
                                <option value="yes">Verified</option>
                                <option value="no">Pending</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-group">
                            <label for="passport_number" class="form-label">Passport Number</label>
                            <input type="text" class="form-control" id="passport_number" name="passport_number" value="{{$user->passport_number}}">
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-group">
                            <label for="passport_validity" class="form-label">Passport Validity</label>
                            <input type="text" class="form-control dates" id="passport_validity" name="passport_validity" value="{{$user->passport_validity}}">
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-group">
                            <label for="aircraft_authorisation_no" class="form-label">Aircraft Authorisation Number</label>
                            <input type="text" class="form-control" id="aircraft_authorisation_no" name="aircraft_authorisation_no" value="{{$user->aircraft_authorisation_no}}">
                        </div>
                    </div>
                </div>
                <div class="row m-3">
                    <div class="col-md-12 border mb-2 bg-light">
                        <p class="mb-1">Permanent Address</p>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-group">
                            <label for="per_state" class="form-label">State</label>
                            <select name="per_state" id="per_state" class="form-control" onchange="get_city(this, 'per_city');">
                                <option value="">Select</option>
                                @foreach($states as $state)
                                    <option {{$user->per_state==$state->id?'selected':''}} value="{{$state->id}}">{{$state->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-group">
                            <label for="per_city" class="form-label">City</label>
                            <select name="per_city" id="per_city" class="form-control">
                                <option value="">Select</option>
                                @foreach($per_city as $city)
                                    <option {{$user->per_city==$city->id?'selected':''}} value="{{$city->id}}">{{$city->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-group">
                            <label for="per_pincode" class="form-label">Pincode</label>
                            <input type="text" class="form-control" id="per_pincode" name="per_pincode" value="{{$user->per_pincode}}">
                        </div>
                    </div>
                </div>
                <div class="row m-3">
                    <div class="col-md-12 border mb-2 bg-light">
                        <p class="mb-1">Temporary Address</p>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-group">

                            <label for="tem_state" class="form-label">State</label>
                            <select name="tem_state" id="tem_state" class="form-control" onchange="get_city(this, 'tem_city');">
                                <option value="">Select</option>
                                @foreach($states as $state)
                                    <option {{$user->tem_state==$state->id?'selected':''}} value="{{$state->id}}">{{$state->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-group">
                            <label for="tem_city" class="form-label">City</label>
                            <select name="tem_city" id="tem_city" class="form-control">
                                <option value="">Select</option>
                                @foreach($tem_city as $city)
                                    <option {{$user->tem_city==$city->id?'selected':''}} value="{{$city->id}}">{{$city->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 p-2">
                        <div class="form-group">
                            <label for="tem_pincode" class="form-label">Pincode</label>
                            <input type="text" class="form-control" id="tem_pincode" name="tem_pincode" value="{{$user->tem_pincode}}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="homestation" class="form-label">Home Station</label>
                            <textarea class="form-control" id="homestation" name="homestation" rows="2">{{$user->homestation}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row m-3" id="add-education-div">
                    <div class="col-md-12 border mb-2 bg-light">
                        <p class="mb-1">Education Qualification</p>
                    </div>
                    <div class="col-md-12" id="add-education-input-div">
                        @if (!empty($user->qualification))
                            @foreach ($user->qualification as $key => $education)
                                <div class="row">
                                    <div class="col-md-3 p-2">
                                        <div class="form-group">
                                            <label for="degree_1" class="form-label">Degree/ Certificate</label>
                                            <input type="text" class="form-control" id="degree_1"
                                                name="degree[]" value="{{ $education['degree'] }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3 p-2">
                                        <div class="form-group">
                                            <label for="year_1" class="form-label">Passing Year</label>
                                            <input type="text" class="form-control" id="year_1" name="year[]"
                                                value="{{ $education['year'] }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3 p-2">
                                        <div class="form-group">
                                            <label for="institute_1" class="form-label">Institute Name</label>
                                            <input type="text" class="form-control" id="institute_1"
                                                name="institute[]" value="{{ $education['institute'] }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3 p-2 mt-4">
                                        @if ($key == 0)
                                            <button type="button" onclick="handleAddEducationInput()"
                                                class="btn btn-dark btn-sm py-2"><i class="fa fa-plus"></i> Add more
                                                Education</button>
                                        @else
                                            <button type="button" onclick="handleRemoveEducationInput(this)"
                                                class="btn btn-danger btn-sm py-2"><i class="fa fa-trash-alt"></i>
                                                Remove Education</button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row">
                                <div class="col-md-3 p-2">
                                    <div class="form-group">
                                        <label for="degree_1" class="form-label">Degree/ Certificate</label>
                                        <input type="text" class="form-control" id="degree_1" name="degree[]"
                                            value="{{ $user->degree_2 }}">
                                    </div>
                                </div>
                                <div class="col-md-3 p-2">
                                    <div class="form-group">
                                        <label for="year_1" class="form-label">Passing Year</label>
                                        <input type="text" class="form-control" id="year_1" name="year[]"
                                            value="{{ $user->year_1 }}">
                                    </div>
                                </div>
                                <div class="col-md-3 p-2">
                                    <div class="form-group">
                                        <label for="institute_1" class="form-label">Institute Name</label>
                                        <input type="text" class="form-control" id="institute_1"
                                            name="institute[]" value="{{ $user->institute_1 }}">
                                    </div>
                                </div>
                                <div class="col-md-3 p-2 mt-4">
                                    <button type="button" onclick="handleAddEducationInput()"
                                        class="btn btn-dark btn-sm py-2"><i class="fa fa-plus"></i> Add more
                                        Education</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row m-3">
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_adt" name="is_adt"
                                {{ !empty($user->is_adt) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_adt">Applicable For Alcohol Detection Test</label>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            const handleAddEducationInput = () => {
                const educationInputDiv = document.getElementById('add-education-input-div');
                const educationIndex = $('#add-education-input-div .row').length;
                if (!educationInputDiv) {
                    console.error("add-education-input-div not found.");
                    return;
                }
                // Your HTML template for education input fields
                let html = `<div class="col-md-3 p-2">
                                <div class="form-group">
                                    <label for="degree_${educationIndex}" class="form-label">Degree/ Certificate</label>
                                    <input type="text" class="form-control" id="degree_${educationIndex}" name="degree[]">
                                </div>
                            </div>
                            <div class="col-md-3 p-2">
                                <div class="form-group">
                                    <label for="year_${educationIndex}" class="form-label">Passing Year</label>
                                    <input type="text" class="form-control" id="year_${educationIndex}" name="year[]">
                                </div>
                            </div>
                            <div class="col-md-3 p-2">
                                <div class="form-group">
                                    <label for="institute_${educationIndex}" class="form-label">Institute Name</label>
                                    <input type="text" class="form-control" id="institute_${educationIndex}" name="institute[]">
                                </div>
                            </div>
                            <div class="col-md-3 p-2 mt-4">
                                <button type="button" onclick="handleRemoveEducationInput(this)" class="btn btn-danger btn-sm py-2"><i
                                        class="fa fa-trash-alt"></i> Remove Education</button>
                            </div>`;

                const educationRow = document.createElement('div');
                educationRow.className = "row";
                educationRow.innerHTML = html;

                educationInputDiv.appendChild(educationRow);
                educationIndex++;
            };
            const handleRemoveEducationInput = (event) => {
                $(event).parent().parent().remove();
            };
        </script>
        <script>
            $(".dates").datetimepicker({
                timepicker: false,
                format: 'd-m-Y',
                formatDate:'Y/m/d',
                autoclose: true,
                clearBtn: true,
                todayButton: true
            });
            
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

            function getSection()
            {
                $.ajax({
                    url: "{{route('app.users.getSection')}}",
                    type: "POST",
                    data: {
                        _token: "{{csrf_token()}}",
                        id: $('#department').val(),
                        user_id:'{{$user->id}}'
                    },
                    success: function(data) {
                        $('#section').html(data.data);
                        $('#section').select2({ width: 'resolve',placeholder: 'Select Section' });
                        getJobFunction();
                    }
                })
            }
            function getJobFunction()
            {
                $.ajax({
                    url: "{{route('app.users.getJobFunction')}}",
                    type: "POST",
                    data: {
                        _token: "{{csrf_token()}}",
                        id: $('#section').val(),
                        user_id:'{{$user->id}}'
                    },
                    success: function(data) {
                        $('#jobfunction').html(data.data);
                        $('#jobfunction').select2({ width: 'resolve',placeholder: 'Select Job Function' });
                    }
                })
            }

            $(document).ready(function() {
                getSection();
            })
        </script>
    </x-slot>
</x-app-layout>