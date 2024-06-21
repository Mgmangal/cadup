<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item"><a href="{{route('app.flying-details')}}">FLYING-DETAILS</a></li>
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
            <h3 class="card-title">Flying Details Add</h3>
            <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
        </div>
        <div class="card-body">
            <form action="{{route('app.flying-details.store')}}" method="POST" enctype="multipart/form-data" id="manageForm" autocomplete="off">
                @csrf
                <div class="row m-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date" class="form-label">Date<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <label class="input-group-text" for="date">
                                    <i class="fa fa-calendar"></i>
                                </label>
                                <input type="text" class="form-control dates is_valid" id="date" name="date" placeholder="Please Enter Date" />
                            </div>
                            @error('date')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="aircraft_id" class="form-label">Aircraft / Simulator<span class="text-danger">*</span></label>
                            <select name="aircraft_id" id="aircraft_id" class="form-control is_valid" onchange="getLastLocation(this.value);">
                                <option value="">Select</option>
                                @foreach($aircrofts as $aircraft)
                                <option value="{{$aircraft->id}}">{{$aircraft->call_sign}}-{{$aircraft->manufacturer}}-{{$aircraft->type_model}}</option>
                                @endforeach
                            </select>
                            @error('aircraft_id')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
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
                                    <select name="pilot1_id[0]" class="form-control is_valid pilots pilot1_id" onchange="changeHandlerPilotOne(this);">
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
                                        <option value="1">P1</option>
                                        <option value="2">P2</option>
                                        <option value="3">Instructor</option>
                                        <option value="4">Examiner</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Second Pilot<span class="text-danger">*</span></label>
                                    <select name="pilot2_id[0]" id="pilot2_id" class="form-control pilot2_id pilots is_valid" onchange="changeHandlerPilotTwo(this);">
                                        <option value="">Select</option>
                                        @foreach($pilots as $pilot)
                                        <option value="{{$pilot->id}}">{{$pilot->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Second Pilot Role<span class="text-danger">*</span></label>
                                    <select name="pilot2_role[0]" class="form-control pilot2_role is_valid" onchange="changeHandlerRoleTwo(this);">
                                        <option value="">Select</option>
                                        <!--<option value="1">AME</option>-->
                                        <option value="1">P1</option>
                                        <option value="2">P2</option>
                                        <option value="3">Instructor</option>
                                        <option value="4">Examiner</option>
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
                                        <option value="1">Agriculture minister</option>
                                        <option value="2">Cabinet Minister</option>
                                        <option value="3">CM</option>
                                        <option value="4">CS</option>
                                        <option value="5">DGP</option>
                                        <option value="6">Dy. CM</option>
                                        <option value="7">Governor</option>
                                        <option value="8">Positioning</option>
                                        <option value="9">PPC</option>
                                        <option value="10">RTB</option>
                                        <option value="11">Speaker UP</option>
                                        <option value="12">VIP</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row m-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Departure Time</label>
                                    <div class="input-group">
                                        <label class="input-group-text" for="departure_time">
                                            <i class="fa fa-calendar"></i>
                                        </label>
                                        <input type="text" class="form-control datestime departure_time is_valid" onchange="calculateBlockTime(this);" name="departure_time[0]" placeholder="Please Enter Departure Time" onchange="validateDate();">
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
                                        <input type="text" class="form-control datestime arrival_time is_valid" onchange="calculateBlockTime(this);" name="arrival_time[0]" placeholder="Please Enter Arrival Time" onchange="validateDate();" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Block Time</label>
                                    <input type="text" class="form-control block_time" name="block_time[0]" placeholder="Please Enter Block Time" readonly>
                                    <div style="font-size: 11px;font-weight: 600;color: #d37b00;">
                                        Duty Period starts 45 Min before first departure and end after 15 min arrival</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Night Hours</label>
                                    <input type="text" class="form-control night_block_time" name="night_block_time[0]" placeholder="Please Enter Night Block Time" value="" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row m-3">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" name="is_night[0]" value="yes" class="form-check-input" onchange="toggleNightBlockSection(this);">
                                    <label class="form-check-label">Night Flying</label>
                                </div>
                            </div>
                        </div>
                        <div class="row m-3 d-none">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">From Time</label>
                                    <div class="input-group">
                                        <label class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </label>
                                        <input type="text" class="form-control datestime night_from_time is_valid" onchange="calculateNightBlockTime(this);" name="night_from_time[0]" placeholder="Please Enter Night From Time">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">To Time</label>
                                    <div class="input-group">
                                        <label class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </label>
                                        <input type="text" class="form-control datestime night_to_time is_valid" onchange="calculateNightBlockTime(this);" name="night_to_time[0]" placeholder="Please Enter Night To Time" />
                                    </div>
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
            let pilots = '';
            var last_arrival_time = '';
            var last_arrival_times = '';

            function getLastLocation(aircroft_id) {
                $.ajax({
                    url: "{{route('app.flying-details.last.location')}}",
                    method: "post",
                    dataType: 'json',
                    data: {
                        aircroft_id,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $('.start_sector').val(response.data);
                        $('.pilots').html(response.pilots);
                        pilots = response.pilots;
                        last_arrival_time = response.last_arrival_time;
                        last_arrival_times = response.last_arrival_time;
                    }
                });

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

            // $(".datestime").datetimepicker({
            //     format: 'd-m-Y H:i',
            //     formatTime: 'H:i',
            //     formatDate: 'd-m-Y',
            //     autoclose: true,
            //     datepicker: false,
            //     onShow: function() {
            //         this.setOptions({
            //             maxDate:$('#date').val()?$('#date').val():false,
            //             minDate:$('#date').val()?$('#date').val():false,
            //             startDate:$('#date').val()?$('#date').val():false,
            //             defaultDate:$('#date').val()?$('#date').val():false,
            //         });
            //     }
            // });

            $(".dates").datetimepicker({
                timepicker: false,
                format: 'd-m-Y',
                formatDate:'Y/m/d',
                autoclose: true,
                clearBtn: true,
                todayButton: true,               
                maxDate: new Date(new Date().getTime() + 5 * 24 * 60 * 60 * 1000),
                onSelectDate: function(ct) {
                    $(".datestime").datetimepicker({ defaultDate:ct});   
                    $(".datestime").val('');
                }
            });

            $('.selct2').select2({
                tags: true,
                tokenSeparators: [',', ' '],
                placeholder: "Select Pilots",
                allowClear: true,
            });

            function calculateBlockTime(e) {
                let departure_time = $(e).closest('.row').find('.departure_time').val();
                let arrival_time = $(e).closest('.row').find('.arrival_time').val();
                if (departure_time != '' && arrival_time != '') {
                    let depa = setDateTime(departure_time);
                    let darr = setDateTime(arrival_time);
                    if (depa >= darr) {
                        $(e).closest('.row').find('.arrival_time').val('');
                        warning('Arrival time should be 15 minute more than departure time');
                        return false;
                    }
                    let block_time = getTimeDefrence(toJSDate(departure_time), toJSDate(arrival_time));;
                    $(e).closest('.row').find('.block_time').val(block_time);
                    totalBlockTime(); 
                }
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

            function calculateNightBlockTime(e) {
                let night_from_time = $(e).closest('.row').find('.night_from_time').val();
                let night_to_time = $(e).closest('.row').find('.night_to_time').val();
                if (night_from_time != '' && night_to_time != '') {
                    let depa = setDateTime(night_from_time);
                    let darr = setDateTime(night_to_time);
                    if (depa >= darr) {
                        $(e).closest('.row').find('.night_to_time').val('');
                        warning('Arrival time should be 15 minute more than departure time');
                        return false;
                    }

                    let night_block_time = getTimeDefrence(toJSDate(night_from_time), toJSDate(night_to_time));;
                    $(e).closest('.row').find('.night_block_time').val(night_block_time);
                }
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

            function toggleNightBlockSection(e) {
                if ($(e).is(':checked')) {
                    let ab = $(e).parent().parent().parent().prev().find('.departure_time').val();
                    let cd = $(e).parent().parent().parent().prev().find('.arrival_time').val();
                    let ef = $(e).parent().parent().parent().prev().find('.block_time').val();
                    $(e).closest('.row').next('.row').find('.night_from_time').val(ab);
                    $(e).closest('.row').next('.row').find('.night_to_time').val(cd);
                    $(e).closest('.row').next('.row').find('.night_block_time').val(ef);
                    $(e).closest('.row').next('.row').removeClass('d-none');
                } else {
                    $(e).closest('.row').next('.row').addClass('d-none');
                    $(e).closest('.row').next('.row').find('.night_from_time').val('');
                    $(e).closest('.row').next('.row').find('.night_to_time').val('');
                    $(e).closest('.row').next('.row').find('.night_block_time').val('');
                }
            }

            $('#manageForm').submit(function(e) {
                e.preventDefault();
                if (is_valid()) {

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
                                // location.reload();
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
                            let arrivalTime = $(".arrival_time ").last().val();
                            let flying_type = $(".flying_type ").last().val();
                            var fieldHTML = '<div class="add_more border-top"> <div class="row m-3"> <div class="col-md-12 bg-info justify-content-between d-flex"> <div class="form-lable p-2">No. ' + x + '</div> <div class="form-label pt-1"> <a href="javascriot:void(0);" class="btn btn-sm btn-danger remove_button">Remove</a> </div> </div> <div class="col-md-3"> <div class="form-group"> <label class="form-label">Pilot<span class="text-danger">*</span></label> <select name="pilot1_id[' + x + ']" class="form-control pilot1_id pilots is_valid" onchange="changeHandlerPilotOne(this);">' + pilots + ' </select> </div> </div> <div class="col-md-3"> <div class="form-group"> <label class="form-label">Role<span class="text-danger">*</span></label> <select name="pilot1_role[' + x + ']" class="form-control pilot1_role  is_valid"  onchange="changeHandlerRoleOne(this);"> <option value="">Select</option><option value="1">P1</option><option value="2">P2</option><option value="3">Instructor</option><option value="4">Examiner</option></select> </div> </div> <div class="col-md-3"> <div class="form-group"> <label class="form-label">Second Pilot<span class="text-danger">*</span></label> <select name="pilot2_id[' + x + ']" class="form-control pilot2_id pilots is_valid" onchange="changeHandlerPilotTwo(this);"> <option value="">Select</option>' + pilots + ' </select> </div> </div> <div class="col-md-3"> <div class="form-group"> <label class="form-label">Second Pilot Role<span class="text-danger">*</span></label> <select name="pilot2_role[' + x + ']" class="form-control pilot2_role is_valid" onchange="changeHandlerRoleTwo(this);"> <option value="">Select</option><option value="1">P1</option><option value="2">P2</option><option value="3">Instructor</option><option value="4">Examiner</option> </select> </div> </div> </div> <div class="row m-3"> <div class="col-md-4"> <div class="form-group"> <label class="form-label">From Sector<span class="text-danger">*</span></label> <input type="text" class="form-control fron_sector auto_complete_input is_valid" name="fron_sector[' + x + ']" value="' + fromSector + '" placeholder="Please Enter Source Sector"> </div> </div> <div class="col-md-4"> <div class="form-group"> <label class="form-label">To Sector<span class="text-danger">*</span></label> <input type="text" class="form-control to_sector auto_complete_input is_valid" name="to_sector[' + x + ']" placeholder="Please Enter Destination Sector"> </div> </div> <div class="col-md-4"> <div class="form-group"> <label class="form-label">Flying Type</label> <select name="flying_type[' + x + ']" class="form-control flying_type flying_type_' + x + ' is_valid"> <option value="">Select</option> <option value="1">Agriculture minister</option><option value="2">Cabinet Minister</option><option value="3">CM</option><option value="4">CS</option><option value="5">DGP</option><option value="6">Dy. CM</option><option value="7">Governor</option><option value="8">Positioning</option><option value="9">PPC</option><option value="10">RTB</option><option value="11">Speaker UP</option><option value="12">VIP</option></select> </div> </div> </div> <div class="row m-3"> <div class="col-md-4"> <div class="form-group"> <label class="form-label">Departure Time</label> <div class="input-group"> <label class="input-group-text"> <i class="fa fa-calendar"></i> </label> <input type="text" class="form-control datestime departure_time is_valid" onchange="calculateBlockTime(this);" name="departure_time[' + x + ']" placeholder="Please Enter Departure Time" value="' + arrivalTime + '" onchange="validateDate();"> </div> </div> </div> <div class="col-md-4"> <div class="form-group"> <label class="form-label">Arrival Time</label> <div class="input-group"> <label class="input-group-text"> <i class="fa fa-calendar"></i> </label> <input type="text" class="form-control datestime arrival_time is_valid" onchange="calculateBlockTime(this);" name="arrival_time[' + x + ']" placeholder="Please Enter Arrival Time" onchange="validateDate();" /> </div> </div> </div> <div class="col-md-4"> <div class="form-group"> <label class="form-label">Block Time</label> <input type="text" class="form-control block_time is_valid" name="block_time[' + x + ']" placeholder="Please Enter Block Time" readonly> </div> </div> </div> <div class="row m-3"> <div class="col-md-12 "> <div class="form-check"> <input type="checkbox" name="is_night[' + x + ']" value="yes" class="form-check-input" onchange="toggleNightBlockSection(this);"> <label class="form-check-label">Night Flying</label> </div> </div> </div> <div class="row m-3 d-none"> <div class="col-md-4"> <div class="form-group"> <label class="form-label">From Time</label> <div class="input-group"> <label class="input-group-text"> <i class="fa fa-calendar"></i> </label> <input type="text" class="form-control datestime night_from_time is_valid" onchange="calculateNightBlockTime(this);" name="night_from_time[' + x + ']" placeholder="Please Enter Night From Time"> </div> </div> </div> <div class="col-md-4"> <div class="form-group"> <label class="form-label">To Time</label> <div class="input-group"> <label class="input-group-text" > <i class="fa fa-calendar"></i> </label> <input type="text" class="form-control datestime night_to_time is_valid" onchange="calculateNightBlockTime(this);" name="night_to_time[' + x + ']" placeholder="Please Enter Night To Time" /> </div> </div> </div> <div class="col-md-4"> <div class="form-group"> <label class="form-label">Block Time</label> <input type="text" class="form-control night_block_time" name="night_block_time[' + x + ']" placeholder="Please Enter Night Block Time" value="" readonly> </div> </div> </div> </div>'; //New input field html 
                            
                            $(wrapper).append(fieldHTML); //Add field html
                            $(".datestime").datetimepicker({
                                format: 'd-m-Y H:i',
                                formatTime: 'H:i',
                                formatDate: 'd-m-Y',
                                autoclose: true
                            });
                            $('.flying_type_'+x).val(flying_type);
                            autoCompleteInput();
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
                $(".datestime").attr('maxlength', '4');
                $(".datestime").attr('placeholder', 'HH:MM');
                $(".datestime").bind({
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
                $(e).val().replace(':', '');
                var str = $(this).val();
                if (str.length > 2) {
                    str = ('0' + str).slice(-4);
                } else {
                    str = ('0' + str + '00').slice(-4);
                }
                var mm = parseInt(str.substr(2, 2));
                var hh = parseInt(str.slice(0, 2));
                if (mm > 59) {
                    mm = mm - 60;
                }

                if (hh > 23) {
                    hh = hh % 24;
                }
                mm = ('0' + mm).slice(-2);
                hh = ('0' + hh).slice(-2);
                var formate = hh + ':' + mm;
                $(this).val(formate);
            }
        </script>
    </x-slot>
</x-app-layout>