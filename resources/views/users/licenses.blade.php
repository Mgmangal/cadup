<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item ">EMPLOYEE </li>
            <li class="breadcrumb-item active">LICENSE / CERTIFICATE </li>
        </ul>
    </x-slot>
    <x-slot name="css">
    </x-slot>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Validation -->
    <x-errors class="mb-4" />
    <x-success class="mb-4" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">License / Certificate</h3>
            <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
        </div>
        <div class="card-body">
            <form action="{{route('app.users.licenses.store', $user->id)}}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-12 p-2">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Is Lifetime</th>
                                    <th>Is Mandatory</th>
                                    <th>Current For Flying</th>
                                    <th>Applicable</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6"><strong>License : {{ $licenses->count() }}</strong></td>
                                </tr>
                                @foreach($licenses as $k => $value)
                                    @php
                                    $master_id = $value->id;
                                    $userCertificate = \App\Models\UserCertificate::where('master_id', $master_id)->where('user_id', $user->id)->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $value->name }}</td>
                                        <td>
                                            {{ ucwords(implode(' ', explode('_', $value->sub_type))) }}
                                            <input type="hidden" name="certificate_type[{{ $value->id }}]"
                                                value="{{ $value->sub_type }}">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="is_lifetime[{{ $value->id }}]" value="yes"
                                                {{ !empty($userCertificate) && $userCertificate->is_lifetime == 'yes' ? 'checked' : '' }}
                                                class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="is_mandatory[{{ $value->id }}]" value="yes"
                                                {{ !empty($userCertificate) && $userCertificate->is_mandatory == 'yes' ? 'checked' : '' }}
                                                class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="id_current_for_flying[{{ $value->id }}]"
                                                value="yes"
                                                {{ !empty($userCertificate) && $userCertificate->id_current_for_flying == 'yes' ? 'checked' : '' }}
                                                class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="master_id[{{ $value->id }}]"
                                                value="{{ $value->id }}" {{ !empty($userCertificate) ? 'checked' : '' }}
                                                class="form-check-input">
                                                
                                        </td>
                                    </tr>
                                @endforeach
                                
                                <tr>
                                    <td colspan="6"><strong>Training : {{ $trainings->count() }}</strong></td>
                                </tr>
                                @foreach($trainings as $k => $value)
                                    @php
                                    $master_id = $value->id;
                                    $userCertificate = \App\Models\UserCertificate::where('master_id', $master_id)->where('user_id', $user->id)->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $value->name }}</td>
                                        <td>
                                            {{ ucwords(implode(' ', explode('_', $value->sub_type))) }}
                                            <input type="hidden" name="certificate_type[{{ $value->id }}]"
                                                value="{{ $value->sub_type }}">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="is_lifetime[{{ $value->id }}]" value="yes"
                                                {{ !empty($userCertificate) && $userCertificate->is_lifetime == 'yes' ? 'checked' : '' }}
                                                class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="is_mandatory[{{ $value->id }}]" value="yes"
                                                {{ !empty($userCertificate) && $userCertificate->is_mandatory == 'yes' ? 'checked' : '' }}
                                                class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="id_current_for_flying[{{ $value->id }}]"
                                                value="yes"
                                                {{ !empty($userCertificate) && $userCertificate->id_current_for_flying == 'yes' ? 'checked' : '' }}
                                                class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="master_id[{{ $value->id }}]"
                                                value="{{ $value->id }}" {{ !empty($userCertificate) ? 'checked' : '' }}
                                                class="form-check-input">
                                                
                                        </td>
                                    </tr>
                                @endforeach
                                
                                <tr>
                                    <td colspan="6"><strong>Qualification : {{ $qualifications->count() }}</strong></td>
                                </tr>
                                @foreach($qualifications as $k => $value)
                                    @php
                                    $master_id = $value->id;
                                    $userCertificate = \App\Models\UserCertificate::where('master_id', $master_id)->where('user_id', $user->id)->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $value->name }}</td>
                                        <td>
                                            {{ ucwords(implode(' ', explode('_', $value->sub_type))) }}
                                            <input type="hidden" name="certificate_type[{{ $value->id }}]"
                                                value="{{ $value->sub_type }}">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="is_lifetime[{{ $value->id }}]" value="yes"
                                                {{ !empty($userCertificate) && $userCertificate->is_lifetime == 'yes' ? 'checked' : '' }}
                                                class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="is_mandatory[{{ $value->id }}]" value="yes"
                                                {{ !empty($userCertificate) && $userCertificate->is_mandatory == 'yes' ? 'checked' : '' }}
                                                class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="id_current_for_flying[{{ $value->id }}]"
                                                value="yes"
                                                {{ !empty($userCertificate) && $userCertificate->id_current_for_flying == 'yes' ? 'checked' : '' }}
                                                class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="master_id[{{ $value->id }}]"
                                                value="{{ $value->id }}" {{ !empty($userCertificate) ? 'checked' : '' }}
                                                class="form-check-input">
                                                
                                        </td>
                                    </tr>
                                @endforeach
                                
                                <tr>
                                    <td colspan="6"><strong>Medical : {{ $medicals->count() }}</strong></td>
                                </tr>
                                @foreach($medicals as $k => $value)
                                    @php
                                    $master_id = $value->id;
                                    $userCertificate = \App\Models\UserCertificate::where('master_id', $master_id)->where('user_id', $user->id)->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $value->name }}</td>
                                        <td>
                                            {{ ucwords(implode(' ', explode('_', $value->sub_type))) }}
                                            <input type="hidden" name="certificate_type[{{ $value->id }}]"
                                                value="{{ $value->sub_type }}">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="is_lifetime[{{ $value->id }}]" value="yes"
                                                {{ !empty($userCertificate) && $userCertificate->is_lifetime == 'yes' ? 'checked' : '' }}
                                                class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="is_mandatory[{{ $value->id }}]" value="yes"
                                                {{ !empty($userCertificate) && $userCertificate->is_mandatory == 'yes' ? 'checked' : '' }}
                                                class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="id_current_for_flying[{{ $value->id }}]"
                                                value="yes"
                                                {{ !empty($userCertificate) && $userCertificate->id_current_for_flying == 'yes' ? 'checked' : '' }}
                                                class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="master_id[{{ $value->id }}]"
                                                value="{{ $value->id }}" {{ !empty($userCertificate) ? 'checked' : '' }}
                                                class="form-check-input">
                                                
                                        </td>
                                    </tr>
                                @endforeach
                                
                                <tr>
                                    <td colspan="6"><strong>Ground Training : {{ $ground_trainings->count() }}</strong></td>
                                </tr>
                                @foreach($ground_trainings as $k => $value)
                                    @php
                                    $master_id = $value->id;
                                    $userCertificate = \App\Models\UserCertificate::where('master_id', $master_id)->where('user_id', $user->id)->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $value->name }}</td>
                                        <td>
                                            {{ ucwords(implode(' ', explode('_', $value->sub_type))) }}
                                            <input type="hidden" name="certificate_type[{{ $value->id }}]"
                                                value="{{ $value->sub_type }}">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="is_lifetime[{{ $value->id }}]" value="yes"
                                                {{ !empty($userCertificate) && $userCertificate->is_lifetime == 'yes' ? 'checked' : '' }}
                                                class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="is_mandatory[{{ $value->id }}]" value="yes"
                                                {{ !empty($userCertificate) && $userCertificate->is_mandatory == 'yes' ? 'checked' : '' }}
                                                class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="id_current_for_flying[{{ $value->id }}]"
                                                value="yes"
                                                {{ !empty($userCertificate) && $userCertificate->id_current_for_flying == 'yes' ? 'checked' : '' }}
                                                class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="master_id[{{ $value->id }}]"
                                                value="{{ $value->id }}" {{ !empty($userCertificate) ? 'checked' : '' }}
                                                class="form-check-input">
                                                
                                        </td>
                                    </tr>
                                @endforeach
                                
                            @if(0)    
                            @php
                                // Assuming $license is a collection of License objects
                                $groupedBySubType = $license->groupBy('sub_type');
                            @endphp
                            @foreach($groupedBySubType as $subType => $licenses)
                                <tr>
                                    <td colspan="6"><strong>{{ ucwords(str_replace('_', ' ', $subType)) }} : {{ $licenses->count() }}</strong></td>
                                </tr>
                                @foreach($licenses as $key => $value)
                                    @php
                                        $master_id = $value->id;
                                        // Retrieve the user certificate relationship with the required pivot data
                                        $userLicence = \App\Models\User::whereHas('certificates', function ($query) use
                                        ($master_id) {
                                                $query->where('master_id', $master_id);
                                        })->with(['certificates' => function ($query) use ($master_id) {
                                            $query->where('master_id', $master_id);
                                        }])->where('id', $user->id)->first();
                                        $userCertificate = $userLicence ? $userLicence->certificates->first() : null;
                                    @endphp
                                    <tr>
                                        <td>{{ $value->name }}</td>
                                        <td>
                                            {{ ucwords(implode(' ', explode('_', $value->sub_type))) }}
                                            <input type="hidden" name="certificate_type[{{ $value->id }}]"
                                                value="{{ $value->sub_type }}">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="is_lifetime[{{ $value->id }}]" value="yes"
                                                {{ $userCertificate && $userCertificate->pivot->is_lifetime == 'yes' ? 'checked' : '' }}
                                                class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="is_mandatory[{{ $value->id }}]" value="yes"
                                                {{ $userCertificate && $userCertificate->pivot->is_mandatory == 'yes' ? 'checked' : '' }}
                                                class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="id_current_for_flying[{{ $value->id }}]"
                                                value="yes"
                                                {{ $userCertificate && $userCertificate->pivot->id_current_for_flying == 'yes' ? 'checked' : '' }}
                                                class="form-check-input">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" name="master_id[{{ $value->id }}]"
                                                value="{{ $value->id }}" {{ $userCertificate ? 'checked' : '' }}
                                                class="form-check-input">
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                            @endif
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-primary btn-md float-center">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>




    <x-slot name="js">

    </x-slot>
</x-app-layout>