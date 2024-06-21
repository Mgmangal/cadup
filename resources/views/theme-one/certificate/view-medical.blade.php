<div class="row mb-2 bg-light">
    <div class="col-md-6">
        Crew : <b id="crew_name">{{$data->user->salutation}} {{$data->user->name}}</b>
    </div>
    <div class="col-md-6">
        Medical : <b id="medical"></b>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="medical_done_on">Medical Done On</label>
            <input type="text" class="form-control datepicker" name="medical_done_on" id="medical_done_on"
                value="{{$data->medical_done_on}}"/>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="medical_done_at">Medical Done At</label>
            <input type="text" class="form-control" name="medical_done_at" id="medical_done_at"
                value="{{$data->medical_done_at}}" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="medical_result">Medical Result</label>
            <select name="medical_result" id="medical_result" class="form-control">
                <option value="">Select</option>
                <option {{$data->medical_result == 'Fit' ? 'selected' : ''}} value="Fit">Fit</option>
                <option {{$data->medical_result == 'Temporary Unfit' ? 'selected' : ''}} value="Temporary Unfit">Temporary Unfit</option>
                <option {{$data->medical_result == 'Unfit' ? 'selected' : ''}} value="Unfit">Unfit</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="planned_renewal_date">Planned Renewal Date</label>
            <input type="text" class="form-control" name="planned_renewal_date" id="planned_renewal_date" value="{{$data->planned_renewal_date}}"/>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="extended_date">Extended Date</label>
            <input type="text" class="form-control" name="extended_date"
                id="extended_date" value="{{$data->extended_date}}"/>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="mandatory_medical_center_count">Mandatory Medical Center Count</label>
            <input type="text" class="form-control" name="mandatory_medical_center_count"
                id="mandatory_medical_center_count" value="{{$data->mandatory_medical_center_count}}"/>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="next_due">Next Due</label>
            <input type="text" class="form-control" name="next_due"
                id="next_due"  value="{{$data->next_due}}"/>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="">Select</option>
                <option {{$data->status == 'Active' ? 'selected' : ''}} value="Active">Active</option>
                <option {{$data->status == 'Suspended' ? 'selected' : ''}} value="Suspended">Suspended</option>
                <option {{$data->status == 'Revoked' ? 'selected' : ''}} value="Revoked">Revoked</option>
            </select>
        </div>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="remarks">Remarks</label>
    <textarea class="form-control" name="remarks" id="remarks">{{$data->remarks}}</textarea>
</div>
<div class="mb-3">
    <label class="form-label" for="limitations">Limitations</label>
    <textarea class="form-control" name="limitations" id="limitations">{{$data->limitations}}</textarea>
</div>
<div class="mb-3">
    <label class="form-label" for="documnets">Upload Document</label>
    <div class="d-flex">
        <input type="file" class="form-control" name="documnets" id="documnets">
        <a href="{{asset('uploads/pilot_certificate/'.$data->documents)}}" target="_blank"
            class="btn btn-primary ms-3">View</a>
    </div>
</div>