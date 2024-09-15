<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item">Manage Library</li>
            <li class="breadcrumb-item active">FSDMS Library</li>
        </ul>
    </x-slot>
    <x-slot name="css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <!-- <link href="{{asset('assets/plugins/select-picker/dist/picker.min.css')}}" rel="stylesheet" /> -->

    </x-slot>
    <!-- Errors -->
    <x-errors class="mb-4" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">FSDMS Library Add</h3>
            <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
        </div>
        <div class="card-body">
            <form action="{{route('app.library.fsdms_store')}}" method="POST" enctype="multipart/form-data" id="manageForm">
                @csrf
                <div class="row m-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="title" class="form-label required">Library<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Please Enter Library Name" value="{{old('title')}}">
                            @error('title')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="parent_id" class="form-label">Parent Library</label>
                            <select name="parent_id" id="parent_id" class="form-control">
                                <option value="">Select Parent Library</option>
                                @foreach($libraries as $library)
                                <option value="{{$library->id}}">{{$library->title}}</option>
                                @endforeach
                            </select>
                            @error('parent_id')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="description" cols="30" rows="5" placeholder="Please Enter Description">{{old('description')}}</textarea>
                            @error('description')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="file">Upload File</label>
                            <input type="file" class="form-control" name="file" id="file">
                            @error('file')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
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




    <x-slot name="js">
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <!-- <script src="{{asset('assets/plugins/select-picker/dist/picker.min.js')}}"></script> -->
        <script>
            // $('.selct2').select2({
            //     tags: true,
            //     tokenSeparators: [',', ' '],
            //     placeholder: "Select Pilots",
            //     allowClear: true,
            // });
            $('#manageForm').submit(function(e) {
                e.preventDefault();
                $('#manageForm').find('.invalid-feedback').hide();
                $('#manageForm').find('.is-invalid').removeClass('is-invalid');
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    data: new FormData(this),
                    success: function(response) {
                        if (response.success) {
                            success(response.message);
                            window.location.href = "{{ route('app.library.fsdms') }}";
                        } else {
                            $.each(response.error, function(fieldName, field) {
                                $('#manageForm').find('[name=' + fieldName + ']').addClass('is-invalid');
                                $('#manageForm').find('[name=' + fieldName + ']').after('<div class="invalid-feedback">' + field + '</div>');
                            })
                        }

                    }
                })
            })

        </script>
    </x-slot>
</x-app-layout>
