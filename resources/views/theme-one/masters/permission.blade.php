@extends('theme-one.layouts.app', ['title' => 'Masters', 'sub_title' => $sub_title])
@section('css')
{{-- <link href="{{ asset('assets/theme_one/lib/datatables.net-dt/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/theme_one/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css') }}" rel="stylesheet"> --}}
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">{{ $sub_title }} @if($role) For: {{$role->name}} @endif</h3>
        <div>
            <a href="{{url()->previous()}}" class="btn btn-primary">Go Back</a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{route('user.master.role_store', $role->id)}}" method="POST" id="manageForm">
            @csrf
            <div class="row">
                {{-- @php echo "
                <pre>" . print_r($permissions) . "</pre>"; die(); @endphp --}}
                @foreach($permissions as $key => $permission)
                <div class="col-md-2 p-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{$permission->id}}"
                            id="permission{{$key}}" name="permissions[]" {{$role->hasPermissionTo($permission->name) ?
                        'checked' : ''}} />
                        <label class="form-check-label" for="permission{{$key}}">{{$permission->name}}</label>
                    </div>
                </div>
                @endforeach
                <div class="col-md-12 mt-4 text-center">
                    <button type="submit" class="btn btn-success px-5">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>@endsection

@section('js')
{{-- <script src="{{ asset('assets/theme_one/lib/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/theme_one/lib/datatables.net-dt/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/theme_one/lib/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/theme_one/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js') }}"></script> --}}
<script>
    $('#manageForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: new FormData(this),
            beforeSend: function() {
                $('.btn').attr('disabled', 'disabled');
                preloader();
            },
            success: function(response) {
                if (response.success) {
                    success(response.message);
                } else {
                    error(response.message);
                }
            },
            complete: function() {
                $('.btn').removeAttr('disabled');
            }
        });
    });
</script>
@endsection
