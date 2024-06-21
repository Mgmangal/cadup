<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">ROLES</li>
            <li class="breadcrumb-item active">PERMISSION</li>
        </ul>
    </x-slot>
    <x-slot name="css">
        <link href="{{asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}" rel="stylesheet" />
    </x-slot>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Validation -->
    <x-errors class="mb-4" />
    <x-success class="mb-4" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Permission For @if(!empty($role)) => {{$role->name}} @endif</h3>
            <div>
                <a href="{{url()->previous()}}" class="btn btn-info btn-sm p-2">Back</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('app.settings.permissions.store', $role->id)}}" method="POST" id="manageForm">
                @csrf
                <div class="row">
                    @foreach($permissions as $key=> $permission)
                    <div class="col-md-2 p-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{$permission->id}}" id="permission{{$key}}" name="permissions[]" {{$role->hasPermissionTo($permission->name)?'checked':''}}/>
                            <label class="form-check-label" for="permission{{$key}}">{{$permission->name}}</label>
                        </div>
                    </div>
                    @endforeach
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <x-slot name="js">
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
    </x-slot>
</x-app-layout>