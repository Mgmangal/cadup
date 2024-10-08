<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item"><a href="{{route('app.flying-details')}}">AAI REPORTS</a></li>
            <li class="breadcrumb-item active">EDIT AAI REPORT</li>
        </ul>
    </x-slot>
    <x-slot name="css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <!-- <link href="{{asset('assets/plugins/select-picker/dist/picker.min.css')}}" rel="stylesheet" /> -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    </x-slot>
    <!-- Errors -->
    <x-errors class="mb-4" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Edit AAI Report</h3>
            <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
        </div>
        <div class="card-body">
            <form action="{{route('app.aai_report.store')}}" method="POST" enctype="multipart/form-data" id="manageForm">
                @csrf
                <div class="row m-3">
                    <input type="hidden" name="edit_id" value="{{$data->id}}" class="form-control">
                    <input type="hidden" name="flying_log_id" value="{{$data->flying_log_id}}" class="form-control">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="d_i_ind" class="form-label">D I IND<span class="text-danger">*</span></label>
                            <select name="d_i_ind" id="d_i_ind" class="form-control">
                                <!--<option value="">Select</option>-->
                                <!--<option value="I" {{ (isset($data->d_i_ind) && $data->d_i_ind == 'I') ? 'selected' : '' }}>I</option>-->
                                <option value="D" {{ (isset($data->d_i_ind) && $data->d_i_ind == 'D') ? 'selected' : '' }}>D</option>
                            </select>
                            @error('d_i_ind')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="rcs_ind" class="form-label">RCS IND<span class="text-danger">*</span></label>
                            <select name="rcs_ind" id="rcs_ind" class="form-control">
                                <!--<option value="">Select</option>-->
                                <!--<option value="RCS" {{ (isset($data->rcs_ind) && $data->rcs_ind == 'RCS') ? 'selected' : '' }}>RCS</option>-->
                                <option value="Non RCS" {{ (isset($data->rcs_ind) && $data->rcs_ind == 'Non RCS') ? 'selected' : '' }}>Non RCS</option>
                            </select>
                            @error('rcs_ind')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Booking Date</label>
                            <input type="text" name="booking_date" class="form-control dates" value="{{Carbon\Carbon::parse(is_get_date_time_format($data->booking_date),'UTC')->setTimezone('Asia/Kolkata')->format('d-m-Y H:i')}}">
                            <!--<input type="text" name="booking_date" class="form-control dates" value="{{\Carbon\Carbon::parse($data->booking_date)->format('d/m/Y h:i')}}">-->
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Modification Date</label>
                            <input type="text" name="modification_date" class="form-control dates" value="{{Carbon\Carbon::parse(is_get_date_time_format($data->modification_date),'UTC')->setTimezone('Asia/Kolkata')->format('d-m-Y H:i')}}">
                            <!--<input type="text" name="modification_date" class="form-control dates" value="{{\Carbon\Carbon::parse($data->modification_date)->format('d/m/Y h:i')}}">-->
                       </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Original PNR</label>
                            <input type="text" class="form-control auto_complete_input" name="original_pnr" value="{{ $data->original_pnr ?? '' }}" placeholder="Please Enter Original PNR">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Parent PNR</label>
                            <input type="text" class="form-control auto_complete_input" name="parent_pnr" value="{{ $data->parent_pnr ?? '' }}" placeholder="Please Enter Parent PNR">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Tail Number</label>
                            <input type="text" class="form-control auto_complete_input" name="tail_number" value="{{ $data->tail_number ?? '' }}" placeholder="Please Enter Tail Number">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Departure Date</label>
                            <input type="text" name="departure_date" class="form-control dates" value="{{Carbon\Carbon::parse(is_get_date_time_format($data->departure_date),'UTC')->setTimezone('Asia/Kolkata')->format('d-m-Y H:i')}}">
                            <!--<input type="text" name="departure_date" class="form-control dates" value="{{\Carbon\Carbon::parse($data->departure_date)->format('d-m-Y h:i')}}">-->
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Departure Time UTC</label>
                            <input type="text" name="departure_date_utc" class="form-control dates" value="{{Carbon\Carbon::parse(is_get_date_time_format($data->departure_date_utc),'UTC')->format('d-m-Y H:i')}}">
                            <!--<input type="text" name="departure_date_utc" class="form-control dates" value="{{\Carbon\Carbon::parse($data->departure_date_utc)->setTimezone('UTC')->format('d-m-Y h:i')}}">-->
                       </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Departure Time Local</label>
                            <input type="text" name="departure_date_local" class="form-control dates" value="{{Carbon\Carbon::parse(is_get_date_time_format($data->departure_date_local),'UTC')->setTimezone('Asia/Kolkata')->format('d-m-Y H:i')}}">
                            <!--<input type="text" name="departure_date_local" class="form-control dates" value="{{\Carbon\Carbon::parse($data->departure_date_local)->format('d-m-Y h:i')}}">-->
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Flight Number</label>
                            <input type="text" class="form-control auto_complete_input" name="flight_number" value="{{ $data->flight_number ?? '' }}" placeholder="Please Enter Comment">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">PNR Actual Departure Station</label>
                            <input type="text" class="form-control auto_complete_input" name="pnr_actual_departure_station" value="{{ $data->pnr_actual_departure_station ?? '' }}" placeholder="Please Enter Actual Departure Station">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Departure Station</label>
                            <input type="text" class="form-control auto_complete_input" name="departure_station" value="{{ $data->departure_station ?? '' }}" placeholder="Please Enter Comment">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Arrival Station</label>
                            <input type="text" class="form-control auto_complete_input" name="arrival_station" value="{{ $data->arrival_station ?? '' }}" placeholder="Please Enter Comment">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Final Station</label>
                            <input type="text" class="form-control auto_complete_input" name="final_station" value="{{ $data->final_station ?? '' }}" placeholder="Please Enter Comment">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Nationality</label>
                            <input type="text" class="form-control auto_complete_input" name="nationality" value="{{ $data->nationality ?? '' }}" placeholder="Please Enter Nationality">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Carrier Code</label>
                            <input type="text" class="form-control auto_complete_input" name="carrier_code" value="{{ $data->carrier_code ?? '' }}" placeholder="Please Enter Carrier Code">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Total Pax</label>
                            <input type="number" class="form-control auto_complete_input" name="total_pax" onchange="setStatus(this.value);"  value="{{ $data->total_pax ?? '' }}" min="0" placeholder="Total number of Passenger">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Adult Count</label>
                            <input type="number" class="form-control auto_complete_input" name="adult_count" value="{{ $data->adult_count ?? '' }}" min="0" placeholder="Total number of Passenger">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Child Count</label>
                            <input type="number" class="form-control auto_complete_input" name="child_count" value="{{ $data->child_count ?? '' }}" min="0" placeholder="Please Enter Child Count">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Infant Count</label>
                            <input type="number" class="form-control auto_complete_input" name="infant_count" value="{{ $data->infant_count ?? '' }}" min="0" placeholder="Please Enter Infant Count">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Sky Marshall Count</label>
                            <input type="number" class="form-control auto_complete_input" name="sky_marshall_count" value="{{ $data->sky_marshall_count ?? '' }}" min="0" placeholder="Please Enter Sky Marshall Count">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Connection Status Embarkation</label>
                            <input type="text" class="form-control auto_complete_input" name="embarkation_connection_status" value="{{ $data->embarkation_connection_status ?? '' }}" placeholder="Please Enter Connection Status Embarkation">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Connection Status Disembarkation</label>
                            <input type="text" class="form-control auto_complete_input" name="disembarkation_connection_status" value="{{ $data->disembarkation_connection_status ?? '' }}" placeholder="Please Enter Connection Status Disembarkation">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Flight Status</label>
                            <input type="text" class="form-control auto_complete_input" name="flight_status" value="{{ $data->flight_status ?? '' }}" placeholder="Please Enter Flight Status">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">PNR Status</label>
                            <input type="text" class="form-control auto_complete_input" name="pnr_status" id="pnr_status" value="{{ $data->pnr_status ?? '' }}" placeholder="Please Enter PNR Status">
                        </div>
                    </div>
                </div>

                <div class="row m-3 text-center">
                    <div class="col-md-12 ">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>




    <x-slot name="js">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <!-- <script src="{{asset('assets/plugins/select-picker/dist/picker.min.js')}}"></script> -->
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
        <script>
             function setStatus(input)
            {
                $('#pnr_status').val((input>0?'Borded':'No show'))
            }
            $(".dates").datetimepicker({
                timepicker: true,
                format: 'd-m-Y H:i',
                formatDate:'Y/m/d H:i',
                autoclose: true,
                clearBtn: true,
                todayButton: true,
                onSelectDate: function(ct) {
                    // $(".datestime").datetimepicker({ defaultDate:ct});
                    // $(".datestime").val('');
                }
            });


            $('#manageForm').submit(function(e) {
                e.preventDefault();
                $('#manageForm').find('.invalid-feedback').hide();
                $('#manageForm').find('.is-invalid').removeClass('is-invalid');
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            success(response.message);
                            setTimeout(() => {
                                window.location.href = "{{route('app.aai_report.flyingLogs')}}";
                            }, 2000);
                        } else {
                            $.each(response.message, function(fieldName, field) {
                                $('#manageForm').find('[name=' + fieldName + ']').addClass('is-invalid');
                                $('#manageForm').find('[name=' + fieldName + ']').after('<div class="invalid-feedback">' + field + '</div>');
                            })
                        }

                    }
                })
            });


        </script>
    </x-slot>
</x-app-layout>
