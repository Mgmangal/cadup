<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item"><a href="{{route('app.external.flying-details')}}">EXTERNAL FLYING-DETAILS</a></li>
            <li class="breadcrumb-item active">ADD</li>
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
            <h3 class="card-title">External Flying Details Add</h3>
            <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
        </div>
        <div class="card-body">
            <form action="{{route('app.external.flying-details.store')}}" method="POST" enctype="multipart/form-data" id="manageForm" autocomplete="off">
                @csrf
                <div class="row m-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date" class="form-label">Date<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <label class="input-group-text" for="date">
                                    <i class="fa fa-calendar"></i>
                                </label>
                                <input type="text" class="form-control dates is_valid" id="date" name="date" onchange="setDefaultDate(this.value);" placeholder="Please Enter Date" />
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
                                <option value="{{$aircraft_type->id}}">{{$aircraft_type->name}}</option>
                                @endforeach
                            </select>
                            @error('aircraft_type')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="aircraft_id" class="form-label">Call Sign<span class="text-danger">*</span></label>
                            <input type="text" class="form-control is_valid" id="aircraft_id" name="aircraft_id"  placeholder="Please Enter Call Sign" />
                        </div>
                    </div>
                </div>
                <div class="field_wrapper">
                    <div class="add_more">
                        <div class="row m-3">
                            <div class="col-md-12 bg-info justify-content-between d-flex">
                                <div class="form-lable p-2">No. 1</div>
                                <div class="form-label pt-1"></div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Pilot<span class="text-danger">*</span></label>
                                    <select name="pilot1_id[0]" class="form-control is_valid pilots pilot1_id">
                                        <option value="">Select</option>
                                        @foreach($pilots as $pilot)
                                        <option value="{{$pilot->id}}">{{$pilot->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Role<span class="text-danger">*</span></label>
                                    <select name="pilot1_role[0]" class="form-control pilot1_role is_valid" onchange="changeHandlerRoleOne(this);">
                                        <option value="">Select</option>
                                        @foreach($pilot_roles as $pilot_role)
                                        <option value="{{$pilot_role->id}}">{{$pilot_role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Second Pilot<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control is_valid" id="pilot2_id" name="pilot2_id[0]"  placeholder="Please Enter Second Pilot" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Second Pilot Role<span class="text-danger">*</span></label>
                                    <select name="pilot2_role[0]" class="form-control pilot2_role is_valid" onchange="changeHandlerRoleTwo(this);">
                                        <option value="">Select</option>
                                        @foreach($pilot_roles as $pilot_role)
                                        <option value="{{$pilot_role->id}}">{{$pilot_role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row m-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">From Sector<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control start_sector auto_complete_input is_valid" name="fron_sector[0]" placeholder="Please Enter Source Sector">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">To Sector<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control to_sector auto_complete_input is_valid" name="to_sector[0]" placeholder="Please Enter Destination Sector">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Flying Type</label>
                                    <select name="flying_type[0]" class="form-control flying_type is_valid">
                                        <option value="">Select</option>
                                        @foreach($flying_types as $flying_type)
                                        <option value="{{$flying_type->id}}">{{$flying_type->name}}</option>
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
                                        <input type="text" class="form-control dates d_date is_valid check_date_time" onchange="calculateBlockTime(this);" name="d_date[0]" placeholder="dd-mm-yyyy" onchange="validateDate();">
                                        <input type="text" class="form-control time_hhmm d_time is_valid" onchange="calculateBlockTime(this);" name="d_time[0]" placeholder="HH:MM" onchange="validateDate();">
                                        <input type="hidden" name="departure_time[0]" class="departure_time">
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
                                        <input type="text" class="form-control dates a_date is_valid check_date_time" onchange="calculateBlockTime(this);" name="a_date[0]" placeholder="dd-mm-yyyy" onchange="validateDate();" />
                                        <input type="text" class="form-control time_hhmm a_time is_valid" onchange="calculateBlockTime(this);" name="a_time[0]" placeholder="HH:MM" onchange="validateDate();" />
                                        <input type="hidden" name="arrival_time[0]" class="arrival_time">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Block Time</label>
                                    <input type="text" class="form-control block_time" name="block_time[0]" placeholder="HH:MM" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Night Hours</label>
                                    <input type="text" class="form-control time_hhmm" name="night_time[0]" placeholder="HH:MM" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-3 text-center">
                    <div class="col-md-12 ">
                        <a href="javascript:void(0);" class="add_button btn btn-warning">Add More</a>
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
            function setDefaultDate(value)
            {
              $('.d_date').val(value);  
              $('.a_date').val(value);  
            }
            
            

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

            $('.selct2').select2({
                tags: true,
                tokenSeparators: [',', ' '],
                placeholder: "Select Pilots",
                allowClear: true,
            });

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
                        if (departure_date_time >= arrival_date_time) {
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
                if (is_valid()) 
                {
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
                    });
                }
            });

            $(document).ready(function() {
                var maxField = 10; //Input fields increment limitation
                var addButton = $('.add_button'); //Add button selector
                var wrapper = $('.field_wrapper'); //Input field wrapper
                var x = 1; //Initial field counter is 1
                // Once add button is clicked
                $(addButton).click(function() {
                    //Check maximum number of input fields
                    if (x < maxField) {
                        if (is_valid()) {
                            x++; //Increase field counter
                            let fromSector = $(".to_sector").last().val();
                            let a_date = $(".a_date").last().val();
                            let a_time = $(".a_time").last().val();
                            let flying_type = $(".flying_type").last().val();
                            
                            let pilot1_id = $(".pilot1_id").last().val();
                            let pilot1_role = $(".pilot1_role").last().val();
                            let pilot2_id = $(".pilot2_id").last().val();
                            let pilot2_role = $(".pilot2_role").last().val();
                           
                            var fieldHTML = '<div class="add_more border-top"><div class="row m-3"><div class="col-md-12 bg-info justify-content-between d-flex"><div class="form-lable p-2">No. ' + x + '</div><div class="form-label pt-1"><a href="javascriot:void(0);" class="btn btn-sm btn-danger remove_button">Remove</a></div></div><div class="col-md-3"><div class="form-group"><label class="form-label">Pilot<span class="text-danger">*</span></label><select name="pilot1_id[' + x + ']" class="form-control pilot1_id pilots is_valid pilot1_id_'+x+'" >@foreach($pilots as $pilot)<option value="{{$pilot->id}}">{{$pilot->name}}</option>@endforeach</select></div></div><div class="col-md-3"><div class="form-group"><label class="form-label">Role<span class="text-danger">*</span></label> <select name="pilot1_role[' + x + ']" class="form-control pilot1_role  is_valid pilot1_role_'+x+'" onchange="changeHandlerRoleOne(this);"> <option value="">Select</option>@foreach($pilot_roles as $pilot_role)<option value="{{$pilot_role->id}}">{{$pilot_role->name}}</option>@endforeach</select> </div> </div> <div class="col-md-3"> <div class="form-group"> <label class="form-label">Second Pilot<span class="text-danger">*</span></label><input type="text" class="form-control is_valid pilot2_id_'+x+'" id="pilot2_id_'+x+'" name="pilot2_id['+x+']" value=""  placeholder="Please Enter Second Pilot" /></div> </div> <div class="col-md-3"> <div class="form-group"> <label class="form-label">Second Pilot Role<span class="text-danger">*</span></label> <select name="pilot2_role[' + x + ']" class="form-control pilot2_role is_valid pilot2_role_'+x+'" onchange="changeHandlerRoleTwo(this);"> <option value="">Select</option>@foreach($pilot_roles as $pilot_role)<option value="{{$pilot_role->id}}">{{$pilot_role->name}}</option>@endforeach</select> </div> </div> </div> <div class="row m-3"> <div class="col-md-4"> <div class="form-group"> <label class="form-label">From Sector<span class="text-danger">*</span></label> <input type="text" class="form-control fron_sector auto_complete_input is_valid" name="fron_sector[' + x + ']" value="' + fromSector + '" placeholder="Please Enter Source Sector"> </div> </div> <div class="col-md-4"> <div class="form-group"> <label class="form-label">To Sector<span class="text-danger">*</span></label> <input type="text" class="form-control to_sector auto_complete_input is_valid" name="to_sector[' + x + ']" placeholder="Please Enter Destination Sector"> </div> </div> <div class="col-md-4"> <div class="form-group"> <label class="form-label">Flying Type</label> <select name="flying_type[' + x + ']" class="form-control flying_type flying_type_' + x + ' is_valid"> <option value="">Select</option>@foreach($flying_types as $flying_type)<option value="{{$flying_type->id}}">{{$flying_type->name}}</option>@endforeach</select> </div> </div> </div> <div class="row m-3"> <div class="col-md-3"> <div class="form-group"> <label class="form-label">Departure Time</label> <div class="input-group"> <label class="input-group-text" for="d_time"> <i class="fa fa-calendar"></i> </label> <input type="text" class="form-control dates d_date is_valid check_date_time" onchange="calculateBlockTime(this);" name="d_date[' + x + ']" placeholder="dd-mm-yyyy" onchange="validateDate();" value="'+a_date+'"> <input type="text" class="form-control time_hhmm d_time is_valid" onchange="calculateBlockTime(this);" name="d_time[' + x + ']" placeholder="HH:MM" onchange="validateDate();"> <input type="hidden" name="departure_time[' + x + ']" class="departure_time"> </div> </div> </div> <div class="col-md-3"> <div class="form-group"> <label class="form-label">Arrival Time</label> <div class="input-group"> <label class="input-group-text"> <i class="fa fa-calendar"></i> </label> <input type="text" class="form-control dates a_date is_valid check_date_time" onchange="calculateBlockTime(this);" name="a_date[' + x + ']" placeholder="dd-mm-yyyy" onchange="validateDate();" value="'+a_date+'"/> <input type="text" class="form-control time_hhmm a_time is_valid" onchange="calculateBlockTime(this);" name="a_time[' + x + ']" placeholder="HH:MM" onchange="validateDate();" /> <input type="hidden" name="arrival_time[' + x + ']" class="arrival_time"> </div> </div> </div> <div class="col-md-3"> <div class="form-group"> <label class="form-label">Block Time</label> <input type="text" class="form-control block_time" name="block_time[' + x + ']" placeholder="HH:MM" readonly> </div> </div> <div class="col-md-3"> <div class="form-group"> <label class="form-label">Night Hours</label> <input type="text" class="form-control time_hhmm" name="night_time[' + x + ']" placeholder="HH:MM" value=""></div></div></div></div>'; //New input field html 
                            
                            $(wrapper).append(fieldHTML); //Add field html
                           
                            $('.flying_type_'+x).val(flying_type);
                            $('.pilot1_id_'+x).val(pilot1_id);
                            $('.pilot2_id_'+x).val(pilot2_id);
                            $('.pilot1_role_'+x).val(pilot1_role);
                            $('.pilot2_role_'+x).val(pilot2_role);
                            autoCompleteInput();
                            initTimeHHMM();
                            $(".dates").datetimepicker({
                                timepicker: false,
                                format: 'd-m-Y',
                                formatDate:'Y/m/d',
                                autoclose: true,
                                clearBtn: true,
                                todayButton: true,               
                                // maxDate: new Date(new Date().getTime() + 5 * 24 * 60 * 60 * 1000),
                                onSelectDate: function(ct) {
                                    // $(".datestime").datetimepicker({ defaultDate:ct});   
                                    // $(".datestime").val('');
                                }
                            });
                        }
                       
                    } else {
                        alert('A maximum of ' + maxField + ' fields are allowed to be added. ');
                    }
                });

                // Once remove button is clicked
                $(wrapper).on('click', '.remove_button', function(e) {
                    e.preventDefault();
                    $(this).parent('div').parent('div').parent('div').parent('div').remove(); //Remove field html
                    x--; //Decrease field counter
                });
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