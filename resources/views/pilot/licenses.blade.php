<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item ">PILOT </li>
            <li class="breadcrumb-item active">LICENSE / CERTIFICATE </li>
        </ul>
    </x-slot>
    <x-slot name="css">
        <link href="{{asset('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}"
            rel="stylesheet">
        <link href="{{asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}"
            rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}"
            rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}"
            rel="stylesheet" />
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
                <!--<a href="javascript:void(0);" class="btn btn-success btn-sm p-2">LTM Print Report</a>-->
                <a href="{{route('app.pilot')}}" class="btn btn-primary btn-sm p-2">Back</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h4><b>Crew : {{$user->fullName()}}</b></h4>
                </div>
                @foreach($certificates as $key => $certificate)
                @php 
                $licenses= \App\Models\UserCertificate::with('licenses')->where('user_id',$certificate->user_id)->where('certificate_type',$certificate->certificate_type)->get();
                @endphp
                <div class="col-md-12 bg-light mb-3">
                    <p class="m-2"><b>Total {{ ucwords(str_replace('_', ' ', $certificate->certificate_type)) }}: {{$licenses->count()}}</b></p>
                </div>
                <div class="col-md-12">
                    <table id="datatableLicense_{{$certificate->certificate_type}}" class="table text-nowrap w-100 datatableLicense">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type Info</th>
                                @if($certificate->certificate_type == 'training')
                                <th>Aircraft</th>
                                @endif
                                <th>Renewed On</th>
                                <th>Next Due</th>
                                <th>Remaining Days</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($licenses as $key => $value)
                            @php
                              
                            $result=checkUserCertificate($user->id,$value->master_id,$certificate->certificate_type);
                            @endphp
                            <td>{{$key+1}}</td>
                            <td>
                                {{$value->licenses?->name}}
                                @if(!empty($result)&&!empty($result->documents))
                                <a href="{{asset('uploads/pilot_certificate/'.$result->documents)}}" target="_blank"><i
                                        class="fa fa-eye text-success"></i></a>
                                @endif
                            </td>
                            @if($certificate->certificate_type == 'training')
                            <td>{{!empty($result)?$result->aircroft_registration:''}}</td>
                            @endif
                            <td>
                                @if($certificate->certificate_type == 'medical')
                                    {{!empty($result)?$result->medical_done_on:''}}
                                @else
                                    {{!empty($result)?$result->renewed_on:''}}
                                @endif
                            </td>
                            <td>{{!empty($result)?$result->next_due:''}}</td>
                            <td>
                                @if($value->is_lifetime=='yes')
                                <button type="button" class="btn btn-success btn-sm position-relative">
                                    Lifetime
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                        Valid
                                        <span class="visually-hidden">unread messages</span>
                                    </span>
                                </button>
                                @elseif($value->is_lifetime=='no'&&$value->is_mandatory == 'yes')
                                    @php
                                        if(!empty($result)) {
                                            $remaining_days = \Carbon\Carbon::parse(date('Y-m-d'))->diffInDays($result->next_due,false);
                                        }else{
                                            $remaining_days =null;
                                        }
                                    
                                        $btn_class = 'danger';
                                        $validity_text='Lapsed' ;
                                        if ($remaining_days !==null) {
                                            if ($remaining_days> 60) {
                                                $btn_class = 'primary'; // Blue for >60 days
                                                $validity_text = $remaining_days;
                                            } elseif ($remaining_days <= 60 && $remaining_days> 7) {
                                                $btn_class = 'warning'; // Orange for <=60 and>7 days
                                                $validity_text = $remaining_days;
                                            } elseif ($remaining_days > 0 && $remaining_days <= 7) { 
                                                $btn_class='danger' ;
                                                    // Red for <=7 days 
                                                $validity_text=$remaining_days;
                                            } 
                                        } 
                                    @endphp 
                                    <button  type="button" class="btn btn-{{ $btn_class }} btn-sm position-relative">
                                        {{ $validity_text }}
                                        @if(!empty($result) && strtotime($result->next_due) >  strtotime(date('Y-m-d')))
                                            <span
                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                                Valid
                                                <span class="visually-hidden">unread messages</span>
                                            </span>
                                        @endif
                                    </button>
                                @elseif($value->is_lifetime=='no'&&$value->is_mandatory =='no')
                                    <b></b>
                                @endif
                            </td>
                            <td>
                                {{!empty($result)?$result->status:''}}
                            </td>
                            <td>
                                @if($certificate->certificate_type == 'authorisation')
                                    @if(!empty($result))

                                    @else

                                    @endif
                                @endif
                                @if($certificate->certificate_type == 'license')
                                    @if(!empty($result))
                                        <a href="javascript:void(0);"
                                            onclick="editLicense('{{$user->fullName()}}','{{ucfirst($value->licenses?->name)}}','{{$user->id}}','{{$value->master_id}}','')"
                                            class="btn btn-primary btn-sm">Edit</a>
                                        <a href="javascript:void(0);"
                                            onclick="addLicense('{{$user->fullName()}}','{{ucfirst($value->licenses?->name)}}','{{$user->id}}','{{$value->master_id}}','')"
                                            class="btn btn-secondary btn-sm">Renew</a>
                                        <a href="{{ route('app.pilot.viewCertificateLogs', ['type' => 'licence-logs', 'user_id' => $user->id, 'id' => $value->master_id]) }}"
                                            class="btn btn-warning btn-sm">Log</a>
                                    @else
                                        <a href="javascript:void(0);"
                                            onclick="addLicense('{{$user->fullName()}}','{{ucfirst($value->licenses?->name)}}','{{$user->id}}','{{$value->master_id}}')"
                                            class="btn btn-primary btn-sm">Add</a>
                                    @endif
                                @endif

                                @if($certificate->certificate_type == 'training')
                                    @if(!empty($result))
                                        <a href="javascript:void(0);"
                                            onclick="editTraining('{{$user->fullName()}}','{{ucfirst($value->licenses?->name)}}','{{$user->id}}','{{$value->master_id}}');"
                                            class="btn btn-primary btn-sm">Edit</a>
                                        <a href="javascript:void(0);"
                                            onclick="addTraining('{{$user->fullName()}}','{{ucfirst($value->licenses?->name)}}','{{$user->id}}','{{$value->master_id}}');"
                                            class="btn btn-secondary btn-sm">Renew</a>
                                        <a href="{{ route('app.pilot.viewCertificateLogs', ['type' => 'training-logs', 'user_id' => $user->id, 'id' => $value->master_id]) }}"
                                            class="btn btn-warning btn-sm">Log</a>
                                    @else
                                        <a href="javascript:void(0);"
                                            onclick="addTraining('{{$user->fullName()}}','{{ucfirst($value->licenses?->name)}}','{{$user->id}}','{{$value->master_id}}');"
                                            class="btn btn-primary btn-sm p-2">Add</a>
                                    @endif
                                @endif
                                @if($certificate->certificate_type == 'medical')
                                    @if(!empty($result))
                                        <a href="javascript:void(0);"
                                            onclick="editMedical('{{$user->fullName()}}','{{ucfirst($value->licenses?->name)}}','{{$user->id}}','{{$value->master_id}}');"
                                            class="btn btn-primary btn-sm">Edit</a>
                                        <a href="javascript:void(0);"
                                            onclick="addMedical('{{$user->fullName()}}','{{ucfirst($value->licenses?->name)}}','{{$user->id}}','{{$value->master_id}}');"
                                            class="btn btn-secondary btn-sm">Renew</a>
                                        <a href="{{ route('app.pilot.viewCertificateLogs', ['type' => 'medical-logs', 'user_id' => $user->id, 'id' => $value->master_id]) }}"
                                            class="btn btn-warning btn-sm">Log</a>

                                    @else
                                        <a href="javascript:void(0);"
                                            onclick="addMedical('{{$user->fullName()}}','{{ucfirst($value->licenses?->name)}}','{{$user->id}}','{{$value->master_id}}');"
                                            class="btn btn-primary btn-sm p-2">Add</a>

                                    @endif
                                @endif
                                @if($certificate->certificate_type == 'qualification')
                                    @if(!empty($result))
                                        <a href="javascript:void(0);"
                                            onclick="editQualification('{{$user->fullName()}}','{{ucfirst($value->licenses?->name)}}','{{$user->id}}','{{$value->master_id}}');"
                                            class="btn btn-primary btn-sm">Edit</a>
                                        <a href="javascript:void(0);"
                                            onclick="addQualification('{{$user->fullName()}}','{{ucfirst($value->licenses?->name)}}','{{$user->id}}','{{$value->master_id}}');"
                                            class="btn btn-secondary btn-sm">Renew</a>
                                        <a href="{{ route('app.pilot.viewCertificateLogs', ['type' => 'qualification-logs', 'user_id' => $user->id, 'id' => $value->master_id]) }}"
                                            class="btn btn-warning btn-sm">Log</a>

                                    @else
                                        <a href="javascript:void(0);"
                                            onclick="addQualification('{{$user->fullName()}}','{{ucfirst($value->licenses?->name)}}','{{$user->id}}','{{$value->master_id}}');"
                                            class="btn btn-primary btn-sm p-2">Add</a>

                                    @endif
                                @endif

                                @if($certificate->certificate_type == 'ground_training')
                                    @if(!empty($result))
                                        <a href="javascript:void(0);"
                                            onclick="editGroundTraining('{{$user->fullName()}}','{{ucfirst($value->licenses?->name)}}','{{$user->id}}','{{$value->master_id}}');"
                                            class="btn btn-primary btn-sm">Edit</a>
                                        <a href="javascript:void(0);"
                                            onclick="addGroundTraining('{{$user->fullName()}}','{{ucfirst($value->licenses?->name)}}','{{$user->id}}','{{$value->master_id}}');"
                                            class="btn btn-secondary btn-sm">Renew</a>
                                        <a href="{{ route('app.pilot.viewCertificateLogs', ['type' => 'ground-training-logs', 'user_id' => $user->id, 'id' => $value->master_id]) }}"
                                            class="btn btn-warning btn-sm">Log</a>

                                    @else
                                        <a href="javascript:void(0);"
                                            onclick="addGroundTraining('{{$user->fullName()}}','{{ucfirst($value->licenses?->name)}}','{{$user->id}}','{{$value->master_id}}');"
                                            class="btn btn-primary btn-sm p-2">Add</a>

                                    @endif
                                @endif
                            </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="modal fade" id="manageModalLicense" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Manage License</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="licenseForm" class="" autocomplete="off" enctype=multipart/form-data>
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
                                    <input type="text" class="form-control datepicker" name="renewed_on" id="renewed_on"
                                        placeholder="Enter Renewed On" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="number">Number</label>
                                    <input type="text" class="form-control" name="number" id="number"
                                        placeholder="Enter Number" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="plannedRenewalDate">Planned Renewal Date</label>
                                    <input type="text" class="form-control datepicker"
                                        onchange="checkDate('renewed_on' ,'planned_renewal_date');"
                                        name="plannedRenewalDate" id="planned_renewal_date"
                                        placeholder="Enter Planned Renewal Date" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="issued_on">Issued On</label>
                                    <input type="text" class="form-control datepicker"
                                        onchange="checkDate('issued_on' ,'renewed_on');" name="issued_on" id="issued_on"
                                        placeholder="Enter Issued On" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="extended_date">Extended Date</label>
                                    <input type="text" class="form-control datepicker"
                                        onchange="checkDate('renewed_on' ,'extended_date');" name="extended_date"
                                        id="extended_date" placeholder="Enter Extended Date" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="renewal_office">Renewal Office</label>
                                    <input type="text" class="form-control" name="renewal_office" id="renewal_office"
                                        placeholder="Enter Renewal Office" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="next_due">Next Due<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control datepicker"
                                        onchange="checkDate('renewed_on' ,'next_due');" name="next_due" id="next_due"
                                        placeholder="Enter Next Due" />
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
                        <button type="submit" class="btn btn-primary btn-submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="manageModalTraining" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Manage Training</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="trainingForm" class="" autocomplete="off" enctype=multipart/form-data>
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
                                    <input type="text" class="form-control datepicker" name="renewed_on" id="renewed_on"
                                        placeholder="Enter Renewed On" />
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
                                    <input type="text" class="form-control datepicker"
                                        onchange="checkDate('trainingForm #renewed_on' ,'trainingForm #planned_renewal_date');"
                                        name="planned_renewal_date" id="planned_renewal_date"
                                        placeholder="Enter Planned Renewal Date" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="examiner">Examiner</label>
                                    <input type="text" class="form-control" name="examiner" id="examiner"
                                        placeholder="Enter Examiner" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="extended_date">Extended Date</label>
                                    <input type="text" class="form-control datepicker"
                                        onchange="checkDate('trainingForm #renewed_on' ,'trainingForm #extended_date');"
                                        name="extended_date" id="extended_date" placeholder="Enter Extended Date" />
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
                                    <label class="form-label" for="next_due">Next Due<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control datepicker"
                                        onchange="checkDate('trainingForm #renewed_on' ,'trainingForm #next_due');"
                                        name="next_due" id="next_due" placeholder="Enter Next Due" />
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
                                    <input type="text" class="form-control" name="aircroft_registration"
                                        id="aircroft_registration" placeholder="Enter Aircraft Registration" />
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
                                    <input type="text" class="form-control" name="aircroft_model" id="aircroft_model"
                                        placeholder="Enter Aircraft Model" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="P1_hours">P1 Hours</label>
                                    <input type="text" class="form-control" name="P1_hours" id="P1_hours"
                                        placeholder="Enter P1 Hours" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="P2_hours">P2 Hours</label>
                                    <input type="text" class="form-control" name="P2_hours" id="P2_hours"
                                        placeholder="Enter P2 Hours" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="renewal_office">Renewal Office</label>
                                    <input type="text" class="form-control" name="renewal_office" id="renewal_office"
                                        placeholder="Enter Renewal Office" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="place_of_test">Place of Test</label>
                                    <input type="text" class="form-control" name="place_of_test" id="place_of_test"
                                        placeholder="Enter Place of Test" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="approach_details">Approach Details</label>
                                    <input type="text" class="form-control" name="approach_details"
                                        id="approach_details" placeholder="Enter Approach Details" />
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
                        <button type="submit" class="btn btn-primary btn-submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="manageModalMedical" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Manage Medical</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="medicalForm" class="" enctype=multipart/form-data>
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
                                    <input type="text" class="form-control datepicker" name="medical_done_on"
                                        id="medical_done_on" placeholder="Enter Medical Done On" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="medical_done_at">Medical Done At</label>
                                    <input type="text" class="form-control" name="medical_done_at" id="medical_done_at"
                                        placeholder="Enter Medical Done At" />
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
                                    <input type="text" class="form-control datepicker"
                                        onchange="checkDate('medicalForm #medical_done_on','medicalForm #planned_renewal_date');"
                                        name="planned_renewal_date" id="planned_renewal_date"
                                        placeholder="Enter Planned Renewal Date" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="extended_date">Extended Date</label>
                                    <input type="text" class="form-control datepicker"
                                        onchange="checkDate('medicalForm #medical_done_on','medicalForm #extended_date');"
                                        name="extended_date" id="extended_date" placeholder="Enter Extended Date" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="mandatory_medical_center_count">Mandatory Medical
                                        Center Count</label>
                                    <input type="text" class="form-control" name="mandatory_medical_center_count"
                                        id="mandatory_medical_center_count"
                                        placeholder="Enter Mandatory Medical Center Count" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="next_due">Next Due<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control datepicker"
                                        onchange="checkDate('medicalForm #medical_done_on','medicalForm #next_due');"
                                        name="next_due" id="next_due" placeholder="Enter Next Due" />
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
                        <button type="submit" class="btn btn-primary btn-submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="manageQualification" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Manage Qualification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="qualificationForm" class="" autocomplete="off" enctype=multipart/form-data>
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
                                    <input type="text" class="form-control datepicker" name="renewed_on" id="renewed_on"
                                        placeholder="Enter Renewed On" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="number">Number</label>
                                    <input type="text" class="form-control" name="number" id="number"
                                        placeholder="Enter Number" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="plannedRenewalDate">Planned Renewal Date</label>
                                    <input type="text" class="form-control datepicker"
                                        onchange="checkDate('renewed_on' ,'planned_renewal_date');"
                                        name="plannedRenewalDate" id="planned_renewal_date"
                                        placeholder="Enter Planned Renewal Date" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="issued_on">Issued On</label>
                                    <input type="text" class="form-control datepicker"
                                        onchange="checkDate('issued_on' ,'renewed_on');" name="issued_on" id="issued_on"
                                        placeholder="Enter Issued On" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="extended_date">Extended Date</label>
                                    <input type="text" class="form-control datepicker"
                                        onchange="checkDate('renewed_on' ,'extended_date');" name="extended_date"
                                        id="extended_date" placeholder="Enter Extended Date" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="renewal_office">Renewal Office</label>
                                    <input type="text" class="form-control" name="renewal_office" id="renewal_office"
                                        placeholder="Enter Renewal Office" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="next_due">Next Due<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control datepicker"
                                        onchange="checkDate('renewed_on' ,'next_due');" name="next_due" id="next_due"
                                        placeholder="Enter Next Due" />
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
                        <button type="submit" class="btn btn-primary btn-submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="manageGroundTraining" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Manage Ground Training</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="GroundTrainingForm" class="" autocomplete="off"
                    enctype=multipart/form-data>
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
                                    <input type="text" class="form-control datepicker" name="renewed_on" id="renewed_on"
                                        placeholder="Enter Renewed On" />
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
                                    <input type="text" class="form-control datepicker"
                                        onchange="checkDate('GroundTrainingForm #renewed_on' ,'GroundTrainingForm #planned_renewal_date');"
                                        name="planned_renewal_date" id="planned_renewal_date"
                                        placeholder="Enter Planned Renewal Date" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="examiner">Examiner</label>
                                    <input type="text" class="form-control" name="examiner" id="examiner"
                                        placeholder="Enter Examiner" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="extended_date">Extended Date</label>
                                    <input type="text" class="form-control datepicker"
                                        onchange="checkDate('GroundTrainingForm #renewed_on' ,'GroundTrainingForm #extended_date');"
                                        name="extended_date" id="extended_date" placeholder="Enter Extended Date" />
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
                                    <label class="form-label" for="next_due">Next Due<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control datepicker"
                                        onchange="checkDate('GroundTrainingForm #renewed_on' ,'GroundTrainingForm #next_due');"
                                        name="next_due" id="next_due" placeholder="Enter Next Due" />
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
                                    <input type="text" class="form-control" name="aircroft_registration"
                                        id="aircroft_registration" placeholder="Enter Aircraft Registration" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="aircroft_type">Aircraft Type</label>
                                    <input type="text" class="form-control" name="aircroft_type" id="aircroft_type"
                                        placeholder="Enter Aircraft Type" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="aircroft_model">Aircraft Model</label>
                                    <input type="text" class="form-control" name="aircroft_model" id="aircroft_model"
                                        placeholder="Enter Aircraft Model" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="P1_hours">P1 Hours</label>
                                    <input type="text" class="form-control" name="P1_hours" id="P1_hours"
                                        placeholder="Enter P1 Hours" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="P2_hours">P2 Hours</label>
                                    <input type="text" class="form-control" name="P2_hours" id="P2_hours"
                                        placeholder="Enter P2 Hours" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="renewal_office">Renewal Office</label>
                                    <input type="text" class="form-control" name="renewal_office" id="renewal_office"
                                        placeholder="Enter Renewal Office" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="place_of_test">Place of Test</label>
                                    <input type="text" class="form-control" name="place_of_test" id="place_of_test"
                                        placeholder="Enter Place of Test" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="approach_details">Approach Details</label>
                                    <input type="text" class="form-control" name="approach_details"
                                        id="approach_details" placeholder="Enter Approach Details" />
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
                        <button type="submit" class="btn btn-primary btn-submit">Submit</button>
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
        <script src="{{asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js')}}">
        </script>
        <script>
            function validateForm(form) {
                var next_due = form.find('#next_due');
                var next_due_val = next_due.val();
    
                // Clear previous validation messages
                next_due.removeClass('is-invalid');
                next_due.parent().find('.invalid-feedback').remove();
    
                if (next_due_val === '') {
                    next_due.addClass('is-invalid');
                    next_due.parent().append('<div class="invalid-feedback">The next due field is required.</div>');
                    return false;
                }
    
                return true; // Return true if the validation passes
            }
    
            $('.btn-submit').on('click', function(e) {
                e.preventDefault(); // Prevent default form submission
                var form = $(this).closest('form');
    
                // Call validateForm and check if it returns true
                if (validateForm(form)) {
                    form.submit(); // If validation passes, submit the form
                }
            });
            
            $('.datepicker').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy',
                calendarWeeks: true,
                zIndexOffset: 9999
            });
    
            function checkDate(fromDate, toDate) {
                let fdate = $('#' + fromDate).val();
                let ldate = $('#' + toDate).val();
                console.log(fdate);
                if (new Date(fdate) >= new Date(ldate)) {
                    $('#' + toDate).val('');
                    warning('This Date should be greater than Done / Renewed Date');
                    return false;
                }
            }
    
            function dataList() {
                $('.datatableLicense').DataTable().destroy();
                $('.datatableLicense').DataTable({
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
    
            function editLicense(user, license, user_id, license_id, more_data) {
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
                        clearError($('#licenseForm'));
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
                        if (more_data == 'lifetime') {
                            $('#manageModalLicense #next_due').val('').attr('disabled', true);
                            $('#manageModalLicense #planned_renewal_date').val('').attr('disabled', true);
                            $('#manageModalLicense #extended_date').val('').attr('disabled', true);
                        } else {
                            $('#manageModalLicense #next_due').attr('disabled', false);
                            $('#manageModalLicense #planned_renewal_date').attr('disabled', false);
                            $('#manageModalLicense #extended_date').attr('disabled', false);
                        }
                        if (data.documents.length > 0) {
                            $('#licenseDocument').html(`<a href="{{asset('uploads/pilot_certificate')}}/`+data.documents +`" class="btn btn-sm btn-primary m-1" target="_blank">View</a><a href="javascript:void(0);" onclick="deleteDocuments('license','` +data.id + `');" class="btn btn-sm btn-danger m-1">Delete</a>`);
                        } else {
                            $('#licenseDocument').html('');
                        }
                    }
                });
            }
    
            function addLicense(user, license, user_id, license_id, more_data) {
                clearError($('#licenseForm'));
                $('#manageModalLicense form')[0].reset();
                $('#manageModalLicense').modal('show');
                $('#manageModalLicense .modal-title').text('Add License');
                $('#manageModalLicense #crew_name').text(user);
                $('#manageModalLicense #license').text(license);
                $('#manageModalLicense form').attr('action', "{{route('app.pilot.license.store')}}");
                $('#manageModalLicense #license_id').val(license_id);
                $('#manageModalLicense #user_id').val(user_id);
                $('#manageModalLicense #edit_id').val('');
                if (more_data == 'lifetime') {
                    $('#manageModalLicense #next_due').val('').attr('disabled', true);
                    $('#manageModalLicense #planned_renewal_date').val('').attr('disabled', true);
                    $('#manageModalLicense #extended_date').val('').attr('disabled', true);
    
                } else {
                    $('#manageModalLicense #next_due').attr('disabled', false);
                    $('#manageModalLicense #planned_renewal_date').attr('disabled', false);
                    $('#manageModalLicense #extended_date').attr('disabled', false);
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
                        clearError($('#trainingForm'));
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
                        if (data.documents.length > 0) {
                            $('#trainingDocument').html(`<a href="{{asset('uploads/pilot_certificate')}}/` +data.documents +`" class="btn btn-sm btn-primary m-1" target="_blank">View</a><a href="javascript:void(0);" onclick="deleteDocuments('training','` + data.id + `');" class="btn btn-sm btn-danger m-1">Delete</a>`);
                        } else {
                            $('#trainingDocument').html('');
                        }
                    }
                })
            }
    
            function addTraining(user, training, user_id, training_id) {
                clearError($('#trainingForm'));
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
                        clearError($('#medicalForm'));
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
                        $('#manageModalMedical #mandatory_medical_center_count').val(data
                            .mandatory_medical_center_count);
                        $('#manageModalMedical #medical_done_at').val(data.medical_done_at);
                        $('#manageModalMedical #medical_done_on').val(data.medical_done_on);
                        $('#manageModalMedical #medical_result').val(data.medical_result);
                        $('#manageModalMedical #planned_renewal_date').val(data.planned_renewal_date);
                        $('#manageModalMedical #extended_date').val(data.extended_date);
                        if (data.documents.length > 0) {
                            $('#medicalDocument').html(`<a href="{{asset('uploads/pilot_certificate')}}/` + data.documents +`" class="btn btn-sm btn-primary m-1" target="_blank">View</a><a href="javascript:void(0);" onclick="deleteDocuments('medical','` +data.id + `');" class="btn btn-sm btn-danger m-1">Delete</a>`);
                        } else {
                            $('#medicalDocument').html('');
                        }
                    }
                });
            }
    
            function addMedical(user, medical, user_id, medical_id) {
                clearError($('#medicalForm'));
                $('#manageModalMedical').modal('show');
                $('#manageModalMedical .modal-title').text('Add Medical');
                $('#manageModalMedical #crew_name').text(user);
                $('#manageModalMedical #medical').text(medical);
                $('#manageModalMedical form').attr('action', "{{route('app.pilot.medical.store')}}");
                $('#manageModalMedical #medical_id').val(medical_id);
                $('#manageModalMedical #user_id').val(user_id);
                $('#manageModalMedical #edit_id').val('');
            }
    
            function editQualification(user, qualification, user_id, qualification_id, more_data) {
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
                        clearError($('#qualificationForm'));
                        $('#manageQualification').modal('show');
                        $('#manageQualification .modal-title').text('Edit Qualification');
                        $('#manageQualification #crew_name').text(user);
                        $('#manageQualification #qualification').text(qualification);
                        $('#manageQualification form').attr('action',
                            "{{route('app.pilot.qualification.update')}}");
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
                        if (more_data == 'lifetime') {
                            $('#manageQualification #next_due').val('').attr('disabled', true);
                            $('#manageQualification #planned_renewal_date').val('').attr('disabled', true);
                            $('#manageQualification #extended_date').val('').attr('disabled', true);
                        } else {
                            $('#manageQualification #next_due').attr('disabled', false);
                            $('#manageQualification #planned_renewal_date').attr('disabled', false);
                            $('#manageQualification #extended_date').attr('disabled', false);
                        }
                        if (data.documents.length > 0) {
                            $('#licenseDocument').html(`<a href="{{asset('uploads/pilot_certificate')}}/` + data.documents +`" class="btn btn-sm btn-primary m-1" target="_blank">View</a><a href="javascript:void(0);" onclick="deleteDocuments('license','` +data.id + `');" class="btn btn-sm btn-danger m-1">Delete</a>`);
                        } else {
                            $('#licenseDocument').html('');
                        }
                    }
                });
            }
    
            function addQualification(user, qualification, user_id, qualification_id, more_data) {
                clearError($('#qualificationForm'));
                $('#manageQualification form')[0].reset();
                $('#manageQualification').modal('show');
                $('#manageQualification .modal-title').text('Add Qualification');
                $('#manageQualification #crew_name').text(user);
                $('#manageQualification #qualification').text(qualification);
                $('#manageQualification form').attr('action', "{{route('app.pilot.qualification.store')}}");
                $('#manageQualification #qualification_id').val(qualification_id);
                $('#manageQualification #user_id').val(user_id);
                $('#manageQualification #edit_id').val('');
                if (more_data == 'lifetime') {
                    $('#manageQualification #next_due').val('').attr('disabled', true);
                    $('#manageQualification #planned_renewal_date').val('').attr('disabled', true);
                    $('#manageQualification #extended_date').val('').attr('disabled', true);
    
                } else {
                    $('#manageQualification #next_due').attr('disabled', false);
                    $('#manageQualification #planned_renewal_date').attr('disabled', false);
                    $('#manageQualification #extended_date').attr('disabled', false);
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
                        clearError($('#GroundTrainingForm'));
                        $('#manageGroundTraining').modal('show');
                        $('#manageGroundTraining .modal-title').text('Edit Ground Training');
                        $('#manageGroundTraining #crew_name').text(user);
                        $('#manageGroundTraining #training').text(training);
                        $('#manageGroundTraining form').attr('action',
                            "{{route('app.pilot.groundTrainingUpdate')}}");
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
                        if (data.documents.length > 0) {
                            $('#trainingDocument').html(`<a href="{{asset('uploads/pilot_certificate')}}/`+data.documents +`" class="btn btn-sm btn-primary m-1" target="_blank">View</a><a href="javascript:void(0);" onclick="deleteDocuments('training','` +data.id + `');" class="btn btn-sm btn-danger m-1">Delete</a>`);
                        } else {
                            $('#trainingDocument').html('');
                        }
                    }
                })
            }
    
            function addGroundTraining(user, training, user_id, training_id) {
                clearError($('#GroundTrainingForm'));
                $('#manageGroundTraining').modal('show');
                $('#manageGroundTraining .modal-title').text('Add Ground Training');
                $('#manageGroundTraining #crew_name').text(user);
                $('#manageGroundTraining #training').text(training);
                $('#manageGroundTraining form').attr('action', "{{route('app.pilot.groundTrainingStore')}}");
                $('#manageGroundTraining #training_id').val(training_id);
                $('#manageGroundTraining #user_id').val(user_id);
                $('#manageGroundTraining #edit_id').val('');
            }
    
            function deleteDocuments(docType, id) {
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
    
                if ($(checkbox).is(':checked')) {
                    var status = 'no';
                } else {
                    var status = 'yes';
                }
                let user_id = $(checkbox).attr('user-id');
                let type = $(checkbox).attr('certificat-type');
                let id = $(checkbox).attr('certificate-id');
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