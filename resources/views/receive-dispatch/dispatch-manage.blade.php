<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item">RECEIVE & DISPATCH</li>
            <li class="breadcrumb-item active">DISPATCH</li>
        </ul>
    </x-slot>
    <x-slot name="css">
        <link href="{{asset('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    </x-slot>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Validation -->
    <x-errors class="mb-4" />
    <x-success class="mb-4" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Dispatch</h3>
            <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
        </div>
        <div class="card-body">
            
            <form action="{{route('app.dispatch.store')}}" method="POST" enctype="multipart/form-data" id="manageForm">
                @csrf
                <div class="row m-3">
                    <input type="hidden" name="edit_id" id="edit_id" value="{{ !empty($data)? $data->id:'' }}">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="letter_number" class="form-label">Dispatch number<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="letter_number" name="letter_number" placeholder="Please Enter Dispatch number" value="{{ !empty($data)? $data->letter_number: old('letter_number') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="letter_type" class="form-label">Letter Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="letter_type" id="letter_type">
                                <option value="">Please Select</option>
                                <option {{ !empty($data) && $data->letter_type == "Bill" ? 'selected' : '' }} value="Bill"> Bill</option>
                                <option {{ !empty($data) && $data->letter_type == "Leave Application" ? 'selected' : '' }} value="Leave Application"> Leave Application</option>
                                <option {{ !empty($data) && $data->letter_type == "Letter" ? 'selected' : '' }} value="Letter"> Letter</option>
                                <option {{ !empty($data) && $data->letter_type == "Confidential Document" ? 'selected' : '' }} value="Confidential Document"> Confidential Document</option>
                                <option {{ !empty($data) && $data->letter_type == "Office Order" ? 'selected' : '' }} value="Office Order"> Office Order</option>
                                <option {{ !empty($data) && $data->letter_type == "Email" ? 'selected' : '' }} value="Email">Email</option>
                                <option {{ !empty($data) && $data->letter_type == "Other" ? 'selected' : '' }} value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 other_letter_type"
                        style="{{ !empty($data) && $data->letter_type == 'Other' ? 'display:block' : 'display:none' }};">
                        <div class="form-group">
                            <label for="other_letter_type" class="form-label">Other Letter Type<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="other_letter_type" name="other_letter_type" placeholder="Please Enter Other Letter Type" value="{{ $data->other_letter_type ?? old('other_letter_type') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date" class="form-label">Date<span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker" id="date" name="date" placeholder="Please Choose Date" value="{{ !empty($data->date)?date('d-m-Y',strtotime($data->date)): old('date') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="address" class="form-label">File Name</label>
                            <textarea name="address" id="address" placeholder="Please Enter File Name" class="form-control">{{ $data->address ?? old('address') }}</textarea>
                        </div>
                    </div>
                    

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dispatch_section" class="form-label">Dispatch Section<span class="text-danger">*</span></label>
                            <select name="dispatch_section" id="dispatch_section" class="form-select" onchange="getUserBySection(this, 'dispatch_from');">
                                <option value="">Please Select</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}" {{ !empty($data) && $data->dispatch_section == $section->id ? 'selected' : '' }}> {{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dispatch_from" class="form-label">Dispatch From<span class="text-danger">*</span></label>
                            <select name="dispatch_from" id="dispatch_from" class="form-select">
                                <option value="">Please Select</option>
                                @if (!empty($to_users))
                                    @foreach ($to_users as $to)
                                        <option value="{{ $to->id }}" {{ !empty($data) && $data->dispatch_from == $to->id ? 'selected' : '' }}>
                                            {{ $to->fullName() }}
                                        </option>
                                    @endforeach

                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label mb-2">Internal/External<span class="text-danger">*</span></label>
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="internal_external" required type="radio"
                                    id="internalOption" value="Internal" {{ empty($data) ? 'checked' : '' }}
                                    {{ !empty($data) && $data->internal_external == 'Internal' ? 'checked' : '' }}>
                                <label class="form-check-label" for="internalOption">Internal</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" name="internal_external" required type="radio"
                                    id="externalOption" value="External"
                                    {{ !empty($data) && $data->internal_external == 'External' ? 'checked' : '' }}>
                                <label class="form-check-label" for="externalOption">External</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="forwarded_to" class="form-label">Forwarded To<span class="text-danger">*</span></label>
                            <select name="forwarded_to" id="forwarded_to" class="form-select source">
                                <option value="">Please Select</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 other_source"
                        style="{{ !empty($data) && $data->forwarded_to == 'Other' ? 'display:block' : 'display:none' }}">
                        <div class="form-group">
                            <label for="other_source" class="form-label">Enter Other Type Name<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="other_source" name="other_source"
                                placeholder="Please Enter Other Type Name"
                                value="{{ $data->other_source ?? old('other_source') }}">
                        </div>
                    </div>

                    <div class="col-md-4 section"
                        style="{{ !empty($data) && $data->forwarded_to == 'Section' ? 'display:block' : 'display:none' }}">
                        <div class="form-group">
                            <label for="section" class="form-label">Select Section<span class="text-danger">*</span></label>
                            <select name="section" id="section" class="form-select"
                                onchange="getUserBySection(this, 'receive_from');">
                                <option value="">Please Select</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}"
                                        {{ !empty($data) && $data->section == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 receive_from_div"
                        style="{{ !empty($data) && isset($data->receiver) ? 'display:block' : 'display:none' }}">
                        <div class="form-group receive_from">
                            @if (!empty($data) && isset($data->receiver) && $data->forwarded_to == 'Section')
                            <label for="receive_from" class="form-label">Receiver Name<span class="text-danger">*</span></label>
                            <select name="receiver" id="receive_from" class="form-select">
                                @foreach ($from_users as $from)
                                <option value="{{ $from->fullName() }}" {{ !empty($data) && $data->receiver == $from->fullName() ? 'selected' : '' }}>
                                    {{ $from->fullName() }}
                                </option>
                                @endforeach
                            </select>
                            @else
                            <label for="receive_from" class="form-label">Receiver Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="receive_from" name="receiver" value="{{ $data->receiver ?? old('receiver') }}">
                            @endif
                        </div>
                    </div>


                    <!--<div class="col-md-4 address" style="{{ !empty($data) && $data->internal_external == 'External' ? 'display:block' : 'display:none' }}">-->
                    <!--    <div class="form-group">-->
                    <!--        <label for="address" class="form-label">Address<span-->
                    <!--                class="text-danger">*</span></label>-->
                    <!--        <textarea name="address" id="address" placeholder="Please Enter Address" class="form-control">{{ $data->address ?? old('address') }}</textarea>-->
                    <!--    </div>-->
                    <!--</div>-->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="subject" class="form-label">Subject<span
                                    class="text-danger">*</span></label>
                            <textarea name="subject" id="subject" placeholder="Please Enter Subject" class="form-control">{{ $data->subject ?? old('subject') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="document">Document</label>
                            <input type="file" class="form-control" name="document" id="document">
                            @if(!empty($data->document))
                                <a target="blank" href="{{asset('uploads/receive-dispatch/'.$data->document)}}" class="btn btn-sm btn-success"><i class="fas fa-lg fa-fw me-2 fa-eye"></i></a>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="row text-center">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <x-slot name="js">
        <script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
        <script src="{{asset('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
        <script>
            $('.datepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy',
                calendarWeeks: false,
                zIndexOffset: 9999,
                orientation: "bottom"
            });
            $('#manageForm').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var data = new FormData(form[0]);
                clearError(form);
                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: function() {
                        showLoader();
                    },
                    success: function(response) {
                        if (response.success) {
                            hideLoader();
                            success(response.message);
                            window.location.href = "{{ route('app.dispatch') }}";
                        } else {
                            $.each(response.error, function(fieldName, field) {
                                form.find('[name=' + fieldName + ']').addClass(
                                    'is-invalid');
                                form.find('[name=' + fieldName + ']').after(
                                    '<div class="invalid-feedback">' + field + '</div>');
                                    hideLoader();
                            })
                        }
                    }
                })
            });
        </script>
        <script>
            $(document).ready(function() {
                const changeInternalExternal = () => {
                    const value = $('input[name="internal_external"]:checked').val();
                    let internal_option = '<option value="">Please Select</option>' +
                        '<option {{ !empty($data) && $data->forwarded_to == 'Secretariat' ? 'selected' : '' }} value="Secretariat">Secretariat</option>' +
                        '<option {{ !empty($data) && $data->forwarded_to == 'Directorate' ? 'selected' : '' }} value="Directorate">Directorate</option>' +
                        '<option {{ !empty($data) && $data->forwarded_to == 'Section' ? 'selected' : '' }} value="Section">Section</option>';
                    let external_option = '<option value="">Please Select</option>' +
                        '<option {{ !empty($data) && $data->forwarded_to == 'Department' ? 'selected' : '' }} value="Department">Department</option>' +
                        '<option {{ !empty($data) && $data->forwarded_to == 'Company' ? 'selected' : '' }} value="Company">Company</option>' +
                        '<option {{ !empty($data) && $data->forwarded_to == 'Airport Authority Of India' ? 'selected' : '' }} value="Airport Authority Of India">Airport Authority Of India</option>' +
                        '<option {{ !empty($data) && $data->forwarded_to == 'DGCA' ? 'selected' : '' }} value="DGCA">DGCA</option>' +
                        '<option {{ !empty($data) && $data->forwarded_to == 'Other' ? 'selected' : '' }} value="Other">Other</option>';

                    if (value == 'Internal') {
                        $('.other_source').hide();
                        $('.address').hide();
                        $('#forwarded_to').html(internal_option);
                    } else if (value == 'External') {
                        $('.section').hide();
                        $('.address').show();
                        $('#forwarded_to').html(external_option);
                    }
                };

                $('input[name="internal_external"]').change(changeInternalExternal);
                changeInternalExternal();



                $('#letter_type').change(function() {
                    const value = $(this).val();
                    if (value == 'Other') {
                        $('.other_letter_type').show();
                    } else {
                        $('.other_letter_type').hide();
                    }
                });

            });
        </script>
        <script>
            $('.source').change(function() {
                const value = $(this).val();
                $('.other_source, .section').hide();
                let receive_from_input =
                    '<label for="receive_from" class="form-label">Receiver Name<span class="text-danger">*</span></label>' +
                    '<input type="text" class="form-control" id="receive_from" name="receiver" placeholder="Please Enter Receiver Name" value="{{ $data->receiver ?? old('receiver') }}">';
                let receive_from_select = '<label for="receive_from" class="form-label">Select Receiver<span class="text-danger">*</span></label>' +
                    '<select name="receiver" id="receive_from" class="form-select">' +
                        '<option value="">Please Select</option>' +
                    '</select>';




                if (value == 'Section') {
                    $('.section').show();
                    $('.receive_from').html(receive_from_select);
                } else if (value == 'Other') {
                    $('.other_source').show();
                    $('.receive_from').html(receive_from_input);
                } else {
                    $('.receive_from').html(receive_from_input);
                }

                if (value) {
                    $('.receive_from_div').show();
                } else {
                    $('.receive_from_div').hide();
                }
            });

            function getUserBySection(e, select_id) {
                var id = $(e).val();
                $.ajax({
                    url: '{{ route('user.getUserBySection') }}',
                    method: 'post',
                    dataType: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                        'id': id
                    },
                    success: function(data) {
                        $('#' + select_id).html(data);

                    }
                });

            }
        </script>
    </x-slot>
</x-app-layout>
