@extends('theme-one.layouts.app', ['title' => 'Manage Library', 'sub_title' => 'HR Library'])
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- <link href="{{asset('assets/plugins/select-picker/dist/picker.min.css')}}" rel="stylesheet" /> -->
@endsection
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">HR Library Add</h3>
        <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
    </div>
    <div class="card-body">
        <form action="{{route('user.library.hr_store')}}" method="POST" enctype="multipart/form-data" id="manageForm">
            @csrf
            <div class="row m-3">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title" class="form-label">Title<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Please Enter Title"
                            value="{{old('title')}}">
                        @error('title')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="resource_type" class="form-label">Resource Type</label>
                        <select name="resource_type" id="resource_type" class="form-control">
                            <option value="">Select Resource Type</option>
                            @foreach($resource_types as $resource)
                            <option value="{{$resource->id}}">{{$resource->name}}</option>
                            @endforeach
                        </select>
                        @error('resource_type')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="author" class="form-label required">Author</label>
                        <input type="text" class="form-control" id="author" name="author"
                            placeholder="Please Enter Author" value="{{old('author')}}">
                        @error('author')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label for="publisher" class="form-label required">Publisher</label>
                        <input type="text" class="form-control" id="publisher" name="publisher"
                            placeholder="Please Enter Publisher" value="{{old('publisher')}}">
                        @error('publisher')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" cols="30" rows="5"
                            placeholder="Please Enter Description">{{old('description')}}</textarea>
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
@endsection

@section('js')
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
                    window.location.href = "{{ route('user.library.hr') }}";
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
@endsection
