<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item ">PILOT </li>
            <li class="breadcrumb-item active">LICENSE / CERTIFICATE </li>
        </ul>
    </x-slot>
    <x-slot name="css">
        <link href="{{asset('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
        <link href="{{asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}" rel="stylesheet" />
        <style>
            .datepicker.datepicker-dropdown {
                z-index: 9999 !important
            }
        </style>
    </x-slot>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Validation -->
    <x-errors class="mb-4" />
    <x-success class="mb-4" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">License Training & Medical</h3>
            <div>
                <a href="javascript:void(0);" class="btn btn-success btn-sm p-2">LTM Print Report</a>
                <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h4><b>Crew : {{$user->fullName()}}</b></h4>
                </div>
                <div class="col-md-12 bg-light mb-3">
                    <p class="m-2"><b>Total Licenses: {{$licenses->count()}}</b></p>
                </div>
                <div class="col-md-12">
                    <table id="datatableLicense" class="table text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type Info</th>
                                <th>Applicable</th>
                                <th>Renewed On</th>
                                <th>Next Due</th>
                                <th>Remaining Days</th>
                                <th>Status</th> <!---active,Suspended,Revoked,Expired---->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($licenses as $key => $license)
                            @php
                            $l_data=checkLicense($user->id,$license->id);
                            @endphp
                            <tr>
                                <th>{{$key+1}}</th>
                                <td>{{ucfirst($license->name)}} {!!(!empty($l_data->documents)?('<a href="'.asset('uploads/pilot_certificate/'.$l_data->documents).'"  target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>'):'')!!}</td>
                                <td>
                                    <div class="d-flex">
                                        <div class="form-check m-2">
                                          <input class="form-check-input" type="checkbox" {{!empty($l_data)&&$l_data->is_applicable=='no'?'checked':''}} value="no"  onchange='handleChange(this);' user-id="{{$user->id}}" certificat-type="license" certificate-id="{{$license->id}}" id="defaultCheck_{{$license->id}}">
                                          <label class="form-check-label" for="defaultCheck_{{$license->id}}">N/A</label>
                                        </div>
                                    </div>
                                </td>
                                <th>{{!empty($l_data)&&$l_data->is_applicable!='no'?$l_data->renewed_on:''}}</th>
                                <td>{{!empty($l_data)&&$l_data->is_applicable!='no'?$l_data->next_due:''}}</td>
                                <td>
                                    @php
                                        $btn_color = 'success'; // Default color
                                        if(!empty($l_data)&&$l_data->is_applicable!='no') {
                                            $remaining_days = \Carbon\Carbon::parse(date('Y-m-d'))->diffInDays($l_data->next_due, false);
                                            if($remaining_days > 60) {
                                                $btn_color = 'primary'; // Blue for more than 60 days
                                            } elseif($remaining_days <= 60 && $remaining_days > 7) {
                                                $btn_color = 'warning'; // Orange for <= 60 and > 7 days
                                            } elseif($remaining_days <= 7) {
                                                $btn_color = 'danger'; // Red for <= 7 days
                                            }
                                        }
                                    @endphp
                                    @if(!empty($l_data) && ($license->more_data == 'lifetime') && $l_data->is_applicable != 'no')
                                        <button type="button" class="btn btn-success btn-sm position-relative">
                                            Lifetime
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                                Valid
                                                <span class="visually-hidden">unread messages</span>
                                            </span>
                                        </button>
                                    @elseif(!empty($l_data) && $l_data->is_applicable == 'no')
                                        {{-- Handle the case where $l_data is applicable is 'no' --}}
                                    @else
                                        {!! !empty($l_data) && strtotime($l_data->next_due) > strtotime(date('Y-m-d')) ? '<button type="button" class="btn btn-'.$btn_color.' btn-sm position-relative">'.\Carbon\Carbon::parse(date('Y-m-d'))->diffInDays($l_data->next_due).'<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Valid<span class="visually-hidden">unread messages</span></span></button>' : '<span class="btn btn-sm btn-'.$btn_color.'">Lapsed</span>' !!}
                                    @endif
                                </td>
                                <td>{{!empty($l_data)&&$l_data->is_applicable!='no'?$l_data->status:''}}</td>
                                <td>
                                    @if(!empty($l_data))
                                    <a href="javascript:void(0);" onclick="editLicense('{{$user->fullName()}}','{{ucfirst($license->name)}}','{{$user->id}}','{{$license->id}}','{{$license->more_data}}')" class="btn btn-primary btn-sm p-2">Edit</a>
                                    <a href="javascript:void(0);" onclick="addLicense('{{$user->fullName()}}','{{ucfirst($license->name)}}','{{$user->id}}','{{$license->id}}','{{$license->more_data}}')" class="btn btn-secondary btn-sm p-2">Renew</a>
                                    <!--<a href="javascript:void(0);" class="btn btn-success btn-sm p-2">Generate Renewal Form</a>-->
                                    <!--<a href="javascript:void(0);" class="btn btn-success btn-sm p-2">Generate Covering Letter</a>-->
                                    @else
                                    <a href="javascript:void(0);" onclick="addLicense('{{$user->fullName()}}','{{ucfirst($license->name)}}','{{$user->id}}','{{$license->id}}')" class="btn btn-primary btn-sm p-2">Add</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 bg-light mb-3 mt-3">
                    <p class="m-2"><b>Total Training: {{$trainings->count()}}</b></p>
                </div>
                <div class="col-md-12">
                    <table id="datatableTraining" class="table text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type Info</th>
                                <th>Applicable</th>
                                <th>Aircraft Name</th>
                                <th>Renewed On</th>
                                <th>Next Due</th>
                                <th>Remaining Days</th>
                                <th>Status</th> <!---active,Suspended,Revoked,Expired---->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($trainings as $key => $training)
                            @php
                            $t_data=checkTraining($user->id,$training->id);
                            @endphp
                            <tr>
                                <th>{{$key+1}}</th>
                                <td>{{ucfirst($training->name)}} {!!(!empty($t_data->documents)?('<a href="'.asset('uploads/pilot_certificate/'.$t_data->documents).'"  target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>'):'')!!}</td>
                                <td>
                                    <div class="d-flex">
                                        <div class="form-check m-2">
                                          <input class="form-check-input" type="checkbox" value="no" {{!empty($t_data)&&$t_data->is_applicable=='no'?'checked':''}} onchange='handleChange(this);' user-id="{{$user->id}}" certificat-type="training" certificate-id="{{$training->id}}" id="defaultCheck_{{$training->id}}">
                                          <label class="form-check-label" for="defaultCheck_{{$training->id}}">N/A</label>
                                        </div>
                                    </div>
                                </td>
                                <th class="text-center">{{!empty($t_data)&&$t_data->is_applicable!='no'?$t_data->aircroft_registration.'-'.$t_data->aircroft_type:''}}</th>
                                <th>{{!empty($t_data)&&$t_data->is_applicable=='yes'?$t_data->renewed_on:''}}</th>
                                <td>{{!empty($t_data)&&$t_data->is_applicable=='yes'?$t_data->next_due:''}}</td>
                                <td>
                                    @if(!empty($t_data) && $t_data->is_applicable == 'no')
                                        {{-- Optional content for non-applicable t_data --}}
                                    @else
                                        @php
                                            $remaining_days = !empty($t_data) ? \Carbon\Carbon::parse(date('Y-m-d'))->diffInDays($t_data->next_due, false) : 0;
                                            $btn_class = 'danger'; // Default for lapsed
                                            $badge_color = 'danger'; // Matching default for lapsed badge
                                            if($remaining_days > 60) {
                                                $btn_class = 'primary'; // Blue for >60 days
                                            } elseif($remaining_days <= 60 && $remaining_days > 7) {
                                                $btn_class = 'warning'; // Orange for <=60 and >7 days
                                            } elseif($remaining_days > 0 && $remaining_days <= 7) {
                                                $btn_class = 'danger'; // Red for <=7 days
                                            }
                                        @endphp

                                        @if(!empty($t_data) && strtotime($t_data->next_due) > strtotime(date('Y-m-d')))
                                            <button type="button" class="btn btn-{{ $btn_class }} btn-sm position-relative">
                                                {{ $remaining_days }}
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                                    Valid
                                                    <span class="visually-hidden">unread messages</span>
                                                </span>
                                            </button>
                                        @else
                                            <span class="btn btn-sm btn-{{ $btn_class }}">Lapsed</span>
                                        @endif
                                    @endif
                                </td>
                                <!--<td>-->
                                <!--    @if(!empty($t_data)&&$t_data->is_applicable=='no')-->
                                    
                                <!--    @else-->
                                <!--    {!!(!empty($t_data)&& strtotime($t_data->next_due) > strtotime(date('Y-m-d'))?'<button type="button" class="btn btn-primary btn-sm position-relative">'.\Carbon\Carbon::parse( date('Y-m-d') )->diffInDays($t_data->next_due ).'<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Valid<span class="visually-hidden">unread messages</span></span></button>':'<span class="btn btn-sm btn-danger">Lapsed</span>')!!}-->
                                <!--    @endif-->
                                <!--</td>-->
                                <td>{{!empty($t_data)&&$t_data->is_applicable!='no'?$t_data->status:''}}</td>
                                <td>
                                    @if(!empty($t_data))
                                    <a href="javascript:void(0);" onclick="editTraining('{{$user->fullName()}}','{{ucfirst($training->name)}}','{{$user->id}}','{{$training->id}}');" class="btn btn-primary btn-sm p-2">Edit</a>
                                    <a href="javascript:void(0);" onclick="addTraining('{{$user->fullName()}}','{{ucfirst($training->name)}}','{{$user->id}}','{{$training->id}}');" class="btn btn-secondary btn-sm p-2">Renew</a>
                                    <!--<a href="javascript:void(0);" class="btn btn-success btn-sm p-2">Generate Renewal Form</a>-->
                                    <!--<a href="javascript:void(0);" class="btn btn-success btn-sm p-2">Generate Covering Letter</a>-->
                                    @else
                                    <a href="javascript:void(0);" onclick="addTraining('{{$user->fullName()}}','{{ucfirst($training->name)}}','{{$user->id}}','{{$training->id}}');" class="btn btn-primary btn-sm p-2">Add</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 bg-light mb-3 mt-3">
                    <p class="m-2"><b>Total Medical: {{$medicals->count()}}</b></p>
                </div>
                <div class="col-md-12">
                    <table id="datatableMedical" class="table text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type Info</th>
                                <th>Applicable</th>
                                <th>Renewed On</th>
                                <th>Next Due</th>
                                <th>Remaining Days</th>
                                <th>Status</th> <!---active,Suspended,Revoked,Expired---->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicals as $key => $medical)
                            @php
                            $m_data=checkMedical($user->id,$medical->id);
                            @endphp
                            <tr>
                                <th>{{$key+1}}</th>
                                <td>{{ucfirst($medical->name)}} {!!(!empty($m_data->documents)?('<a href="'.asset('uploads/pilot_certificate/'.$m_data->documents).'"  target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>'):'')!!}</td>
                                <td>
                                    <div class="d-flex">
                                        <div class="form-check m-2">
                                          <input class="form-check-input" type="checkbox" on value="no"  {{!empty($m_data)&&$m_data->is_applicable=='no'?'checked':''}} onchange='handleChange(this);' user-id="{{$user->id}}" certificat-type="medical" certificate-id="{{$medical->id}}" id="defaultCheck_{{$medical->id}}">
                                          <label class="form-check-label" for="defaultCheck_{{$medical->id}}">N/A</label>
                                        </div>
                                    </div>
                                </td>
                                <th>{{!empty($m_data)&&$m_data->is_applicable=='yes'?$m_data->medical_done_on:''}}</th>
                                <td>{{!empty($m_data)&&$m_data->is_applicable=='yes'?$m_data->next_due:''}}</td>
                                <td>
                                    @if(!empty($m_data) && $m_data->is_applicable == 'no')
                                        {{-- You can place optional content here for when $m_data is not applicable --}}
                                    @else
                                        @php
                                            if(!empty($m_data)) {
                                                $remaining_days = \Carbon\Carbon::parse(date('Y-m-d'))->diffInDays($m_data->next_due, false);
                                                if($remaining_days > 60) {
                                                    $btn_class = 'primary';
                                                } elseif($remaining_days <= 60 && $remaining_days > 7) {
                                                    $btn_class = 'warning'; // Orange for 7 < days <= 60
                                                } elseif($remaining_days > 0 && $remaining_days <= 7) {
                                                    $btn_class = 'danger'; // Red for days <= 7
                                                }
                                            }
                                        @endphp

                                        @if(!empty($m_data) && strtotime($m_data->next_due) > strtotime(date('Y-m-d')))
                                            <button type="button" class="btn btn-{{ $btn_class }} btn-sm position-relative">
                                                {{ $remaining_days }}
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                                    Valid
                                                    <span class="visually-hidden">unread messages</span>
                                                </span>
                                            </button>
                                        @else
                                            <span class="btn btn-sm btn-{{ $btn_class }}">Lapsed</span>
                                        @endif
                                    @endif
                                </td>
                                <!--<td>-->
                                <!--    @if(!empty($m_data)&&$m_data->is_applicable=='no')-->
                                    
                                <!--    @else-->
                                <!--    {!!(!empty($m_data)&& strtotime($m_data->next_due) > strtotime(date('Y-m-d'))?'<button type="button" class="btn btn-primary btn-sm position-relative">'.\Carbon\Carbon::parse( date('Y-m-d') )->diffInDays($m_data->next_due ).'<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Valid<span class="visually-hidden">unread messages</span></span></button>':'<span class="btn btn-sm btn-danger">Lapsed</span>')!!}-->
                                <!--    @endif-->
                                <!--</td>-->
                                <td>{{!empty($m_data)&&$m_data->is_applicable=='yes'?$m_data->status:''}}</td>
                                <td>
                                    @if(!empty($m_data))
                                    <a href="javascript:void(0);" onclick="editMedical('{{$user->fullName()}}','{{ucfirst($medical->name)}}','{{$user->id}}','{{$medical->id}}');" class="btn btn-primary btn-sm p-2">Edit</a>
                                    <a href="javascript:void(0);" onclick="addMedical('{{$user->fullName()}}','{{ucfirst($medical->name)}}','{{$user->id}}','{{$medical->id}}');"  class="btn btn-secondary btn-sm p-2">Renew</a>
                                    <!--<a href="javascript:void(0);" class="btn btn-success btn-sm p-2">Generate Renewal Form</a>-->
                                    <!--<a href="javascript:void(0);" class="btn btn-success btn-sm p-2">Generate Covering Letter</a>-->
                                    @else
                                    <a href="javascript:void(0);" onclick="addMedical('{{$user->fullName()}}','{{ucfirst($medical->name)}}','{{$user->id}}','{{$medical->id}}');" class="btn btn-primary btn-sm p-2">Add</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="col-md-12 bg-light mb-3 mt-3">
                    <p class="m-2"><b>Total Qualification: {{$qualifications->count()}}</b></p>
                </div>
                <div class="col-md-12">
                    <table id="datatableQualification" class="table text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type Info</th>
                                <th>Applicable</th>
                                <th>Renewed On</th>
                                <th>Next Due</th>
                                <th>Remaining Days</th>
                                <th>Status</th> <!---active,Suspended,Revoked,Expired---->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($qualifications as $key => $qualification)
                            @php
                            $q_data=checkQualification($user->id,$qualification->id);
                            @endphp
                            <tr>
                                <th>{{$key+1}}</th>
                                <td>{{ucfirst($qualification->name)}} {!!(!empty($q_data->documents)?('<a href="'.asset('uploads/pilot_certificate/'.$q_data->documents).'"  target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>'):'')!!}</td>
                                <td>
                                    <div class="d-flex">
                                        <div class="form-check m-2">
                                          <input class="form-check-input" type="checkbox" on value="no"  {{!empty($q_data)&&$q_data->is_applicable=='no'?'checked':''}} onchange='handleChange(this);' user-id="{{$user->id}}" certificat-type="qualification" certificate-id="{{$qualification->id}}" id="defaultCheck_{{$qualification->id}}">
                                          <label class="form-check-label" for="defaultCheck_{{$qualification->id}}">N/A</label>
                                        </div>
                                    </div>
                                </td>
                                <th>{{!empty($q_data)&&$q_data->is_applicable=='yes'?$q_data->renewed_on:''}}</th>
                                <td>{{!empty($q_data)&&$q_data->is_applicable=='yes'?$q_data->next_due:''}}</td>
                                <td>
                                    @if(!empty($q_data) && $q_data->is_applicable == 'no')
                                        {{-- Optional: Handle the case where $q_data is not applicable --}}
                                    @else
                                        @php
                                            $remaining_days = !empty($q_data) ? \Carbon\Carbon::now()->diffInDays($q_data->next_due, false) : null;
                                            $btn_class = 'danger'; // Default color for lapsed or <=7 days
                                            $badge_color = 'danger'; // You might want to adjust if you need a different badge color for specific conditions
                                            $validity_text = 'Lapsed'; // Default text

                                            if ($remaining_days !== null) {
                                                if ($remaining_days > 60) {
                                                    $btn_class = 'primary'; // Blue for >60 days
                                                    $validity_text = $remaining_days;
                                                } elseif ($remaining_days <= 60 && $remaining_days > 7) {
                                                    $btn_class = 'warning'; // Orange for <=60 and >7 days
                                                    $validity_text = $remaining_days;
                                                } elseif ($remaining_days > 0 && $remaining_days <= 7) {
                                                    $btn_class = 'danger'; // Red for <=7 days
                                                    $validity_text = $remaining_days;
                                                }
                                            }
                                        @endphp

                                        @if(!empty($q_data) && strtotime($q_data->next_due) > strtotime(date('Y-m-d')))
                                            <button type="button" class="btn btn-{{ $btn_class }} btn-sm position-relative">
                                                {{ $validity_text }}
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                                    Valid
                                                    <span class="visually-hidden">unread messages</span>
                                                </span>
                                            </button>
                                        @else
                                            <span class="btn btn-sm btn-{{ $btn_class }}">{{ $validity_text }}</span>
                                        @endif
                                    @endif
                                </td>
                                <!--<td>-->
                                <!--    @if(!empty($q_data)&&$q_data->is_applicable=='no')-->
                                    
                                <!--    @else -->
                                <!--    {!!(!empty($q_data)&& strtotime($q_data->next_due) > strtotime(date('Y-m-d'))?'<button type="button" class="btn btn-primary btn-sm position-relative">'.\Carbon\Carbon::parse( date('Y-m-d') )->diffInDays($q_data->next_due ).'<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Valid<span class="visually-hidden">unread messages</span></span></button>':'<span class="btn btn-sm btn-danger">Lapsed</span>')!!}-->
                                <!--    @endif-->
                                <!--</td>-->
                                <td>{{!empty($q_data)&&$q_data->is_applicable=='yes'?$q_data->status:''}}</td>
                                <td>
                                    @if(!empty($q_data))
                                    <a href="javascript:void(0);" onclick="editQualification('{{$user->fullName()}}','{{ucfirst($qualification->name)}}','{{$user->id}}','{{$qualification->id}}');" class="btn btn-primary btn-sm p-2">Edit</a>
                                    <a href="javascript:void(0);" onclick="addQualification('{{$user->fullName()}}','{{ucfirst($qualification->name)}}','{{$user->id}}','{{$qualification->id}}');"  class="btn btn-secondary btn-sm p-2">Renew</a>
                                    <!--<a href="javascript:void(0);" class="btn btn-success btn-sm p-2">Generate Renewal Form</a>-->
                                    <!--<a href="javascript:void(0);" class="btn btn-success btn-sm p-2">Generate Covering Letter</a>-->
                                    @else
                                    <a href="javascript:void(0);" onclick="addQualification('{{$user->fullName()}}','{{ucfirst($qualification->name)}}','{{$user->id}}','{{$qualification->id}}');" class="btn btn-primary btn-sm p-2">Add</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                
                <div class="col-md-12 bg-light mb-3 mt-3">
                    <p class="m-2"><b>Total Ground Training: {{$groundtrainings->count()}}</b></p>
                </div>
                <div class="col-md-12">
                    <table id="datatableGroundTraining" class="table text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type Info</th>
                                <th>Applicable</th>
                                <!--<th>Aircraft Name</th>-->
                                <th>Renewed On</th>
                                <th>Next Due</th>
                                <th>Remaining Days</th>
                                <th>Status</th> <!---active,Suspended,Revoked,Expired---->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($groundtrainings as $key => $gdtraining)
                            @php
                            $gt_data=checkGroundTraining($user->id,$gdtraining->id);
                            @endphp
                            <tr>
                                <th>{{$key+1}}</th>
                                <td>{{ucfirst($gdtraining->name)}} {!!(!empty($gt_data->documents)?('<a href="'.asset('uploads/pilot_certificate/'.$gt_data->documents).'"  target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>'):'')!!}</td>
                                <td>
                                    <div class="d-flex">
                                        <div class="form-check m-2">
                                          <input class="form-check-input" type="checkbox" value="no" {{!empty($gt_data)&&$gt_data->is_applicable=='no'?'checked':''}} onchange='handleChange(this);' user-id="{{$user->id}}" certificat-type="ground_training" certificate-id="{{$gdtraining->id}}" id="defaultCheck_{{$gdtraining->id}}">
                                          <label class="form-check-label" for="defaultCheck_{{$gdtraining->id}}">N/A</label>
                                        </div>
                                    </div>
                                </td>
                                <!--<th class="text-center">{{!empty($gt_data)?$gt_data->aircroft_registration.'-'.$gt_data->aircroft_type:''}}</th>-->
                                <th>{{!empty($gt_data)&&$gt_data->is_applicable=='yes'?$gt_data->renewed_on:''}}</th>
                                <td>{{!empty($gt_data)&&$gt_data->is_applicable=='yes'?$gt_data->next_due:''}}</td>
                                <td>
                                    @if(!empty($gt_data) && $gt_data->is_applicable == 'no')
                                        {{-- Optional: Handle the case where $gt_data is not applicable --}}
                                    @else
                                        @php
                                            $remaining_days = !empty($gt_data) ? \Carbon\Carbon::now()->diffInDays($gt_data->next_due, false) : null;
                                            $btn_class = 'danger'; // Default color for lapsed or <=7 days
                                            $badge_color = 'danger'; // You might want to adjust if you need a different badge color for specific conditions
                                            $validity_text = 'Lapsed'; // Default text

                                            if ($remaining_days !== null) {
                                                if ($remaining_days > 60) {
                                                    $btn_class = 'primary'; // Blue for >60 days
                                                    $validity_text = $remaining_days;
                                                } elseif ($remaining_days <= 60 && $remaining_days > 7) {
                                                    $btn_class = 'warning'; // Orange for <=60 and >7 days
                                                    $validity_text = $remaining_days;
                                                } elseif ($remaining_days > 0 && $remaining_days <= 7) {
                                                    $btn_class = 'danger'; // Red for <=7 days
                                                    $validity_text = $remaining_days;
                                                }
                                            }
                                        @endphp

                                        @if(!empty($gt_data) && strtotime($gt_data->next_due) > strtotime(date('Y-m-d')))
                                            <button type="button" class="btn btn-{{ $btn_class }} btn-sm position-relative">
                                                {{ $validity_text }}
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                                    Valid
                                                    <span class="visually-hidden">unread messages</span>
                                                </span>
                                            </button>
                                        @else
                                            <span class="btn btn-sm btn-{{ $btn_class }}">{{ $validity_text }}</span>
                                        @endif
                                    @endif
                                </td>
                                <!--<td>-->
                                <!--    @if(!empty($gt_data)&&$gt_data->is_applicable=='no')-->
                                    
                                <!--    @else-->
                                <!--    {!!(!empty($gt_data)&& strtotime($gt_data->next_due) > strtotime(date('Y-m-d'))?'<button type="button" class="btn btn-primary btn-sm position-relative">'.\Carbon\Carbon::parse( date('Y-m-d') )->diffInDays($gt_data->next_due ).'<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Valid<span class="visually-hidden">unread messages</span></span></button>':'<span class="btn btn-sm btn-danger">Lapsed</span>')!!}-->
                                <!--    @endif-->
                                <!--</td>-->
                                <td>{{!empty($gt_data)&& $gt_data->is_applicable != 'no'?$gt_data->status:''}}</td>
                                <td>
                                    @if(!empty($gt_data))
                                    <a href="javascript:void(0);" onclick="editGroundTraining('{{$user->fullName()}}','{{ucfirst($gdtraining->name)}}','{{$user->id}}','{{$gdtraining->id}}');" class="btn btn-primary btn-sm p-2">Edit</a>
                                    <a href="javascript:void(0);" onclick="addGroundTraining('{{$user->fullName()}}','{{ucfirst($gdtraining->name)}}','{{$user->id}}','{{$gdtraining->id}}');" class="btn btn-secondary btn-sm p-2">Renew</a>
                                    <!--<a href="javascript:void(0);" class="btn btn-success btn-sm p-2">Generate Renewal Form</a>-->
                                    <!--<a href="javascript:void(0);" class="btn btn-success btn-sm p-2">Generate Covering Letter</a>-->
                                    @else
                                    <a href="javascript:void(0);" onclick="addGroundTraining('{{$user->fullName()}}','{{ucfirst($gdtraining->name)}}','{{$user->id}}','{{$gdtraining->id}}');" class="btn btn-primary btn-sm p-2">Add</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>

    <div class="modal fade" id="manageModalLicense" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Manage License</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="licenseForm" class="" autocomplete="off" enctype= multipart/form-data>
                    @csrf
                    <div class="modal-body"> 
                        <div class="row mb-2 bg-light">
                            <div class="col-md-6">
                                Crew : <b id="crew_name"></b>
                            </div>
                            <div class="col-md-6">
                                License : <b id="license"></b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="renewed_on">Renewed On</label>
                                    <input type="hidden" name="edit_id" id="edit_id" />
                                    <input type="hidden" name="user_id" id="user_id" />
                                    <input type="hidden" name="license_id" id="license_id" />
                                    <input type="text" class="form-control datepicker" name="renewed_on" id="renewed_on" placeholder="Enter Renewed On" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="number">Number</label>
                                    <input type="text" class="form-control" name="number" id="number" placeholder="Enter Number" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="plannedRenewalDate">Planned Renewal Date</label>
                                    <input type="text" class="form-control datepicker" onchange="checkDate('renewed_on' ,'planned_renewal_date');" name="plannedRenewalDate" id="planned_renewal_date" placeholder="Enter Planned Renewal Date" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="issued_on">Issued On</label>
                                    <input type="text" class="form-control datepicker" onchange="checkDate('issued_on' ,'renewed_on');" name="issued_on" id="issued_on" placeholder="Enter Issued On" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="extended_date">Extended Date</label>
                                    <input type="text" class="form-control datepicker" onchange="checkDate('renewed_on' ,'extended_date');"  name="extended_date" id="extended_date" placeholder="Enter Extended Date" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="renewal_office">Renewal Office</label>
                                    <input type="text" class="form-control" name="renewal_office" id="renewal_office" placeholder="Enter Renewal Office" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="next_due">Next Due</label>
                                    <input type="text" class="form-control datepicker" onchange="checkDate('renewed_on' ,'next_due');"  name="next_due" id="next_due" placeholder="Enter Next Due" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Select</option>
                                        <option value="Active">Active</option>
                                        <option value="Suspended">Suspended</option>
                                        <option value="Revoked">Revoked</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="remarks">Remarks</label>
                            <textarea class="form-control" name="remarks" id="remarks"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="documnets">Upload Document</label>
                            <input type="file" class="form-control" name="documnets" id="documnets">
                            <div id="licenseDocument" class="p-2">
                                
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="manageModalTraining" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Manage Training</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="trainingForm" class="" autocomplete="off" enctype= multipart/form-data>
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-2 bg-light">
                            <div class="col-md-6">
                                Crew : <b id="crew_name"></b>
                            </div>
                            <div class="col-md-6">
                                Training : <b id="training"></b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="renewed_on">Renewed On</label>
                                    <input type="hidden" name="edit_id" id="edit_id" />
                                    <input type="hidden" name="user_id" id="user_id" />
                                    <input type="hidden" name="training_id" id="training_id" />
                                    <input type="text" class="form-control datepicker" name="renewed_on" id="renewed_on" placeholder="Enter Renewed On" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="seat_occupied">Seat Occupied</label>
                                    <select name="seat_occupied" id="seat_occupied" class="form-control">
                                        <option value="">Select</option>
                                        <option value="LHS">LHS</option>
                                        <option value="RHS">RHS</option>
                                        <option value="LHS/RHS">LHS/RHS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="planned_renewal_date">Planned Renewal Date</label>
                                    <input type="text" class="form-control datepicker" onchange="checkDate('trainingForm #renewed_on' ,'trainingForm #planned_renewal_date');" name="planned_renewal_date" id="planned_renewal_date" placeholder="Enter Planned Renewal Date" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="examiner">Examiner</label>
                                    <input type="text" class="form-control" name="examiner" id="examiner" placeholder="Enter Examiner" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="extended_date">Extended Date</label>
                                    <input type="text" class="form-control datepicker" onchange="checkDate('trainingForm #renewed_on' ,'trainingForm #extended_date');" name="extended_date" id="extended_date" placeholder="Enter Extended Date" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="day_night">Day/Night</label>
                                    <select name="day_night" id="day_night" class="form-control">
                                        <option value="">Select</option>
                                        <option value="Day">Day</option>
                                        <option value="Night">Night</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="next_due">Next Due</label>
                                    <input type="text" class="form-control datepicker" onchange="checkDate('trainingForm #renewed_on' ,'trainingForm #next_due');" name="next_due" id="next_due" placeholder="Enter Next Due" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="test_on">Test On</label>
                                    <select name="test_on" id="test_on" class="form-control">
                                        <option value="">Select</option>
                                        <option value="Aeroplane">Aeroplane</option>
                                        <option value="Helicopter">Helicopter</option>
                                        <option value="Simulator">Simulator</option>
                                        <option value="ground_training">Ground Training</option>

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
                                        <option value="Active">Active</option>
                                        <option value="Suspended">Suspended</option>
                                        <option value="Revoked">Revoked</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="simulator_level">Simulator Level</label>
                                    <select name="simulator_level" id="simulator_level" class="form-control">
                                        <option value="">Select</option>
                                        <option value="Level A">Level A</option>
                                        <option value="Level B">Level B</option>
                                        <option value="Level C">Level C</option>
                                        <option value="Level D">Level D</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="remarks">Remarks</label>
                                    <textarea class="form-control" name="remarks" id="remarks"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="aircroft_registration">Aircraft Registration</label>
                                    <input type="text" class="form-control" name="aircroft_registration" id="aircroft_registration" placeholder="Enter Aircraft Registration" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="aircroft_type">Aircraft Type</label>
                                    <select class="form-control" name="aircroft_type" id="aircroft_type">
                                        <option value="">Select</option>
                                        @foreach($ac_types as $ac_type)
                                        <option value="{{$ac_type->id}}">{{$ac_type->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="aircroft_model">Aircraft Model</label>
                                    <input type="text" class="form-control" name="aircroft_model" id="aircroft_model" placeholder="Enter Aircraft Model" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="P1_hours">P1 Hours</label>
                                    <input type="text" class="form-control" name="P1_hours" id="P1_hours" placeholder="Enter P1 Hours" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="P2_hours">P2 Hours</label>
                                    <input type="text" class="form-control" name="P2_hours" id="P2_hours" placeholder="Enter P2 Hours" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="renewal_office">Renewal Office</label>
                                    <input type="text" class="form-control" name="renewal_office" id="renewal_office" placeholder="Enter Renewal Office" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="place_of_test">Place of Test</label>
                                    <input type="text" class="form-control" name="place_of_test" id="place_of_test" placeholder="Enter Place of Test" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="approach_details">Approach Details</label>
                                    <input type="text" class="form-control" name="approach_details" id="approach_details" placeholder="Enter Approach Details" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="documnets">Upload Document</label>
                            <input type="file" class="form-control" name="documnets" id="documnets">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="manageModalMedical" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Manage Medical</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="medicalForm" class="" enctype= multipart/form-data>
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-2 bg-light">
                            <div class="col-md-6">
                                Crew : <b id="crew_name"></b>
                            </div>
                            <div class="col-md-6">
                                Medical : <b id="medical"></b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="medical_done_on">Medical Done On</label>
                                    <input type="hidden" name="edit_id" id="edit_id" />
                                    <input type="hidden" name="user_id" id="user_id" />
                                    <input type="hidden" name="medical_id" id="medical_id" />
                                    <input type="text" class="form-control datepicker" name="medical_done_on" id="medical_done_on" placeholder="Enter Medical Done On" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="medical_done_at">Medical Done At</label>
                                    <input type="text" class="form-control" name="medical_done_at" id="medical_done_at" placeholder="Enter Medical Done At" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="medical_result">Medical Result</label>
                                    <select name="medical_result" id="medical_result" class="form-control">
                                        <option value="">Select</option>
                                        <option value="Fit">Fit</option>
                                        <option value="Temporary Unfit">Temporary Unfit</option>
                                        <option value="Unfit">Unfit</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="planned_renewal_date">Planned Renewal Date</label>
                                    <input type="text" class="form-control datepicker"  onchange="checkDate('medicalForm #medical_done_on','medicalForm #planned_renewal_date');" name="planned_renewal_date" id="planned_renewal_date" placeholder="Enter Planned Renewal Date" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="extended_date">Extended Date</label>
                                    <input type="text" class="form-control datepicker" onchange="checkDate('medicalForm #medical_done_on','medicalForm #extended_date');" name="extended_date" id="extended_date" placeholder="Enter Extended Date" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="mandatory_medical_center_count">Mandatory Medical Center Count</label>
                                    <input type="text" class="form-control" name="mandatory_medical_center_count" id="mandatory_medical_center_count" placeholder="Enter Mandatory Medical Center Count" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="next_due">Next Due</label>
                                    <input type="text" class="form-control datepicker" onchange="checkDate('medicalForm #medical_done_on','medicalForm #next_due');" name="next_due" id="next_due" placeholder="Enter Next Due" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Select</option>
                                        <option value="Active">Active</option>
                                        <option value="Suspended">Suspended</option>
                                        <option value="Revoked">Revoked</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="remarks">Remarks</label>
                            <textarea class="form-control" name="remarks" id="remarks"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="limitations">Limitations</label>
                            <textarea class="form-control" name="limitations" id="limitations"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="documnets">Upload Document</label>
                            <input type="file" class="form-control" name="documnets" id="documnets">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="manageQualification" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Manage Qualification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="licenseForm" class="" autocomplete="off" enctype= multipart/form-data>
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-2 bg-light">
                            <div class="col-md-6">
                                Crew : <b id="crew_name"></b>
                            </div>
                            <div class="col-md-6">
                                Qualification : <b id="qualification"></b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="renewed_on">Renewed On</label>
                                    <input type="hidden" name="edit_id" id="edit_id" />
                                    <input type="hidden" name="user_id" id="user_id" />
                                    <input type="hidden" name="qualification_id" id="qualification_id" />
                                    <input type="text" class="form-control datepicker" name="renewed_on" id="renewed_on" placeholder="Enter Renewed On" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="number">Number</label>
                                    <input type="text" class="form-control" name="number" id="number" placeholder="Enter Number" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="plannedRenewalDate">Planned Renewal Date</label>
                                    <input type="text" class="form-control datepicker" onchange="checkDate('renewed_on' ,'planned_renewal_date');" name="plannedRenewalDate" id="planned_renewal_date" placeholder="Enter Planned Renewal Date" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="issued_on">Issued On</label>
                                    <input type="text" class="form-control datepicker" onchange="checkDate('issued_on' ,'renewed_on');" name="issued_on" id="issued_on" placeholder="Enter Issued On" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="extended_date">Extended Date</label>
                                    <input type="text" class="form-control datepicker" onchange="checkDate('renewed_on' ,'extended_date');"  name="extended_date" id="extended_date" placeholder="Enter Extended Date" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="renewal_office">Renewal Office</label>
                                    <input type="text" class="form-control" name="renewal_office" id="renewal_office" placeholder="Enter Renewal Office" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="next_due">Next Due</label>
                                    <input type="text" class="form-control datepicker" onchange="checkDate('renewed_on' ,'next_due');"  name="next_due" id="next_due" placeholder="Enter Next Due" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Select</option>
                                        <option value="Active">Active</option>
                                        <option value="Suspended">Suspended</option>
                                        <option value="Revoked">Revoked</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="remarks">Remarks</label>
                            <textarea class="form-control" name="remarks" id="remarks"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="documnets">Upload Document</label>
                            <input type="file" class="form-control" name="documnets" id="documnets">
                            <div id="licenseDocument" class="p-2">
                                
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="manageGroundTraining" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Manage Ground Training</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="GroundTrainingForm" class="" autocomplete="off" enctype= multipart/form-data>
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-2 bg-light">
                            <div class="col-md-6">
                                Crew : <b id="crew_name"></b>
                            </div>
                            <div class="col-md-6">
                                Ground Training : <b id="training"></b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="renewed_on">Renewed On</label>
                                    <input type="hidden" name="edit_id" id="edit_id" />
                                    <input type="hidden" name="user_id" id="user_id" />
                                    <input type="hidden" name="training_id" id="training_id" />
                                    <input type="text" class="form-control datepicker" name="renewed_on" id="renewed_on" placeholder="Enter Renewed On" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="seat_occupied">Seat Occupied</label>
                                    <select name="seat_occupied" id="seat_occupied" class="form-control">
                                        <option value="">Select</option>
                                        <option value="LHS">LHS</option>
                                        <option value="RHS">RHS</option>
                                        <option value="LHS/RHS">LHS/RHS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="planned_renewal_date">Planned Renewal Date</label>
                                    <input type="text" class="form-control datepicker" onchange="checkDate('GroundTrainingForm #renewed_on' ,'GroundTrainingForm #planned_renewal_date');" name="planned_renewal_date" id="planned_renewal_date" placeholder="Enter Planned Renewal Date" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="examiner">Examiner</label>
                                    <input type="text" class="form-control" name="examiner" id="examiner" placeholder="Enter Examiner" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="extended_date">Extended Date</label>
                                    <input type="text" class="form-control datepicker" onchange="checkDate('GroundTrainingForm #renewed_on' ,'GroundTrainingForm #extended_date');" name="extended_date" id="extended_date" placeholder="Enter Extended Date" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="day_night">Day/Night</label>
                                    <select name="day_night" id="day_night" class="form-control">
                                        <option value="">Select</option>
                                        <option value="Day">Day</option>
                                        <option value="Night">Night</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="next_due">Next Due</label>
                                    <input type="text" class="form-control datepicker" onchange="checkDate('GroundTrainingForm #renewed_on' ,'GroundTrainingForm #next_due');" name="next_due" id="next_due" placeholder="Enter Next Due" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="test_on">Test On</label>
                                    <select name="test_on" id="test_on" class="form-control">
                                        <option value="">Select</option>
                                        <option value="Aeroplane">Aeroplane</option>
                                        <option value="Helicopter">Helicopter</option>
                                        <option value="Simulator">Simulator</option>
                                        <option value="ground_training">Ground Training</option>

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
                                        <option value="Active">Active</option>
                                        <option value="Suspended">Suspended</option>
                                        <option value="Revoked">Revoked</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="simulator_level">Simulator Level</label>
                                    <select name="simulator_level" id="simulator_level" class="form-control">
                                        <option value="">Select</option>
                                        <option value="Level A">Level A</option>
                                        <option value="Level B">Level B</option>
                                        <option value="Level C">Level C</option>
                                        <option value="Level D">Level D</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="remarks">Remarks</label>
                                    <textarea class="form-control" name="remarks" id="remarks"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="aircroft_registration">Aircraft Registration</label>
                                    <input type="text" class="form-control" name="aircroft_registration" id="aircroft_registration" placeholder="Enter Aircraft Registration" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="aircroft_type">Aircraft Type</label>
                                    <input type="text" class="form-control" name="aircroft_type" id="aircroft_type" placeholder="Enter Aircraft Type" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="aircroft_model">Aircraft Model</label>
                                    <input type="text" class="form-control" name="aircroft_model" id="aircroft_model" placeholder="Enter Aircraft Model" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="P1_hours">P1 Hours</label>
                                    <input type="text" class="form-control" name="P1_hours" id="P1_hours" placeholder="Enter P1 Hours" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="P2_hours">P2 Hours</label>
                                    <input type="text" class="form-control" name="P2_hours" id="P2_hours" placeholder="Enter P2 Hours" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="renewal_office">Renewal Office</label>
                                    <input type="text" class="form-control" name="renewal_office" id="renewal_office" placeholder="Enter Renewal Office" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="place_of_test">Place of Test</label>
                                    <input type="text" class="form-control" name="place_of_test" id="place_of_test" placeholder="Enter Place of Test" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="approach_details">Approach Details</label>
                                    <input type="text" class="form-control" name="approach_details" id="approach_details" placeholder="Enter Approach Details" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="documnets">Upload Document</label>
                            <input type="file" class="form-control" name="documnets" id="documnets">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <x-slot name="js">
        <script src="{{asset('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js')}}"></script>
        <script>
            $('.datepicker').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy',
                calendarWeeks: true,
                zIndexOffset: 9999
            });

            function checkDate(fromDate ,toDate)
            {
                let fdate = $('#'+fromDate).val();
                let ldate = $('#'+toDate).val();
                console.log(fdate);
                if (new Date(fdate) >= new Date(ldate)) {
                    $('#'+toDate).val('');
                    warning('This Date should be greater than Done / Renewed Date');
                    return false;
                }
            }

            function dataList() {
                $('#datatableLicense').DataTable().destroy();
                $('#datatableLicense').DataTable({
                    responsive: true,
                });
                $('#datatableTraining').DataTable().destroy();
                $('#datatableTraining').DataTable({
                    responsive: true,
                });
                $('#datatableMedical').DataTable().destroy();
                $('#datatableMedical').DataTable({
                    responsive: true,
                });
            }
            dataList();

            function editLicense(user, license, user_id, license_id,more_data) {
                $.ajax({
                    url: "{{route('app.pilot.license.edit')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        user_id,
                        license_id,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#manageModalLicense').modal('show');
                        $('#manageModalLicense .modal-title').text('Edit License');
                        $('#manageModalLicense #crew_name').text(user);
                        $('#manageModalLicense #license').text(license);
                        $('#manageModalLicense form').attr('action', "{{route('app.pilot.license.update')}}");
                        $('#manageModalLicense #license_id').val(license_id);
                        $('#manageModalLicense #user_id').val(user_id);
                        $('#manageModalLicense #edit_id').val(data.id);
                        $('#manageModalLicense #issued_on').val(data.issued_on);
                        $('#manageModalLicense #next_due').val(data.next_due);
                        $('#manageModalLicense #number').val(data.number);
                        $('#manageModalLicense #planned_renewal_date').val(data.planned_ren_date);
                        $('#manageModalLicense #extended_date').val(data.extended_date);
                        $('#manageModalLicense #remarks').val(data.remarks);
                        $('#manageModalLicense #renewal_office').val(data.renewal_office);
                        $('#manageModalLicense #renewed_on').val(data.renewed_on);
                        $('#manageModalLicense #status').val(data.status);
                        if(more_data=='lifetime')
                        {
                            $('#manageModalLicense #next_due').val('').attr('disabled',true);
                            $('#manageModalLicense #planned_renewal_date').val('').attr('disabled',true);
                            $('#manageModalLicense #extended_date').val('').attr('disabled',true);
                        }else{
                            $('#manageModalLicense #next_due').attr('disabled',false);
                            $('#manageModalLicense #planned_renewal_date').attr('disabled',false);
                            $('#manageModalLicense #extended_date').attr('disabled',false);
                        }
                        if(data.documents.length>0)
                        {
                            $('#licenseDocument').html(`<a href="{{asset('uploads/pilot_certificate')}}/'`+data.documents+`'" class="btn btn-sm btn-primary m-1" target="_blank">View</a><a href="javascript:void(0);" onclick="deleteDocuments('license','`+data.id+`');" class="btn btn-sm btn-danger m-1">Delete</a>`);
                        }else{
                            $('#licenseDocument').html('');
                        }
                    }
                });
            }

            function addLicense(user, license, user_id, license_id,more_data) {
                $('#manageModalLicense form')[0].reset();
                $('#manageModalLicense').modal('show');
                $('#manageModalLicense .modal-title').text('Add License');
                $('#manageModalLicense #crew_name').text(user);
                $('#manageModalLicense #license').text(license);
                $('#manageModalLicense form').attr('action', "{{route('app.pilot.license.store')}}");
                $('#manageModalLicense #license_id').val(license_id);
                $('#manageModalLicense #user_id').val(user_id);
                $('#manageModalLicense #edit_id').val('');
                if(more_data=='lifetime')
                {
                    $('#manageModalLicense #next_due').val('').attr('disabled',true);
                    $('#manageModalLicense #planned_renewal_date').val('').attr('disabled',true);
                    $('#manageModalLicense #extended_date').val('').attr('disabled',true);
                    
                }else{
                    $('#manageModalLicense #next_due').attr('disabled',false);
                    $('#manageModalLicense #planned_renewal_date').attr('disabled',false);
                    $('#manageModalLicense #extended_date').attr('disabled',false);
                }

            }

            function editTraining(user, training, user_id, training_id) {
                $.ajax({
                    url: "{{route('app.pilot.training.edit')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        user_id,
                        training_id,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#manageModalTraining').modal('show');
                        $('#manageModalTraining .modal-title').text('Edit Training');
                        $('#manageModalTraining #crew_name').text(user);
                        $('#manageModalTraining #training').text(training);
                        $('#manageModalTraining form').attr('action', "{{route('app.pilot.training.update')}}");
                        $('#manageModalTraining #training_id').val(training_id);
                        $('#manageModalTraining #user_id').val(user_id);
                        $('#manageModalTraining #edit_id').val(data.id);
                        $('#manageModalTraining #issued_on').val(data.issued_on);
                        $('#manageModalTraining #next_due').val(data.next_due);
                        $('#manageModalTraining #remarks').val(data.remarks);
                        $('#manageModalTraining #status').val(data.status);
                        $('#manageModalTraining #planned_renewal_date').val(data.planned_renewal_date);
                        $('#manageModalTraining #renewed_on').val(data.renewed_on);
                        $('#manageModalTraining #extended_date').val(data.extended_date);
                        $('#manageModalTraining #P1_hours').val(data.P1_hours);
                        $('#manageModalTraining #P2_hours').val(data.P2_hours);
                        $('#manageModalTraining #aircroft_model').val(data.aircroft_model);
                        $('#manageModalTraining #aircroft_registration').val(data.aircroft_registration);
                        $('#manageModalTraining #aircroft_type').val(data.aircroft_type);
                        $('#manageModalTraining #approach_details').val(data.approach_details);
                        $('#manageModalTraining #day_night').val(data.day_night);
                        $('#manageModalTraining #examiner').val(data.examiner);
                        $('#manageModalTraining #extended_date').val(data.extended_date);
                        $('#manageModalTraining #place_of_test').val(data.place_of_test);
                        $('#manageModalTraining #renewal_office').val(data.renewal_office);
                        $('#manageModalTraining #seat_occupied').val(data.seat_occupied);
                        $('#manageModalTraining #simulator_level').val(data.simulator_level);
                        $('#manageModalTraining #test_on').val(data.test_on);
                        if(data.documents.length>0)
                        {
                            $('#trainingDocument').html(`<a href="{{asset('uploads/pilot_certificate')}}/`+data.documents+`" class="btn btn-sm btn-primary m-1" target="_blank">View</a><a href="javascript:void(0);" onclick="deleteDocuments('training','`+data.id+`');" class="btn btn-sm btn-danger m-1">Delete</a>`);
                        }else{
                            $('#trainingDocument').html('');
                        }
                    }
                })
            }

            function addTraining(user, training, user_id, training_id) {
                $('#manageModalTraining').modal('show');
                $('#manageModalTraining .modal-title').text('Add Training');
                $('#manageModalTraining #crew_name').text(user);
                $('#manageModalTraining #training').text(training);
                $('#manageModalTraining form').attr('action', "{{route('app.pilot.training.store')}}");
                $('#manageModalTraining #training_id').val(training_id);
                $('#manageModalTraining #user_id').val(user_id);
                $('#manageModalTraining #edit_id').val('');
            }

            function editMedical(user, medical, user_id, medical_id) {
                $.ajax({
                    url: "{{route('app.pilot.medical.edit')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        user_id,
                        medical_id,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#manageModalMedical').modal('show');
                        $('#manageModalMedical .modal-title').text('Edit Medical');
                        $('#manageModalMedical #crew_name').text(user);
                        $('#manageModalMedical #medical').text(medical);
                        $('#manageModalMedical form').attr('action', "{{route('app.pilot.medical.update')}}");
                        $('#manageModalMedical #medical_id').val(medical_id);
                        $('#manageModalMedical #user_id').val(user_id);
                        $('#manageModalMedical #edit_id').val(data.id);
                        $('#manageModalMedical #issued_on').val(data.issued_on);
                        $('#manageModalMedical #next_due').val(data.next_due);
                        $('#manageModalMedical #remarks').val(data.remarks);
                        $('#manageModalMedical #status').val(data.status);
                        $('#manageModalMedical #limitations').val(data.limitations);
                        $('#manageModalMedical #mandatory_medical_center_count').val(data.mandatory_medical_center_count);
                        $('#manageModalMedical #medical_done_at').val(data.medical_done_at);
                        $('#manageModalMedical #medical_done_on').val(data.medical_done_on);
                        $('#manageModalMedical #medical_result').val(data.medical_result);
                        $('#manageModalMedical #planned_renewal_date').val(data.planned_renewal_date);
                        $('#manageModalMedical #extended_date').val(data.extended_date);
                        if(data.documents.length>0)
                        {
                            $('#medicalDocument').html(`<a href="{{asset('uploads/pilot_certificate')}}/`+data.documents+`" class="btn btn-sm btn-primary m-1" target="_blank">View</a><a href="javascript:void(0);" onclick="deleteDocuments('medical','`+data.id+`');" class="btn btn-sm btn-danger m-1">Delete</a>`);
                        }else{
                            $('#medicalDocument').html('');
                        }
                    }
                });
            }

            function addMedical(user, medical, user_id, medical_id) {
                $('#manageModalMedical').modal('show');
                $('#manageModalMedical .modal-title').text('Add Medical');
                $('#manageModalMedical #crew_name').text(user);
                $('#manageModalMedical #medical').text(medical);
                $('#manageModalMedical form').attr('action', "{{route('app.pilot.medical.store')}}");
                $('#manageModalMedical #medical_id').val(medical_id);
                $('#manageModalMedical #user_id').val(user_id);
                $('#manageModalMedical #edit_id').val('');
            }
            
            function editQualification(user, qualification, user_id, qualification_id,more_data) {
                $.ajax({
                    url: "{{route('app.pilot.qualification.edit')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        user_id,
                        qualification_id,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#manageQualification').modal('show');
                        $('#manageQualification .modal-title').text('Edit Qualification');
                        $('#manageQualification #crew_name').text(user);
                        $('#manageQualification #qualification').text(qualification);
                        $('#manageQualification form').attr('action', "{{route('app.pilot.qualification.update')}}");
                        $('#manageQualification #qualification_id').val(qualification_id);
                        $('#manageQualification #user_id').val(user_id);
                        $('#manageQualification #edit_id').val(data.id);
                        $('#manageQualification #issued_on').val(data.issued_on);
                        $('#manageQualification #next_due').val(data.next_due);
                        $('#manageQualification #number').val(data.number);
                        $('#manageQualification #planned_renewal_date').val(data.planned_ren_date);
                        $('#manageQualification #extended_date').val(data.extended_date);
                        $('#manageQualification #remarks').val(data.remarks);
                        $('#manageQualification #renewal_office').val(data.renewal_office);
                        $('#manageQualification #renewed_on').val(data.renewed_on);
                        $('#manageQualification #status').val(data.status);
                        if(more_data=='lifetime')
                        {
                            $('#manageQualification #next_due').val('').attr('disabled',true);
                            $('#manageQualification #planned_renewal_date').val('').attr('disabled',true);
                            $('#manageQualification #extended_date').val('').attr('disabled',true);
                        }else{
                            $('#manageQualification #next_due').attr('disabled',false);
                            $('#manageQualification #planned_renewal_date').attr('disabled',false);
                            $('#manageQualification #extended_date').attr('disabled',false);
                        }
                        if(data.documents.length>0)
                        {
                            $('#licenseDocument').html(`<a href="{{asset('uploads/pilot_certificate')}}/`+data.documents+`" class="btn btn-sm btn-primary m-1" target="_blank">View</a><a href="javascript:void(0);" onclick="deleteDocuments('license','`+data.id+`');" class="btn btn-sm btn-danger m-1">Delete</a>`);
                        }else{
                            $('#licenseDocument').html('');
                        }
                    }
                });
            }

            function addQualification(user, qualification, user_id, qualification_id,more_data) {
                $('#manageQualification form')[0].reset();
                $('#manageQualification').modal('show');
                $('#manageQualification .modal-title').text('Add Qualification');
                $('#manageQualification #crew_name').text(user);
                $('#manageQualification #qualification').text(qualification);
                $('#manageQualification form').attr('action', "{{route('app.pilot.qualification.store')}}");
                $('#manageQualification #qualification_id').val(qualification_id);
                $('#manageQualification #user_id').val(user_id);
                $('#manageQualification #edit_id').val('');
                if(more_data=='lifetime')
                {
                    $('#manageQualification #next_due').val('').attr('disabled',true);
                    $('#manageQualification #planned_renewal_date').val('').attr('disabled',true);
                    $('#manageQualification #extended_date').val('').attr('disabled',true);
                    
                }else{
                    $('#manageQualification #next_due').attr('disabled',false);
                    $('#manageQualification #planned_renewal_date').attr('disabled',false);
                    $('#manageQualification #extended_date').attr('disabled',false);
                }
            }
            
            function editGroundTraining(user, training, user_id, training_id) {
                $.ajax({
                    url: "{{route('app.pilot.groundTrainingEdit')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        user_id,
                        training_id,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#manageGroundTraining').modal('show');
                        $('#manageGroundTraining .modal-title').text('Edit Ground Training');
                        $('#manageGroundTraining #crew_name').text(user);
                        $('#manageGroundTraining #training').text(training);
                        $('#manageGroundTraining form').attr('action', "{{route('app.pilot.groundTrainingUpdate')}}");
                        $('#manageGroundTraining #training_id').val(training_id);
                        $('#manageGroundTraining #user_id').val(user_id);
                        $('#manageGroundTraining #edit_id').val(data.id);
                        $('#manageGroundTraining #issued_on').val(data.issued_on);
                        $('#manageGroundTraining #next_due').val(data.next_due);
                        $('#manageGroundTraining #remarks').val(data.remarks);
                        $('#manageGroundTraining #status').val(data.status);
                        $('#manageGroundTraining #planned_renewal_date').val(data.planned_renewal_date);
                        $('#manageGroundTraining #renewed_on').val(data.renewed_on);
                        $('#manageGroundTraining #extended_date').val(data.extended_date);
                        $('#manageGroundTraining #P1_hours').val(data.P1_hours);
                        $('#manageGroundTraining #P2_hours').val(data.P2_hours);
                        $('#manageGroundTraining #aircroft_model').val(data.aircroft_model);
                        $('#manageGroundTraining #aircroft_registration').val(data.aircroft_registration);
                        $('#manageGroundTraining #aircroft_type').val(data.aircroft_type);
                        $('#manageGroundTraining #approach_details').val(data.approach_details);
                        $('#manageGroundTraining #day_night').val(data.day_night);
                        $('#manageGroundTraining #examiner').val(data.examiner);
                        $('#manageGroundTraining #extended_date').val(data.extended_date);
                        $('#manageGroundTraining #place_of_test').val(data.place_of_test);
                        $('#manageGroundTraining #renewal_office').val(data.renewal_office);
                        $('#manageGroundTraining #seat_occupied').val(data.seat_occupied);
                        $('#manageGroundTraining #simulator_level').val(data.simulator_level);
                        $('#manageGroundTraining #test_on').val(data.test_on);
                        if(data.documents.length>0)
                        {
                            $('#trainingDocument').html(`<a href="{{asset('uploads/pilot_certificate')}}/`+data.documents+`" class="btn btn-sm btn-primary m-1" target="_blank">View</a><a href="javascript:void(0);" onclick="deleteDocuments('training','`+data.id+`');" class="btn btn-sm btn-danger m-1">Delete</a>`);
                        }else{
                            $('#trainingDocument').html('');
                        }
                    }
                })
            }

            function addGroundTraining(user, training, user_id, training_id) {
                $('#manageGroundTraining').modal('show');
                $('#manageGroundTraining .modal-title').text('Add Ground Training');
                $('#manageGroundTraining #crew_name').text(user);
                $('#manageGroundTraining #training').text(training);
                $('#manageGroundTraining form').attr('action', "{{route('app.pilot.groundTrainingStore')}}");
                $('#manageGroundTraining #training_id').val(training_id);
                $('#manageGroundTraining #user_id').val(user_id);
                $('#manageGroundTraining #edit_id').val('');
            }
            
            function deleteDocuments(docType,id)
            {
                $.ajax({
                    url: "{{route('app.pilot.certificat.delete')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        docType,
                        id,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                      location.reload();  
                    }
                });
            }
            
            function handleChange(checkbox) {
                
                if($(checkbox).is(':checked')){
                    var status='no'; 
                }else{
                    var status='yes'; 
               }
               let user_id=$(checkbox).attr('user-id');
               let type=$(checkbox).attr('certificat-type');
               let id=$(checkbox).attr('certificate-id');
               $.ajax({
                    url: "{{route('app.pilot.certificat.applicable')}}",
                    type: "post",
                    dataType: "json",
                    data: {
                        user_id,
                        type,
                        id,
                        status,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                      location.reload();  
                    }
                });
            }

        </script>
    </x-slot>
</x-app-layout>