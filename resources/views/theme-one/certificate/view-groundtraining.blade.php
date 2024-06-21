<div class="row mb-2 bg-light">
    <div class="col-md-6">
        Crew : <b id="crew_name">{{$data->user->salutation}} {{$data->user->name}}</b>
    </div>
    <div class="col-md-6">
        Ground Training : <b id="training"></b>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="renewed_on">Renewed On</label>
            <input type="text" class="form-control" name="renewed_on" id="renewed_on" value="{{$data->renewed_on}}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="seat_occupied">Seat Occupied</label>
            <select name="seat_occupied" id="seat_occupied" class="form-control">
                <option value="">Select</option>
                <option {{ $data->seat_occupied == 'LHS' ? 'selected' : '' }} value="LHS">LHS</option>
                <option {{ $data->seat_occupied == 'RHS' ? 'selected' : '' }} value="RHS">RHS</option>
                <option {{ $data->seat_occupied == 'LHS/RHS' ? 'selected' : '' }} value="LHS/RHS">LHS/RHS</option>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="planned_renewal_date">Planned Renewal Date</label>
            <input type="text" class="form-control" id="planned_renewal_date" value="{{$data->planned_renewal_date}}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="examiner">Examiner</label>
            <input type="text" class="form-control" name="examiner" id="examiner" value="{{$data->examiner}}" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="extended_date">Extended Date</label>
            <input type="text" class="form-control" name="extended_date" id="extended_date"
                value="{{$data->extended_date}}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="day_night">Day/Night</label>
            <select name="day_night" id="day_night" class="form-control">
                <option value="">Select</option>
                <option {{ $data->day_night == 'Day' ? 'selected' : '' }} value="Day">Day</option>
                <option {{ $data->day_night == 'Night' ? 'selected' : '' }} value="Night">Night</option>
            </select>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="next_due">Next Due</label>
            <input type="text" class="form-control" name="next_due" id="next_due" value="{{$data->next_due}}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="test_on">Test On</label>
            <select name="test_on" id="test_on" class="form-control">
                <option value="">Select</option>
                <option {{ $data->test_on == 'Aeroplane' ? 'selected' : '' }} value="Aeroplane">Aeroplane</option>
                <option {{ $data->test_on == 'Helicopter' ? 'selected' : '' }} value="Helicopter">Helicopter</option>
                <option {{ $data->test_on == 'Simulator' ? 'selected' : '' }} value="Simulator">Simulator</option>
                <option {{ $data->test_on == 'ground_training' ? 'selected' : '' }} value="ground_training">Ground
                    Training</option>

            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="">Select</option>
                <option {{ $data->status == 'Active' ? 'selected' : '' }} value="Active">Active</option>
                <option {{ $data->status == 'Suspended' ? 'selected' : '' }} value="Suspended">Suspended</option>
                <option {{ $data->status == 'Revoked' ? 'selected' : '' }} value="Revoked">Revoked</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="simulator_level">Simulator Level</label>
            <select name="simulator_level" id="simulator_level" class="form-control">
                <option value="">Select</option>
                <option {{ $data->simulator_level == 'Level A' ? 'selected' : '' }} value="Level A">Level A</option>
                <option {{ $data->simulator_level == 'Level B' ? 'selected' : '' }} value="Level B">Level B</option>
                <option {{ $data->simulator_level == 'Level C' ? 'selected' : '' }} value="Level C">Level C</option>
                <option {{ $data->simulator_level == 'Level D' ? 'selected' : '' }} value="Level D">Level D</option>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="mb-3">
            <label class="form-label" for="remarks">Remarks</label>
            <textarea class="form-control" name="remarks" id="remarks">{{$data->remarks}}</textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="aircroft_registration">Aircraft Registration</label>
            <input type="text" class="form-control" name="aircroft_registration" id="aircroft_registration"
                value="{{$data->aircroft_registration}}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="aircroft_type">Aircraft Type</label>
            <input type="text" class="form-control" name="aircroft_type" id="aircroft_type"
                value="{{$data->aircroft_type}}" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="aircroft_model">Aircraft Model</label>
            <input type="text" class="form-control" name="aircroft_model" id="aircroft_model"
                value="{{$data->aircroft_model}}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="P1_hours">P1 Hours</label>
            <input type="text" class="form-control" name="P1_hours" id="P1_hours" value="{{$data->P1_hours}}" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="P2_hours">P2 Hours</label>
            <input type="text" class="form-control" name="P2_hours" id="P2_hours" value="{{$data->P2_hours}}" / />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="renewal_office">Renewal Office</label>
            <input type="text" class="form-control" name="renewal_office" id="renewal_office"
                value="{{$data->renewal_office}}" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="place_of_test">Place of Test</label>
            <input type="text" class="form-control" name="place_of_test" id="place_of_test"
                value="{{$data->place_of_test}}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="approach_details">Approach Details</label>
            <input type="text" class="form-control" name="approach_details" id="approach_details"
                value="{{$data->approach_details}}" />
        </div>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="documents">Upload Document</label>
    <div class="d-flex">
        <input type="file" class="form-control" name="documnets" id="documnets">
        <a href="{{asset('uploads/pilot_certificate/'.$data->documents)}}" target="_blank"
            class="btn btn-primary ms-3">View</a>
    </div>
</div>