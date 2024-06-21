<x-app-layout>
    <x-slot name="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('app.dashboard')}}">DASHBOARD</a></li>
            <li class="breadcrumb-item active">EMPLOYEE</li>
            <li class="breadcrumb-item active">ROLE</li>
        </ul>
    </x-slot>
    <x-slot name="css">
    </x-slot>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!-- Validation -->
    <x-errors class="mb-4" />
    <x-success class="mb-4" />
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Assign Pilot Role</h3>
            <a href="{{url()->previous()}}" class="btn btn-primary btn-sm p-2">Back</a>
        </div>
        <div class="card-body">
            <form action="{{route('app.pilot.roles.store', $user->id)}}" method="post">
                @csrf
                <div class="row">
                    @foreach($roles as $role)
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="roles[]" id="checkAll{{$role->id}}" value="{{$role->id}}" @if(in_array($role->id, $userRoles)) checked @endif>
                            <label class="form-check-label" for="checkAll{{$role->id}}">{{$role->name}}</label>
                        </div>
                    </div>
                    @endforeach
                </div>




                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <x-slot name="js">

    </x-slot>
</x-app-layout>