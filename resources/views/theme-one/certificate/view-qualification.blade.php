<div class="row mb-2 bg-light">
    <div class="col-md-6">
        Crew : <b id="crew_name">{{$data->user->salutation}} {{$data->user->name}}</b>
    </div>
    <div class="col-md-6">
        Qualification : <b id="qualification"></b>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="renewed_on">Renewed On</label>
            <input type="text" class="form-control datepicker" name="renewed_on" id="renewed_on" value="{{$data->renewed_on}}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="number">Number</label>
            <input type="text" class="form-control" name="number" id="number" value="{{$data->number}}" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="plannedRenewalDate">Planned Renewal Date</label>
            <input type="text" class="form-control" name="plannedRenewalDate"
                id="planned_renewal_date" value="{{$data->planned_renewal_date}}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="issued_on">Issued On</label>
            <input type="text" class="form-control" name="issued_on" id="issued_on" value="{{$data->issued_on}}" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="extended_date">Extended Date</label>
            <input type="text" class="form-control" name="extended_date" id="extended_date" value="{{$data->extended_date}}"/>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="renewal_office">Renewal Office</label>
            <input type="text" class="form-control" name="renewal_office" id="renewal_office" value="{{$data->renewal_office}}" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label" for="next_due">Next Due</label>
            <input type="text" class="form-control" name="next_due" id="next_due" value="{{$data->next_due}}"/>
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
    <label class="form-label" for="documnets">Upload Document</label>
    <div class="d-flex">
        <input type="file" class="form-control" name="documnets" id="documnets">
        <a href="{{asset('uploads/pilot_certificate/'.$data->documents)}}" target="_blank"
            class="btn btn-primary ms-3">View</a>
    </div>
</div>