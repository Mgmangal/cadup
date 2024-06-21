<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item"><a href="{{route('app.users')}}">NONE-FLYING-DETAILS</a></li>
            <li class="breadcrumb-item active">ADD</li>
        </ul>
    </x-slot>
    <x-slot name="css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <!-- <link href="{{asset('assets/plugins/select-picker/dist/picker.min.css')}}" rel="stylesheet" /> -->
    </x-slot>
    <!-- Errors -->
    <x-errors class="mb-4" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">None Flying Details Add</h3>
            <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
        </div>
        <div class="card-body">
            <form action="{{route('app.none-flying-details.store')}}" method="POST" enctype="multipart/form-data" id="manageForm">
                @csrf
                
                <div class="field_wrapper">
                    <div class="add_more">
                        <div class="row m-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Pilot<span class="text-danger">*</span></label>
                                    <select name="user_id" class="form-control is_valid pilot1_id">
                                        <option value="">Select</option>
                                        @foreach($pilots as $pilot)
                                        <option value="{{$pilot->id}}">{{$pilot->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Departure Time</label>
                                    <div class="input-group">
                                        <label class="input-group-text" for="from_dates">
                                            <i class="fa fa-calendar"></i>
                                        </label>
                                        <input type="text" class="form-control datestime from_dates is_valid" onchange="calculateBlockTime(this);" name="from_dates" placeholder="Please Enter Departure Time" onchange="validateDate();">
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
                                        <input type="text" class="form-control datestime to_dates is_valid" onchange="calculateBlockTime(this);" name="to_dates" placeholder="Please Enter Arrival Time" onchange="validateDate();" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Block Time</label>
                                    <input type="text" class="form-control block_time" name="block_time" placeholder="Please Enter Block Time" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row m-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Comment</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control reason is_valid"  name="reason" placeholder="Please Enter Comment">
                                    </div>
                                </div>
                            </div>
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
        <script>
            $(".dates").datetimepicker({
                timepicker: false,
                format: 'd-m-Y',
                formatDate: 'd-m-Y',
                autoclose: true,
                clearBtn: true,
                todayButton: true,
            });

            $(".datestime").datetimepicker({
                format: 'd-m-Y H:i',
                formatTime: 'H:i',
                formatDate: 'd-m-Y',
                autoclose: true
            });

            $('.selct2').select2({
                tags: true,
                tokenSeparators: [',', ' '],
                placeholder: "Select Pilots",
                allowClear: true,
            });

            function calculateBlockTime(e) {
                let departure_time = $(e).closest('.row').find('.from_dates').val();
                let arrival_time = $(e).closest('.row').find('.to_dates').val();
                if (departure_time != '' && arrival_time != '') {
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
                        console.log(hrs)
                        mins = mins % 60;
                        // t1 = hrs.padDigit() + ':' + mins.padDigit();
                        t1 = ((hrs < 10) ? ("0" + hrs) : hrs) + ":" + ((mins < 10) ? ("0" + mins) : mins);
                    }
                });
                $('#totalBlockTime').html('Total Block Time : ' + t1);
            }

            function calculateNightBlockTime(e) {
                let night_from_time = $(e).closest('.row').find('.from_dates').val();
                let night_to_time = $(e).closest('.row').find('.to_dates').val();
                if (night_from_time != '' && night_to_time != '') {
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

        </script>
    </x-slot>
</x-app-layout>