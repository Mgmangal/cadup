<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item"><a href="{{route('app.external.flying-details')}}">EXTERNAL FLYING-DETAILS</a></li>
            <li class="breadcrumb-item active">EDIT</li>
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
            <h3 class="card-title">External Flying Details Edit</h3>
            <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
        </div>
        <div class="card-body">
            <form action="{{route('app.external.flying-details.update',$data->id)}}" method="POST" enctype="multipart/form-data" id="manageForm">
                @csrf
                @method('PUT')
                <div class="row m-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date" class="form-label">Date<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <label class="input-group-text" for="date">
                                    <i class="fa fa-calendar"></i>
                                </label>
                                <input type="text" class="form-control dates" id="date" name="date" value="{{$data->date}}" />
                            </div>
                            @error('date')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <!--<div class="col-md-3">-->
                    <!--    <div class="form-group">-->
                    <!--        <label for="aircraft_cateogry" class="form-label">Aircraft Category<span class="text-danger">*</span></label>-->
                    <!--        <select name="aircraft_cateogry" id="aircraft_cateogry" class="form-control">-->
                    <!--            <option value="">Select</option>-->
                    <!--            <option value="Fixed Wing">Fixed Wing</option>-->
                    <!--            <option value="Rotor Wing">Rotor Wing</option>-->
                    <!--        </select>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="aircraft_type" class="form-label">Aircraft Type<span class="text-danger">*</span></label>
                            <select name="aircraft_type" id="aircraft_type" class="form-control is_valid">
                                <option value="">Select</option>
                                @foreach($aircraft_types as $aircraft_type)
                                <option {{old('aircraft_type',$data->aircraft_type)==$aircraft_type->id?'selected':''}} value="{{$aircraft_type->id}}">{{$aircraft_type->name}}</option>
                                @endforeach
                            </select>
                            @error('aircraft_type')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="aircraft_id" class="form-label">Call Sign <span class="text-danger">*</span></label>
                            <input type="text" class="form-control is_valid" id="aircraft_id" name="aircraft_id" value="{{$data->aircraft_id}}"  placeholder="Please Enter Call Sign" />
                        </div>
                    </div>
                </div>
                <div class="row m-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pilot1_id" class="form-label">Pilot<span class="text-danger">*</span></label>
                            <select name="pilot1_id" id="pilot1_id" class="form-control is_valid pilot1_id">
                                <option value="">Select</option>
                                @foreach($pilots as $pilot)
                                <option {{old('pilot1_id',$data->pilot1_id)==$pilot->id?'selected':''}} value="{{$pilot->id}}">{{$pilot->name}}</option>
                                @endforeach
                            </select>
                            @error('pilot1_id')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pilot1_role" class="form-label">Role<span class="text-danger">*</span></label>
                            <select name="pilot1_role" id="pilot1_role" class="form-control is_valid pilot1_role" onchange="changeHandlerRoleOne(this);">
                                <option value="">Select</option>
                               @foreach($pilot_roles as $pilot_role)
                                    <option {{old('pilot1_role',$data->pilot1_role)==$pilot_role->id?'selected':''}} value="{{$pilot_role->id}}">{{$pilot_role->name}}</option>
                                @endforeach
                            </select>
                            @error('pilot1_role')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pilot2_id" class="form-label">Second Pilot<span class="text-danger">*</span></label>
                            <input type="text" class="form-control is_valid" id="pilot2_id" name="pilot2_id" value="{{$data->pilot2_id}}"  placeholder="Please Enter Second Pilot" />

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pilot2_role" class="form-label">Second Pilot Role<span class="text-danger">*</span></label>
                            <select name="pilot2_role" id="pilot2_role" class="form-control is_valid pilot2_role" onchange="changeHandlerRoleTwo(this);">
                                <option value="">Select</option>
                               @foreach($pilot_roles as $pilot_role)
                                    <option {{old('pilot2_role',$data->pilot2_role)==$pilot_role->id?'selected':''}} value="{{$pilot_role->id}}">{{$pilot_role->name}}</option>
                                @endforeach
                            </select>
                            @error('pilot1_role')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row m-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fron_sector" class="form-label">From Sector<span class="text-danger">*</span></label>
                            <input type="text" class="form-control is_valid auto_complete_input" id="fron_sector" name="fron_sector" placeholder="Please Enter Source Sector" value="{{old('fron_sector',$data->fron_sector)}}">
                            @error('fron_sector')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="to_sector" class="form-label">To Sector<span class="text-danger">*</span></label>
                            <input type="text" class="form-control is_valid auto_complete_input" id="to_sector" name="to_sector" placeholder="Please Enter Destinatio Sector" value="{{old('to_sector',$data->to_sector)}}">
                            @error('to_sector')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="flying_type" class="form-label">Flying Type</label>
                            <select name="flying_type" id="flying_type" class="form-control is_valid">
                               <option value="">Select</option>
                               @foreach($flying_types as $flying_type)
                                <option {{old('flying_type',$data->flying_type)==$flying_type->id?'selected':''}} value="{{$flying_type->id}}">{{$flying_type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row m-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Departure Time</label>
                            <div class="input-group">
                                <label class="input-group-text" for="d_time">
                                    <i class="fa fa-calendar"></i>
                                </label>
                                <input type="text" class="form-control dates d_date is_valid check_date_time" onchange="calculateBlockTime(this);" name="d_date" value="{{date('d-m-Y',strtotime($data->departure_time))}}" onchange="validateDate();">
                                <input type="text" class="form-control time_hhmm d_time is_valid" onchange="calculateBlockTime(this);" name="d_time" value="{{date('H:i',strtotime($data->departure_time))}}" onchange="validateDate();">
                                <input type="hidden" name="departure_time" class="departure_time" value="{{is_get_date_time_format($data->departure_time)}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Arrival Time</label>
                            <div class="input-group">
                                <label class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </label>
                                <input type="text" class="form-control dates a_date is_valid check_date_time" onchange="calculateBlockTime(this);" name="a_date" value="{{date('d-m-Y',strtotime($data->arrival_time))}}" onchange="validateDate();" />
                                <input type="text" class="form-control time_hhmm a_time is_valid" onchange="calculateBlockTime(this);" name="a_time" value="{{date('H:i',strtotime($data->arrival_time))}}" onchange="validateDate();" />
                                <input type="hidden" name="arrival_time" class="arrival_time"  value="{{is_get_date_time_format($data->arrival_time)}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Block Time</label>
                            <input type="text" class="form-control block_time" name="block_time" placeholder="HH:MM"  value="{{is_time_defrence($data->departure_time,$data->arrival_time)}}" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Night Hours</label>
                            <input type="text" class="form-control time_hhmm" name="night_time" placeholder="HH:MM" value="{{$data->night_time}}">
                        </div>
                    </div>
                </div>


                <div class="row m-3 text-center">
                    <div class="col-md-12 ">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="javascript:void(0);" id="totalBlockTime" class="btn btn-warning" style="margin: 10px;">Total Block Time : 00:00</a>
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
            $(".dates").datetimepicker({
                timepicker: false,
                format: 'd-m-Y',
                formatDate:'Y/m/d',
                autoclose: true,
                clearBtn: true,
                todayButton: true,
                maxDate: new Date(new Date().getTime() + 5 * 24 * 60 * 60 * 1000),
                onSelectDate: function(ct) {
                    // $(".datestime").datetimepicker({ defaultDate:ct});
                    // $(".datestime").val('');
                }
            });

             function setDateTime(dateTime) {
                if (dateTime.length > 0) {
                    dateTime = dateTime.split(" ");
                    var date = dateTime[0];
                    var time = dateTime[1];
                    date = date.split("-");
                    time = time.split(":");
                    var day = date[0];
                    var month = date[1];
                    var year = date[2];

                    var hour = time[0];
                    var mint = time[1];
                    return new Date(year, month, day, hour, mint, '00');
                } else {
                    return '00';
                }
            }
            function calculateBlockTime(e) {
                setTimeout(function(){
                    checkDateTime();
                    let departure_date = $(e).closest('.row').find('.d_date').val();
                    let departure_time = $(e).closest('.row').find('.d_time').val();
                    let arrival_date = $(e).closest('.row').find('.a_date').val();
                    let arrival_time = $(e).closest('.row').find('.a_time').val();
                    if (departure_time != '' && arrival_time != ''&& departure_date!='' && arrival_date!='') {

                        // console.log(departure_date+' '+departure_time);
                        // console.log(arrival_date+' '+arrival_time);
                        let depa = setDateTime(departure_date+' '+departure_time);
                        let darr = setDateTime(arrival_date+' '+arrival_time);

                        if (depa >= darr) {
                            $(e).closest('.row').find('.a_time').val('');
                            warning('Arrival time should be more than departure time');
                            return false;
                        }
                        $(e).closest('.row').find('.departure_time').val(departure_date+' '+departure_time);
                        $(e).closest('.row').find('.arrival_time').val(arrival_date+' '+arrival_time);
                        let block_time = getTimeDefrence(toJSDate(departure_date+' '+departure_time), toJSDate(arrival_date+' '+arrival_time));;
                        $(e).closest('.row').find('.block_time').val(block_time);
                        totalBlockTime();
                    }
                }, 1000);
            }

            function checkDateTime()
            {
                var departure_date_time='';
                var arrival_date_time='';
                $('.check_date_time').each(function(index){
                    console.log(index);
                    if(index==0)
                    {
                        departure_date_time=setDateTime($(this).val()+' '+$(this).next().val());
                    }else{
                        arrival_date_time=setDateTime($(this).val()+' '+$(this).next().val());
                        if (departure_date_time > arrival_date_time) {
                            $(this).next().val('');
                            warning('Please enter valid time');
                            return false;
                        }
                        departure_date_time=arrival_date_time;
                    }
                });
            }

            function totalBlockTime() {
                var t1 = "00:00";
                var mins = 0;
                var hrs = 0;
                $('.block_time').each(function() {
                    if ($(this).val().length > 1) {
                        t1 = t1.split(':');
                        // console.log(t1);
                        var t2 = $(this).val().split(':');
                        // console.log(t2);
                        mins = Number(t1[1]) + Number(t2[1]);
                        minhrs = Math.floor(parseInt(mins / 60));
                        hrs = Number(t1[0]) + Number(t2[0]) + minhrs;
                        // console.log(hrs)
                        mins = mins % 60;
                        // t1 = hrs.padDigit() + ':' + mins.padDigit();
                        t1 = ((hrs < 10) ? ("0" + hrs) : hrs) + ":" + ((mins < 10) ? ("0" + mins) : mins);
                    }
                });
                $('#totalBlockTime').html('Total Block Time : ' + t1);
            }

            function toJSDate(dateTime) {
                //Ex: 16-11-2015 16:05
                var dateTime = dateTime.split(" "); //dateTime[0] = date, dateTime[1] = time
                //console.log(dateTime);
                var date = dateTime[0].split("-");
                //console.log(date);
                var time = dateTime[1].split(":");
                //console.log(time);
                //(year, month, day, hours, minutes, seconds, milliseconds)
                //subtract 1 from month because Jan is 0 and Dec is 11
                return new Date(date[2], (date[1] - 1), date[0], time[0], time[1], 0, 0);
            }

            function getTimeDefrence(start_actual_time, end_actual_time) {
                start_actual_time = new Date(start_actual_time);
                end_actual_time = new Date(end_actual_time);
                var diff = end_actual_time - start_actual_time;
                var diffSeconds = diff / 1000;
                var HH = Math.floor(diffSeconds / 3600);
                var MM = Math.floor(diffSeconds % 3600) / 60;
                var formatted = ((HH < 10) ? ("0" + HH) : HH) + ":" + ((MM < 10) ? ("0" + MM) : MM);
                return formatted;
            }

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
                            location.reload();
                        } else {
                            $.each(response.message, function(fieldName, field) {
                                $('#manageForm').find('[name=' + fieldName + ']').addClass('is-invalid');
                                $('#manageForm').find('[name=' + fieldName + ']').after('<div class="invalid-feedback">' + field + '</div>');
                            })
                        }

                    }
                })
            });

            function is_valid() {
                $('.is_valid').removeClass('is-invalid');
                let valid = true;
                $('.is_valid:visible').each(function() {
                    if ($(this).val() == '') {
                        $(this).addClass('is-invalid');
                        valid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                return valid;
            }

            function changeHandlerPilotOne(e) {
                $(e).parent().parent().parent().find('.pilot2_id option[disabled]').attr('disabled', false); //reset all the disabled options on every change event
                console.log($(e).parent().parent().parent().find('.pilot2_id'));
                var val = e.value;
                $(e).parent().parent().parent().find('.pilot2_id').not(this).find('option').filter(function() { //filter option elements having value as selected option
                    return this.value === val;
                }).prop('disabled', true);
            }

            function changeHandlerPilotTwo(e) {
                $(e).parent().parent().parent().find('.pilot1_id option[disabled]').attr('disabled', false); //reset all the disabled options on every change event
                var val = e.value;
                $(e).parent().parent().parent().find('.pilot1_id').not(this).find('option').filter(function() { //filter option elements having value as selected option
                    return this.value === val;
                }).prop('disabled', true);
            }


            function changeHandlerRoleOne(e) {
                $(e).parent().parent().parent().find('.pilot2_role option[disabled]').prop('disabled', false); //reset all the disabled options on every change event
                var val = e.value;
                $(e).parent().parent().parent().find('.pilot2_role').not(this).find('option').filter(function() { //filter option elements having value as selected option
                    return this.value === val;
                }).prop('disabled', true);
            }

            function changeHandlerRoleTwo(e) {
                $(e).parent().parent().parent().find('.pilot1_role option[disabled]').prop('disabled', false); //reset all the disabled options on every change event
                var val = e.value;
                $(e).parent().parent().parent().find('.pilot1_role').not(this).find('option').filter(function() { //filter option elements having value as selected option
                    return this.value === val;
                }).prop('disabled', true);
            }

            function autoCompleteInput()
            {
                $( ".auto_complete_input" ).autocomplete({
                  source: jQuery.parseJSON( localStorage.getItem('htmltest'))
                });
            }

            autoCompleteInput();

            function getSectors()
            {
                $.ajax({
                    url: "{{route('app.sectors.autocomplete')}}",
                    type: 'get',
                    data: {},
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        localStorage.setItem('htmltest', JSON.stringify(data));
                    }
                });
            }
            getSectors();

            function initTimeHHMM() {
                $(".time_hhmm").attr('maxlength', '4');
                $(".time_hhmm").attr('placeholder', 'HH:MM');
                $(".time_hhmm").bind({
                    keydown: CheckNum,
                    blur: formateHHMM,
                    focus: unformateHHMM
                });
            }

            function CheckNum(e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
                    // Allow: Ctrl+A, Command+A
                    (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                    // Allow: home, end, left, right, down, up
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            };

            function unformateHHMM(e) {
                $(this).val($(this).val().replace(':', ''));
            }

            function formateHHMM(e) {
                $(this).val($(this).val().replace(':', ''));
                var str = $(this).val();
                console.log(str,'hello');
                if (str.length > 2) {
                    str = ('0' + str).slice(-4);
                } else {
                    str = ('0' + str + '00').slice(-4);
                }
                 console.log(str,'yes');
                var mm = parseInt(str.substr(2, 2));
                var hh = parseInt(str.slice(0, 2));
                if (mm > 59) {
                    mm = mm - 60;
                }

                if (hh > 23) {
                    hh = hh % 24;
                }
                  console.log(hh);
                mm = ('0' + mm).slice(-2);
                hh = ('0' + hh).slice(-2);
                var formate = hh + ':' + mm;
                $(this).val(formate);
            }

            initTimeHHMM() ;
        </script>
    </x-slot>
</x-app-layout>
