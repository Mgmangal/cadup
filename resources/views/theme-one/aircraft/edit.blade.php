@extends('theme-one.layouts.app', ['title' => 'Aircraft', 'sub_title' => $sub_title])
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- <link href="{{asset('assets/plugins/select-picker/dist/picker.min.css')}}" rel="stylesheet" /> -->
@endsection
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Aircraft {{ $sub_title }}</h3>
        {{-- @if (auth()->user()->can('Aircraft Edit')) --}}
        <a href="{{url()->previous()}}" class="btn btn-primary text-white">Go Back</a>
        {{-- @endif --}}
    </div>
    <div class="card-body">
        <form action="{{route('user.aircraft.update',$aircraft->id)}}" method="POST" enctype="multipart/form-data"
            id="manageForm">
            @csrf
            @method('PUT')
            <div class="row m-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="aircraft_cateogry" class="form-label">Aircraft Category<span
                                class="text-danger">*</span></label>
                        <select name="aircraft_cateogry" id="aircraft_cateogry" class="form-control">
                            <option value="">Select</option>
                            <option {{$aircraft->aircraft_cateogry == 'Fixed Wing' ? 'selected':''}} value="Fixed
                                Wing">Fixed Wing</option>
                            <option {{$aircraft->aircraft_cateogry == 'Rotor Wing' ? 'selected':''}} value="Rotor
                                Wing">Rotor Wing</option>
                            <option {{$aircraft->aircraft_cateogry == 'Fixed Wing Simulator' ? 'selected':''}}
                                value="Fixed Wing Simulator">Fixed Wing Simulator</option>
                            <option {{$aircraft->aircraft_cateogry == 'Rotor Wing Simulator' ? 'selected':''}}
                                value="Rotor Wing Simulator">Rotor Wing Simulator</option>
                        </select>
                        @error('aircraft_cateogry')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="aircraft_type" class="form-label">Aircraft Type<span
                                class="text-danger">*</span></label>
                        <select name="aircraft_type" id="aircraft_type" class="form-control">
                            <option value="">Select</option>
                            @foreach($masters as $master)
                            <option {{$aircraft->aircraft_type == $master->id ? 'selected':''}}
                                value="{{$master->id}}">{{$master->name}}</option>
                            @endforeach
                        </select>
                        @error('aircraft_type')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="manufacturer" class="form-label">Manufacturer<span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="manufacturer" name="manufacturer"
                            placeholder="Please Enter Manufacturer"
                            value="{{old('manufacturer',$aircraft->manufacturer)}}">
                        @error('manufacturer')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row m-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="type_model" class="form-label">Type Model<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="type_model" name="type_model"
                            placeholder="Please Enter Type Model" value="{{old('type_model',$aircraft->type_model)}}">
                        @error('type_model')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="call_sign" class="form-label">Call Sign<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="call_sign" name="call_sign"
                            placeholder="Please Enter Call Sign" value="{{old('call_sign',$aircraft->call_sign)}}">
                        @error('call_sign')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="me_se" class="form-label">ME/SE<span class="text-danger">*</span></label>
                        <select name="me_se" id="me_se" class="form-control">
                            <option value="">Select</option>
                            <option {{$aircraft->me_se == 'ME' ? 'selected':''}} value="ME">ME</option>
                            <option {{$aircraft->me_se == 'SE' ? 'selected':''}} value="SE">SE</option>
                        </select>
                        @error('me_se')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row m-3">

                <!--<div class="col-md-4">-->
                <!--    <div class="form-group">-->
                <!--        <label for="operation_start_date" class="form-label">Operation Start Date</label>-->
                <!--        <div class="input-group">-->
                <!--            <label class="input-group-text" for="operation_start_date">-->
                <!--                <i class="fa fa-calendar"></i>-->
                <!--            </label>-->
                <!--            <input type="text" class="form-control dates" id="operation_start_date" name="operation_start_date" placeholder="Please Enter Operation Start Date" onchange="validateDate();" value="{{old('operation_start_date',$aircraft->operation_start_date)}}">-->

                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->

                <!--<div class="col-md-4">-->
                <!--    <div class="form-group">-->
                <!--        <label for="operation_end_date" class="form-label">Operation End Date</label>-->
                <!--        <div class="input-group">-->
                <!--            <label class="input-group-text" for="operation_end_date">-->
                <!--                <i class="fa fa-calendar"></i>-->
                <!--            </label>-->
                <!--            <input type="text" class="form-control dates" id="operation_end_date" name="operation_end_date" placeholder="Please Enter Operation End Date" onchange="validateDate();" value="{{old('operation_end_date',$aircraft->operation_end_date)}}" />-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="manufacturing_year" class="form-label">Year of Manufacturing<span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="manufacturing_year" name="manufacturing_year"
                            placeholder="Please Enter Manufacturing Year"
                            value="{{old('type_model',$aircraft->manufacturing_year)}}">
                        @error('manufacturing_year')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="pilots" class="form-label">Pilots</label>
                        <div class="input-group">
                            <select class="form-control selct2" name="pilots[]" id="pilots" class="form-control"
                                multiple>
                                @foreach($pilots as $pilot)
                                <option {{!empty($aircraft->pilots)&&in_array($pilot->id,$aircraft->pilots) ?
                                    'selected':''}} value="{{$pilot->id}}">{{$pilot->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row m-3 text-center">
                <div class="col-md-12 ">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- <script src="{{asset('assets/plugins/select-picker/dist/picker.min.js')}}"></script> -->
<script>
    $('.selct2').select2({
                tags: true,
                tokenSeparators: [',', ' '],
                placeholder: "Select Pilots",
                allowClear: true,
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
                            window.location.href = "{{ route('user.aircrafts') }}";
                        } else {
                            $.each(response.error, function(fieldName, field) {
                                $('#manageForm').find('[name=' + fieldName + ']').addClass('is-invalid');
                                $('#manageForm').find('[name=' + fieldName + ']').after('<div class="invalid-feedback">' + field + '</div>');
                            })
                        }

                    }
                })
            })

            function validateDate() {
                var start_date = $("#operation_start_date").val();
                var end_date = $("#operation_end_date").val();
                // console.log(end_date.length, start_date.length);
                // console.log(Date.parse(toJSDate(start_date)), Date.parse(toJSDate(end_date)));
                if (start_date.length != 0 && end_date.length != 0 && Date.parse(toJSDate(start_date)) >= Date.parse(toJSDate(end_date))) {
                    // console.log(Date.parse(toJSDate(start_date)), Date.parse(toJSDate(end_date)));
                    $("#operation_end_date").val("");
                    warning("Operation End Date should be Greater than Operation Start Date.");
                }
            }

            function toJSDate(dateTime) {
                var date = dateTime.split("-");
                //(year, month, day, hours, minutes, seconds, milliseconds)
                //subtract 1 from month because Jan is 0 and Dec is 11
                return new Date(date[2], (date[1] - 1), date[0]);
            }

            $(".dates").datepicker({
                //defaultDate: new Date(),
                format: 'yyyy-mm-dd',
                autoclose: true,
                orientation: 'bottom auto',
            });
</script>
@endsection
