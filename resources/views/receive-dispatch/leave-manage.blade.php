<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('app.dashboard') }}">DASHBOARD</a></li>
            <li class="breadcrumb-item">RECEIPT & DISPATCH</li>
            <li class="breadcrumb-item active">LEAVE</li>
        </ul>
    </x-slot>
    <x-slot name="css">
        <link href="{{ asset('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}"
            rel="stylesheet">
    </x-slot>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Validation -->
    <x-errors class="mb-4" />
    <x-success class="mb-4" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Leave</h3>
            <a href="{{ url()->previous() }}" class="btn btn-primary btn-sm p-2">Back</a>
        </div>
        <div class="card-body">
            <form action="{{ route('app.receive.leave.store') }}" method="POST" enctype="multipart/form-data"
                id="manageForm" autocomplete="off">
                @csrf
                <div class="row m-3">
                    <input type="hidden" name="edit_id" id="edit_id" value="{{ $data->id ?? '' }}">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="letter_number" class="form-label">Reference Number<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="letter_number" name="letter_number" placeholder="Please Enter Reference Number" value="{{ $data->letter_number ?? old('letter_number') }}">
                            <input type="hidden"  id="letter_type" name="letter_type"  value="Leave Application">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date" class="form-label">Date<span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker" id="date" name="date" placeholder="Please Choose Date" value="{{ $data->date ?? old('date') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="address" class="form-label">File Name</label>
                            <textarea name="address" id="address" placeholder="Please Enter File Name" class="form-control">{{ $data->address ?? old('address') }}</textarea>
                            <input type="hidden" name="internal_external" value="Internal">
                            <input type="hidden" name="source" value="Section">
                        </div>
                    </div>
                    <!--<div class="col-md-4">-->
                    <!--    <label class="form-label mb-2">Internal/External<span class="text-danger">*</span></label>-->
                    <!--    <div class="form-group">-->
                    <!--        <div class="form-check form-check-inline">-->
                    <!--            <input class="form-check-input" name="internal_external" type="radio"-->
                    <!--                id="internalOption" value="Internal" {{ empty($data) ? 'checked' : '' }}-->
                    <!--                {{ !empty($data) && $data->internal_external == 'Internal' ? 'checked' : '' }}>-->
                    <!--            <label class="form-check-label" for="internalOption">Internal</label>-->
                    <!--        </div>-->
                    <!--        <div class="form-check form-check-inline">-->
                    <!--            <input class="form-check-input" name="internal_external" type="radio"-->
                    <!--                id="externalOption" value="External"-->
                    <!--                {{ !empty($data) && $data->internal_external == 'External' ? 'checked' : '' }}>-->
                    <!--            <label class="form-check-label" for="externalOption">External</label>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->


                    <!--<div class="col-md-4">-->
                    <!--    <div class="form-group">-->
                    <!--        <label for="source" class="form-label">Received From<span class="text-danger">*</span></label>-->
                    <!--        <select name="source" id="source" class="form-select source">-->
                    <!--            <option value="">Please Select</option>-->
                    <!--        </select>-->
                    <!--    </div>-->
                    <!--</div>-->
                    
                    <div class="col-md-4 section">
                        <div class="form-group">
                            <label for="section" class="form-label">Section<span class="text-danger">*</span></label>
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

                    <div class="col-md-4">
                        <div class="form-group receive_from">
                           
                            <label for="receive_from" class="form-label">Receivers Name</label>
                            <select name="receive_from" id="receive_from" class="form-select">
                                <option value="">Please Select</option>
                                 @if (!empty($to_users))
                                    @foreach ($to_users as $to)
                                        <option value="{{ $to->fullName() }}" {{ !empty($data) && $data->receive_to == $to->fullName() ? 'selected' : '' }}>
                                            {{ $to->fullName() }}
                                        </option>
                                    @endforeach

                                @endif
                            </select>
                           
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="to_section" class="form-label">To Section<span
                                    class="text-danger">*</span></label>
                            <select name="to_section" id="to_section" class="form-select"
                                onchange="getUserBySection(this, 'receive_to');">
                                <option value="">Please Select</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}"
                                        {{ !empty($data) && $data->to_section == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="receive_to" class="form-label">To<span class="text-danger">*</span></label>
                            <select name="receive_to" id="receive_to" class="form-select">
                                <option value="">Please Select</option>
                                @if (!empty($to_users))
                                    @foreach ($to_users as $to)
                                        <option value="{{ $to->fullName() }}" {{ !empty($data) && $data->receive_to == $to->fullName() ? 'selected' : '' }}>
                                            {{ $to->fullName() }}
                                        </option>
                                    @endforeach

                                @endif
                            </select>
                        </div>
                    </div>

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
        <script src="{{ asset('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

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
                            window.location.href = "{{ route('app.receive.leave') }}";
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
